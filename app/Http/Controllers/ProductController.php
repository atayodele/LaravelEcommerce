<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Exceptions\ProductNotBelongsToUser;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show');
    }

    /**
     * @SWG\Get(
     *   path="/api/products",
     *   tags={"Product"},
     *   summary="Get All Products",
     *   operationId="testing",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    public function index()
    {
        return ProductCollection::collection(Product::paginate(20));
    }

    /**
     * @SWG\Post(
     *   path="/api/products",
     *      tags={"Product"},
     *      operationId="ApiSaveProduct",
     *      summary="Add Product",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/json"},
     *      @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *      @SWG\Parameter(name="description", in="formData", required=true, type="string"),
     *      @SWG\Parameter(name="stock", in="formData", required=true, type="number"),
     *      @SWG\Parameter(name="price", in="formData", required=true, type="number"),
     *      @SWG\Parameter(name="discount", in="formData", required=true, type="number"),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    public function store(Request $request)
    {
        $product = new Product;
        $product->name = $request->name;
        $product->detail = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->save();
        return response([
            'data' => new ProductResource($product)
        ],Response::HTTP_CREATED);
    }

    /**
     * @SWG\Get(
     *   path="/api/products/{product}",
     *      tags={"Product"},
     *      operationId="ApiShowProduct",
     *      summary="Show Product By Product Id",
     *      @SWG\Parameter(name="product", in="path", required=true, type="number"),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * @SWG\Put(
     *   path="/api/products/{product}",
     *      tags={"Product"},
     *      operationId="ApiUpdateProduct",
     *      summary="Update Product",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/json"},
     *      @SWG\Parameter(name="product", in="path", required=true, type="number"),
     *      @SWG\Parameter(name="name", in="formData", required=true, type="string"),
     *      @SWG\Parameter(name="description", in="formData", required=true, type="string"),
     *      @SWG\Parameter(name="stock", in="formData", required=true, type="number"),
     *      @SWG\Parameter(name="price", in="formData", required=true, type="number"),
     *      @SWG\Parameter(name="discount", in="formData", required=true, type="number"),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    public function update(Request $request, Product $product)
    {
        $this->ProductUserCheck($product);
        $request['detail'] = $request->description;
        unset($request['description']);
        $product->update($request->all());
        return response([
            'data' => new ProductResource($product)
        ],Response::HTTP_CREATED);
    }

    /**
     * @SWG\Delete(
     *   path="/api/products/{product}",
     *      tags={"Product"},
     *      operationId="ApiDeleteProduct",
     *      summary="Delete Product By Product Id",
     *      @SWG\Parameter(name="product", in="path", required=true, type="number"),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
     *
     */
    public function destroy(Product $product)
    {
        $this->ProductUserCheck($product);
        $product->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    public function ProductUserCheck($product)
    {
        if (Auth::id() !== $product->user_id) {
            throw new ProductNotBelongsToUser;
        }
    }
}
