<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Recupera l'ID del post dalla rotta
        $postId = $this->route('id');
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                // La regola 'unique' ignora il post che si sta aggiornando
                Rule::unique('posts')->ignore($postId),
            ],
            'content' => 'required|array', // Deve essere un array (il JSON della pagina)
            'status' => 'required|string|in:published,draft,trashed', // Valore stretto a published,draft,trashed
        ];
    }
}
