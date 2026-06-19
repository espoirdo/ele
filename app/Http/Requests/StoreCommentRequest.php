<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'contenu' => 'required|string|max:1000',
            'note' => 'required|integer|between:1,5',
        ];
    }

    public function messages(): array
    {
        return [
            'contenu.required' => 'Le commentaire est requis',
            'note.required' => 'La note est requise',
            'note.between' => 'La note doit être entre 1 et 5',
        ];
    }
}
