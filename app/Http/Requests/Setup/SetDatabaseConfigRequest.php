<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;

class SetDatabaseConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $filePath = storage_path('izy-starter/izy-fallback-config.php');

        // Se il file non esiste, consideriamo che il CMS non sia inizializzato
        if (! file_exists($filePath)) {
            return true;
        }

        // Includi il file per ottenere la configurazione
        $config = include $filePath;

        // Controllo se 'initialized' è false
        return isset($config['initialized']) && $config['initialized'] === false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dbType' => 'required|string',
            'host' => 'required|string',
            'dbName' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'initialized' => 'required|boolean',
        ];
    }
}
