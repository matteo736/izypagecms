<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;


class ConfigFallbackService
{
    protected static $filePath = 'storage/izy-starter/izy-fallback-config.php';

    /**
     * Salva l'intero array nel file di configurazione.
     * Effettua un merge con l'array esistente.
     */
    public static function save(array $config): bool
    {
        $filePath = base_path(self::$filePath);

        // Controlla se il file esiste e recupera l'array attuale
        $currentConfig = [];
        if (file_exists($filePath)) {
            $currentConfig = include $filePath;
            if (!is_array($currentConfig)) {
                $currentConfig = [];
            }
        }

        // Unisce l'array esistente con quello nuovo (il nuovo sovrascrive i valori vecchi)
        $mergedConfig = array_replace_recursive($currentConfig, $config);

        // Genera il contenuto del file
        $content = "<?php\n\nreturn " . var_export($mergedConfig, true) . ";\n";

        // Scrive il nuovo contenuto nel file
        if (file_put_contents($filePath, $content) === false) {
            Log::error("Failed to write configuration file at $filePath");
            return false;
        }
        return true;
    }


    /**
     * Modifica un singolo elemento del file di configurazione.
     */
    public static function update(string $key, mixed $value): bool
    {
        $filePath = base_path(self::$filePath);

        // Controlla se il file esiste
        if (!file_exists($filePath)) {
            Log::error("Configuration file not found at $filePath");
            return false;
        }

        // Carica il file come array
        $config = include $filePath;

        // Verifica che il file restituisca un array valido
        if (!is_array($config)) {
            Log::error("Invalid configuration file format at $filePath");
            return false;
        }

        // Aggiorna il valore della chiave specificata
        $config[$key] = $value;

        // Salva il nuovo array nel file
        return self::save($config);
    }
}
