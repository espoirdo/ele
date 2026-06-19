<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'nullable|exists:categories,id',
            'image_couverture' => 'nullable|image|max:5120',
            'date' => 'required|date|after:today',
            'heure' => 'required|date_format:H:i',
            'lieu' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'est_gratuit' => 'boolean',
            'premium_mise_en_avant' => 'boolean',
            'premium_newsletter' => 'boolean',
            'premium_reseaux' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est requis',
            'description.required' => 'La description est requise',
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'date.required' => 'La date est requise',
            'date.after' => 'La date doit être dans le futur',
            'heure.required' => 'L\'heure est requise',
            'lieu.required' => 'Le lieu est requis',
        ];
    }
}
