<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'error' => false,
            'response' => $products
        ], 200);
    }

    public function store(Request $req)
    {
        if ($req->file('file') == null) {
            $req->file = "";
        } else {
            $image = $req->file('file')->store('public/images');
            $url = Storage::url($image);
            $req->file = $url;
            return $req->all();
        }

        // $rules = array(
        //     'name' => 'required',
        //     'price' => 'required',
        //     'file' => 'required|image'
        // );
        // $validator = Validator::make($req->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => true,
        //         'response' => $validator->errors()
        //     ], 401);
        // } else {
        //     $product = Product::create($req->all());
        //     return response()->json([
        //         'error' => false,
        //         'response' => $product,
        //         'si' => $req->all()
        //     ], 200);
        // }
    }

    public function show(Product $product)
    {
        return response()->json([
            'error' => true,
            'response' => $product
        ], 200);
    }

    public function update(Request $req, Product $product)
    {
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'image' => 'required|image'
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'response' => $validator->errors()
            ], 401);
        } else {
            $new_product = $product->update($req->all());
            return response()->json([
                'error' => false,
                'response' => $product,
            ], 200);
        }
    }

    public function destroy(Product $product)
    {
        $si = $product;
        $product->delete();
        return response()->json([
            'error' => false,
            'response' => $si
        ], 200);
    }
}
