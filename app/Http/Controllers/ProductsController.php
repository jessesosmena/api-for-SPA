<?php

namespace App\Http\Controllers;

use App\Product; 
use Illuminate\Http\Request;

use Validator;

class ProductsController extends Controller
{
    public function index(){
        
       return Product::orderBy('created_at','desc')->get();
    }

    public function store(Request $request){

        $exploded = explode(',', $request->image);

        $decoded = base64_decode($exploded[1]);

        if(str_contains($exploded[0], 'jpeg')){
            $extension = 'jpg';
        } else {
            $extension = 'png';
        }

        $fileName = time().'.'.$extension;

        $path = public_path().'/images/'.$fileName;

        file_put_contents($path, $decoded);

        $product = Product::create($request->except('image') + [

        'user_id' => $request->client_id,
        'image' => $fileName

        ]); 
    }

    public function show($id){ // show => return data of this id to be updated
        
        $product = Product::find($id);

        if($product){ // if number of product is greater than 0 or is not null
           return response()->json(Product::find($id)); // return product that belongs to the id 
        } else {
        	return response()->json(['error' => 'Resource not found!'], 404);
        }
    }

    public function update(Request $request, $id){
        
        $product = Product::find($id);

        $product->update($request->all()); // update this product id

        return response()->json($product); // return updated product in json format
    }

    public function destroy($id){  // <= product id from client side

    	try {
    		Product::destroy($id);

    		return response([], 204);

    	} catch (\Exception $e){

    		return response(['Error deleting product', 500]);
    	}
    }
}
