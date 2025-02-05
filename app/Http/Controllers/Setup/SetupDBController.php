<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Services\ConfigFallbackService;
use App\Services\DatabaseConfigService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\UserCheckHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class SetupDBController extends Controller
{
    private $izp_dbConfig;
    private $databaseConfigService;

    // Inietta il servizio nel costruttore
    public function __construct(DatabaseConfigService $databaseConfigService)
    {
        $this->izp_dbConfig = config('izy-dbconfig');
        $this->databaseConfigService = $databaseConfigService; // Assicurati che $this->izp_dbConfig sia un array
    }


    public function render(): Response
    {
        // Assicurati che la chiave 'password' sia presente ma vuota per salvaguardare la sicurezza
        $this->izp_dbConfig['password'] = '';
        $this->izp_dbConfig['username'] = '';

        return Inertia::render('Setup/SetupDb', [
            'dbConfig' => $this->izp_dbConfig,
            'title' => config('izy-admin-titles.setupdb'),
            'status' => session('status'),
        ]);
    }

    public function setconfig(Request $request)
    {
        // recupero del driver della configurazione corrente
        $oldDefaultConfigDriver = config('database.default');
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
                //settata la connessione al nuovo db per questa richiesta
                $this->databaseConfigService->connectConfig();
                // Fai partire le migrazioni iniziali se necessario
                $this->databaseConfigService->runStartingIzyMigrations();
                // migra i dati delle vecchie sessioni salvate sulla vecchia connessione
                $this->databaseConfigService->migrateOldSession($oldDefaultConfigDriver);
                //salva il file di configurazione
                ConfigFallbackService::save($validatedData);
                //seeder per inizializzare le tabelle dei ruoli e dei permessi
                $this->databaseConfigService->runPermissionSeeder();
                $this->databaseConfigService->runRoleSeeder();
                // setta il flag riguardante la cache di configurazione con la configurazione corrente
                $this->databaseConfigService->setConfigCacheDbInit();
                // Reindirizza alla rotta 'izyAdmin' con un messaggio di successo
                return redirect()->route('izy.admin');
            } else {
                // Passa il messaggio di errore e rimanda alla rotta precedente
                return redirect()->back()->with('error', 'Connessione al database fallita. Riprova.');
            }
        } catch (\Exception $e) {
            Log::channel('stack')->debug('Detailed debug', ['data' => $e->getMessage()]);
        }
    }
}
