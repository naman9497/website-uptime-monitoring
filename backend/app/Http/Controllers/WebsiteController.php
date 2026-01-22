<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebsiteResource;
use App\Models\Client;

class WebsiteController extends Controller
{
    public function index(Client $client)
    {
        $websites = $client->websites()->with('currentStatus')->get();

        return WebsiteResource::collection($websites);
    }
}
