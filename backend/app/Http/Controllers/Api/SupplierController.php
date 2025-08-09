<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Address;
use App\Models\Supplier;
use App\Services\DataQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataQueryService $dataQueryService)
    {
        if ($request->query('all') === 'true') {
            // Récupérer toutes les données sans tri ni pagination
            $suppliers = Supplier::with('address')->get();
            return SupplierResource::collection($suppliers);
        }

        // Sinon, on applique les filtres, le tri et la pagination
        $params = $request->only(['search', 'per_page', 'sort_field', 'sort_direction']);
        $suppliers = $dataQueryService->getFilteredList(
            Supplier::with('address'),
            $params
        );

        return SupplierResource::collection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
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
            $supplier = Supplier::create($data);

            $supplier->setRelation('address', $address);

            DB::commit();

            return new SupplierResource($supplier->load('address'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create retail store: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        // Charger les produits et l'adresse associée au fournisseur
        $supplier->load(['address', 'products']);

        return new SupplierResource($supplier);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {
            // Mettre à jour l'adresse associée
            $addressData = $data['address'];
            unset($data['address']);

            // Si l'adresse existe, on la met à jour, sinon on en crée une nouvelle
            if ($supplier->address) {
                $supplier->address->update($addressData);
            } else {
                $address = Address::create($addressData);
                $data['address_id'] = $address->id;
            }

            // Mettre à jour le fournisseur
            $supplier->update($data);

            DB::commit();

            return new SupplierResource($supplier->load('address'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update supplier: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();

        try {
            // Supprimer le fournisseur et son adresse associée
            $supplier->address()->delete();
            $supplier->delete();

            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete supplier: ' . $e->getMessage()], 500);
        }
    }
}
