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
        $file = storage_path('izy-starter/izy-fallback-config.php');
        if (!is_readable($file)) {
            return true;
        }
        $config = include $file;
        if (!is_array($config)) {
            return true; // fallback prudente
        }
        return array_key_exists('initialized', $config) && $config['initialized'] === false;
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
