<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientBillingRequest;
use App\Http\Requests\UpdateClientBillingRequest;
use App\Models\BillingAddress;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientBillingController extends Controller
{
    public function show($clientId)
    {
        $client = Client::findOrFail($clientId);
        $billingAddress = $client->billingAddress;
        
        if (!$billingAddress) {
            return response()->json(['message' => 'No existe dirección de facturación registrada'], 404);
        }

        return response()->json($billingAddress);
    }

    public function store(StoreClientBillingRequest $request, $clientId)
    {
        $client = Client::findOrFail($clientId);

        // Verificar si ya existe una dirección de facturación
        if ($client->billingAddress) {
            return response()->json(['message' => 'El cliente ya tiene una dirección de facturación registrada'], 409);
        }

        $data = $request->validated();
        $data['client_id'] = $clientId;

        BillingAddress::create($data);

        return response()->json(
            ['message' => 'Dirección de facturación creada exitosamente'],
            201
        );
    }

    public function update(UpdateClientBillingRequest $request, $clientId)
    {
        $client = Client::findOrFail($clientId);
        $billingAddress = $client->billingAddress;

        if (!$billingAddress) {
            return response()->json(['message' => 'No existe dirección de facturación registrada'], 404);
        }

        $data = $request->validated();
        $billingAddress->update($data);

        return response()->json(['message' => 'Dirección de facturación actualizada exitosamente']);
    }

    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        $billingAddress = $client->billingAddress;

        if (!$billingAddress) {
            return response()->json(['message' => 'No existe dirección de facturación registrada'], 404);
        }

        $billingAddress->delete();

        return response()->json([], 204);
    }
}
