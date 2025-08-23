<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\DataQueryService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DataQueryService $dataQueryService)
    {
        $params = request()->only(['search', 'per_page', 'sort_field', 'sort_direction', 'category_id', 'supplier_id']);
        $products = $dataQueryService->getFilteredList(
            Product::with(['category', 'supplier']),
            $params
        );
        return ProductListResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $request->file('image') ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImageAndGetRelativePath($image);
            $data['image_path'] = $relativePath;
            $data['image_mime'] = $image->getMimeType();
            $data['image_size'] = $image->getSize();
        }

        unset($data['image']); // Remove the original image file from the data array

        $product = Product::create($data);


        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Eager load the category and supplier relationships
        $product->load(['category', 'supplier']);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {

        $data = $request->validated();

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImageAndGetRelativePath($image);
            $data['image_path'] = $relativePath;
            $data['image_mime'] = $image->getMimeType();
            $data['image_size'] = $image->getSize();
        }

        unset($data['image']); // Remove the original image file from the data array


        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Suppresion du produit effectuée'], 200);
    }

    public function saveImageAndGetRelativePath(\Illuminate\Http\UploadedFile $image)
    {
        // Lire le contenu du fichier et calculer son empreinte unique
        $hash = sha1_file($image->getPathname());
        $extension = $image->getClientOriginalExtension();
        $filename = $hash . '.' . $extension;
        $directory = 'images/products';

        $relativePath = $directory . '/' . $filename;


        // Vérifier si le fichier existe déjà
        if (Storage::disk('public')->exists($relativePath)) {
            return $relativePath;
        }

        // Stocke dans storage/app/public/products
        $image->storeAs('products', $filename, 'public');

        // Retourne le chemin accessible via /storage/products/xxxx.jpg
        return 'storage/products/' . $filename;
    }
}
