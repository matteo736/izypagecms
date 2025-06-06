<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'layout' => 'required|array', // Deve essere un array (il JSON della pagina)
            'postTypeId' => 'nullable|int|exists:post_types,id',// ID del tipo di post che puo essere null perche se aggiorniamo un post esistente non lo modifichiamo
        ];
    }
}
