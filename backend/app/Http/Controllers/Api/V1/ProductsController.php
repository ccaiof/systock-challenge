<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::query()->paginate();

        return ProductResource::collection($products);
    }

    public function indexByUser(User $user)
    {
        $products = $user->products()->paginate();

        return ProductResource::collection($products);
    }

    public function storeByUser(CreateProductRequest $request, User $user)
    {
        $product = $user->products()->create($request->validated());

        return ProductResource::make($product)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $product = Product::query()->findOrFail($id);

        return ProductResource::make($product);
    }

    public function store(CreateProductRequest $request)
    {
        $product = Product::query()->create($request->validated());

        return ProductResource::make($product)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::query()->findOrFail($id);

        $product->fill($request->validated());
        $product->save();

        return ProductResource::make($product);
    }

    public function destroy(int $id)
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
