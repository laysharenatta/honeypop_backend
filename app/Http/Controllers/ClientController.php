<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    public function store(StoreClientRequest $request)
    {
        $client = $request->validated();
        $client['fecha_registro'] = now();
        Client::create($client);
        return response()->json(
            ['message' => 'Cliente creado exitosamente'],
            201
        );
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $data = $request->validated();
        $client->update($data);
        return response()->json(['message' => 'Cliente actualizado exitosamente']);
    }   


    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json([], 204);
    }
}
