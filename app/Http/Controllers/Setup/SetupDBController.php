<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Setup\SetDatabaseConfigRequest;
use Inertia\Response;
use Inertia\Inertia;
use App\Services\ConfigFallbackService;
use App\Services\DatabaseConfigService;
use Illuminate\Support\Facades\Log;

class SetupDBController extends Controller
{
    private $databaseConfigService;

    // Inietta il servizio nel costruttore
    public function __construct(DatabaseConfigService $databaseConfigService)
    {
        $this->databaseConfigService = $databaseConfigService; // Assicurati che $this->izp_dbConfig sia un array
    }

    /**
     * Mostra la vista per la configurazione del database
     *
     * @return Response
     */
    public function render(): Response
    {
        // manda la configurazione attiva del db senza la password
        $clear_config = $this->databaseConfigService->getConfig();
        $clear_config['password'] = '';
        // manda la vista con la configurazione del db
        return Inertia::render('Setup/SetupDb', [
            'dbConfig' => $clear_config,
            'title' => config('izy-admin-titles.setupdb'),
            'status' => session('status'),
        ]);
    }

    /**
     * Esegue i seeder per inizializzare il db.
     *
     * @param null
     * @return void
     */
    private function seedDefault(): void
    {
        $this->databaseConfigService->runPermissionSeeder();
        $this->databaseConfigService->runRoleSeeder();
        $this->databaseConfigService->runPostTypeSeeder();
    }

    /**
     * Esegue il redirect dopo il setup del db.
     *
     * @param null
     * @return Response
     */
    private function redirectAfterSetup()
    {
        return User::count() == 0
            ? redirect()->route('register', ['isFirstUser' => true])
            : redirect()->route('izy.admin');
    }

    /**
     * Inizializza il db.
     *
     * @param array $validatedData
     * @param string $oldConfigDriver
     * @param string $oldDbName
     * @param string $sessionId
     * @return void
     */
    private function initializeDatabase(
        string $oldConfigDriver,
        string $oldDbName,
        string $sessionId
    ): void {
        try {
            $this->databaseConfigService->connectConfig();
            $this->databaseConfigService->runStartingIzyMigrations();
            $this->databaseConfigService->migrateActiveSession($oldConfigDriver, $oldDbName, $sessionId);
            $this->seedDefault();
        } catch (\Throwable $e) {
            Log::channel('stack')->debug('Errore durante l’inizializzazione del DB', ['data' => $e->getMessage()]);
            throw $e; // rilancio l'eccezione, così il controller può gestire il redirect con errore
        }
    }


    /**
     * Imposta la configurazione del database.
     *
     * @param Request $request
     * @return Response
     */
    public function setconfig(SetDatabaseConfigRequest $request)
    {
        // recupero del driver e il nome della configurazione corrente
        $oldConfigDriver = config('database.default');
        $oldDbName = config("database.connections.{$oldConfigDriver}.database");
        // Recupera l'ID della sessione attiva prima di cambiare la configurazione del database
        $sessionId = session()->getId();
        // Validazione dei dati
        $validatedData = $request->validated();

        // Aggiorna i dati per la connessione al database nel servizio
        $this->databaseConfigService->updateData($validatedData);

        if (!$this->databaseConfigService->testConnection()) {
            return redirect()->back()->with('error', 'Connessione al database fallita. Riprova.');
        }

        // Step 1: prepara il DB
        $this->initializeDatabase($oldConfigDriver, $oldDbName, $sessionId);

        // salva la configurazione nel file di fallback e aggiorna la cache
        ConfigFallbackService::save($validatedData);
        $this->databaseConfigService->setConfigCacheDbInit();

        // Step 2: decidi il redirect
        return $this->redirectAfterSetup();
    }
}
