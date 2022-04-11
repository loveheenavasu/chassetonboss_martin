<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;

class ListingController extends Controller
{
    public function index()
    {
        return Listing::query()->withCount('emails')->get();
    }
}
