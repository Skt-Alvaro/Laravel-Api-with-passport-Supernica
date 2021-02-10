<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function store(Request $req)
    {
        $rules = array(
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|int'
        );
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        } else {
            $product = Product::create($req->all());
            return response()->json([
                'response' => 'Producto creado con éxito',
                'product' => $product
            ], 200);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    public function update(Request $req, Product $product)
    {
        $rules = array(
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|int'
        );
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        } else {
            $new_product = $product->update($req->all());
            return response()->json([
                'response' => 'Producto actualizado con éxito',
                'product' => $product
            ], 200);
        }
    }

    public function destroy(Product $product)
    {
        $si = $product;
        $product->delete();
        return response()->json([
            'response'=>'Producto eliminado con éxito',
            'producto eliminado' => $si
        ], 200);
    }
}
