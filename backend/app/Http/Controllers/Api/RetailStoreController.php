<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RetailStoreRequest;
use App\Http\Resources\RetailStoreResource;
use App\Models\Address;
use App\Models\RetailStore;
use App\Services\DataQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetailStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataQueryService $dataQueryService)
    {

        if ($request->query('all') === 'true') {
            // Récupérer toutes les données sans tri ni pagination
            $retailStores = RetailStore::with('address')->get();
            return RetailStoreResource::collection($retailStores);
        }
        // Sinon, on applique les filtres, le tri et la pagination
        $params = $request->only(['search', 'per_page', 'sort_field', 'sort_direction']);
        $retailStores = $dataQueryService->getFilteredList(
            RetailStore::with('address'),
            $params
        );


        return RetailStoreResource::collection($retailStores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RetailStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            //Création de l'adresse 
            $addressData = $data['address'];
            unset($data['address']);

            $address = Address::create($addressData);

            // Création du point de vente associé à l'adresse
            $data['address_id'] = $address->id;
            $retailStore = RetailStore::create($data);

            $retailStore->setRelation('address', $address);

            DB::commit();

            return new RetailStoreResource($retailStore->load('address'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create retail store: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RetailStore $retailStore)
    {
        // Charger l'adresse associée au point de vente
        $retailStore->load('address');

        return new RetailStoreResource($retailStore);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RetailStoreRequest $request, string $id)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {

            // Trouver le point de vente par ID
            $retailStore = RetailStore::findOrFail($id);

            // Mettre à jour l'adresse associée
            $addressData = $data['address'];
            unset($data['address']);

            // Si l'adresse existe, on la met à jour, sinon on en crée une nouvelle
            if ($retailStore->address) {
                $retailStore->address->update($addressData);
            } else {
                $address = Address::create($addressData);
                $data['address_id'] = $address->id;
            }

            // Mettre à jour le point de vente
            $retailStore->update($data);

            DB::commit();

            return new RetailStoreResource($retailStore->load('address'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update retail store: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RetailStore $retailStore)
    {
        DB::beginTransaction();
        try {
            // Supprimer le point de vente et son adresse associée
            $address = $retailStore->address;
            $retailStore->delete();

            if ($address) {
                $address->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Retail store deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete retail store: ' . $e->getMessage()], 500);
        }
    }
}
