<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEmailsBatch;
use App\Models\Email;
use App\Models\Listing;

class EmailController extends Controller
{
    public function storeBatch(StoreEmailsBatch $request)
    {
        $listing = Listing::query()->findOrFail($request->listing_id);

        $emails = collect($request->emails)->map(function ($email) {
            return Email::query()->firstOrCreate([
                'email' => $email
            ]);
        });

        $listing->emails()->syncWithoutDetaching(
            $emails->pluck('id')->toArray()
        );

        return response('', 201);
    }
}
