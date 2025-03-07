<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
     * Imposta la configurazione del database
     *
     * @param Request $request
     * @return Response
     */
    public function setconfig(Request $request)
    {
        // recupero del driver e il nome della configurazione corrente
        $oldConfigDriver = config('database.default');
        $oldDbName = config("database.connections.{$oldConfigDriver}.database");
        // Recupera l'ID della sessione attiva prima di cambiare la configurazione del database
        $sessionId = session()->getId();
        // Validazione dei dati
        $validatedData = $request->validate([
            'dbType' => 'required|string',
            'host' => 'required|string',
            'dbName' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'initialized' => 'required|boolean'
        ]);

        // Aggiorna i dati per la connessione al database
        $this->databaseConfigService->updateData($validatedData);

        try {
            // Controlla la connessione al database
            if ($this->databaseConfigService->testConnection()) {
                // Connessione al database riuscita
                //--------------------------------------------------------------------------------
                //setta la connessione al nuovo db per questa richiesta
                $this->databaseConfigService->connectConfig();
                // Fai partire le migrazioni iniziali se necessario
                $this->databaseConfigService->runStartingIzyMigrations();
                // migra i dati delle vecchie sessioni salvate sulla vecchia connessione
                $this->databaseConfigService->migrateActiveSession($oldConfigDriver, $oldDbName, $sessionId);
                //seeder per inizializzare le tabelle dei ruoli e dei permessi
                $this->databaseConfigService->runPermissionSeeder();
                $this->databaseConfigService->runRoleSeeder();
                //salva il file di configurazione
                ConfigFallbackService::save($validatedData);
                //--------------------------------------------------------------------------------
                /*
                 * viene usata la cache per evitare di creare race condition tra le richieste
                 * di scrittura nel file di configurazione izy-fallback
                 */
                $this->databaseConfigService->setConfigCacheDbInit();
                /*
                 * se la tabella degli utenti è vuota reindirizza alla registrazione
                 * altrimenti reindirizza alla dashboard
                 */
                if (User::count() == 0) {
                    // Reindirizza alla rotta 'register' se non ci sono utenti
                    return redirect()->route('register', ['isFirstUser' => true]);
                } else {
                    // Reindirizza alla rotta 'izyAdmin'
                    return redirect()->route('izy.admin');
                }
            } else {
                // Passa il messaggio di errore e rimanda alla rotta precedente
                return redirect()->back()->with('error', 'Connessione al database fallita. Riprova.');
            }
        } catch (\Exception $e) {
            Log::channel('stack')->debug('Detailed debug', ['data' => $e->getMessage()]);
        }
    }
}
