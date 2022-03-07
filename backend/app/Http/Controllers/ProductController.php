<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Picture;


class ProductController extends Controller
{
    public function index()
    {
        //
    }

    public function create(){
        //
    }

    //Create a new product
    public function createProduct(Request $request, $id){
        $produtReference = $request->input("reference");
        $validateExistence = DB::table("products")->select("*")
            ->where('reference', '=', $produtReference)->get();
        if($validateExistence->isEmpty()){
            // First: saving product data
            $newProduct = new Product;

            $newProduct->reference = $request->input("reference");
            $newProduct->name = $request->input("prod_name");
            $newProduct->type = $request->input("prod_type");
            $newProduct->description = $request->input("prod_description");
            $newProduct->details = $request->input("prod_details");

            $newProduct->price = $request->input("prod_price");
            $newProduct->ID_category = $request->input("prod_category");
            $newProduct->stock = $request->input("prod_stock");
            $newProduct->brand = $request->input("prod_brand");
            $newProduct->model = $request->input("prod_model");
            $newProduct->save();

            return response ()->json (['status'=>'success','message'=>
            'Product data saved Successfully','response'=>['data'=>$newProduct]], 200);
        }
        return response ()->json (['status'=>'error','message'=>
        'Could not save the product data','response'=>'Already exist the product'], 409);
    }

    public function show(Product $product){
        $products = DB::table("products")->select("*")->get();

        if ($products->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Products not found'], 404);
        }
        return response ()->json (['status'=>'success','message'=>
        'Products found','response'=>['data'=>$products]], 200);
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    //Delete the product data and their pictures
    public function deleteProduct($id){
        //First, delete the product data
        $deleteProductSQL = "DELETE from products
                             WHERE ID = $id";
        $deleteProduct = DB::select($deleteproductSQL);

        //Second, delete the product pictures
        $deletePicturesSQL = "DELETE from picture
                              WHERE ID_product = $id"; 
        $deleteProductPicture = DB::select($deletePictureSQL);

        return response ()->json (['status'=>'success','message'=>
        'Category deleted Successfully'], 200);
    }
     
    //Upload pictures for the new product
    public function uploadProductPicture(Request $request) {
        $file = $request->file('file');
        $path = public_path().'/uploads';
        $fileName =  'pic'.time().$file->getClientOriginalName();
        $file->move($path, $fileName);

        $newPicture = new Picture();
        $newPicture->ID_product = 1;
        $newPicture->picture = $fileName;
        $newPicture->save();
        
        return response ()->json (['status'=>'success','message'=>
        'Pictures uploaded Successfully'], 200);
    } 
}
