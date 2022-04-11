<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailsBatch extends FormRequest
{
    public function rules(): array
    {
        return [
            'listing_id' => ['required', 'exists:listings,id'],
            'emails' => ['required', 'array', 'min:1'],
            'emails.*' => ['required', 'email']
        ];
    }
}
