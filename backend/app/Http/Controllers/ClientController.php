<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {}

    public function index()
    {
        $clients = $this->clientRepository->all();

        return ClientResource::collection($clients);
    }
}
