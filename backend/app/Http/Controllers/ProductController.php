<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Picture;


class ProductController extends Controller
{
    public function index() {
        //
    }

    public function create(Request $request){
        $n = $request->input("n");
        print($n);
    }

    //Create a new product
    public function createProduct(Request $request){
        $produtReference = $request->input("reference");
        $validateExistence = DB::table("products")->select("*")
        ->where('reference', '=', $produtReference)->get();

        if($validateExistence->isEmpty()){
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
            'Product data saved successfully','response'=>['data'=>$newProduct]], 201);
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not save the product data','response'=>'Already exist the product'], 409);
        }  
    }

    //Show all products
    public function showAllProducts(Product $product){
        $products = DB::table("products")->select("*")->get();

        if ($products->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Products not found', 'response'=>'The products table are empty'], 404);
        }
        else{
            return response ()->json (['status'=>'success','message'=>
            'Products found','response'=>['data'=>$products]], 200);
        }     
    }

    public function edit(Product $product) {
        //
    }

    //Update product data
    public function updateProductData(Request $request, $idProduct){
        $reference      = $request->input("reference");
        $name           = $request->input("prod_name");
        $type           = $request->input("prod_type");
        $description    = $request->input("prod_description");
        $details        = $request->input("prod_details");
        $price          = $request->input("prod_price");
        $ID_category    = $request->input("prod_category");
        $stock          = $request->input("prod_stock");
        $brand          = $request->input("prod_brand");
        $model          = $request->input("prod_model");

        $updateProductSQL = "UPDATE products SET
                             reference = '$reference',
                             name = '$name',
                             type = '$type',
                             description = '$description',
                             details = '$details',
                             price = $price,
                             ID_category = $ID_category,
                             stock = $stock,
                             brand = '$brand',
                             model = '$model'
                             WHERE ID = $idProduct";
        $productUpdated = DB::select($updateProductSQL);
        $productData = DB::table("products")->select("*")
        ->where('ID', '=', $idProduct);

        return response ()->json(['status'=>'success', 'message'=>
        'Product data updated successfully', 'response'=>['data'=>$productData]],200);
    }

    //Delete the product data and their pictures
    public function deleteProduct($idProduct){
        //First, delete the product data
        $deleteProductSQL = "DELETE FROM products WHERE ID = $idProduct";
        $deleteProduct = DB::select($deleteproductSQL);

        //Second, delete the product pictures
        $deletePicturesSQL = "DELETE FROM picture WHERE ID_product = $idProduct"; 
        $deleteProductPicture = DB::select($deletePictureSQL);

        return response ()->json (['status'=>'success','message'=>
        'Product deleted Successfully'], 200);
    }
    
    //Show all details of a product
    public function productDetails($idProduct){
        //First, find product data
        $productData = DB::table("products")->select("*")
        ->where('ID', '=', $idProduct)->get();

        //Second, find product category
        $productCategorySQL = "SELECT c.name
                               FROM categories c
                               JOIN products p
                               WHERE p.ID_category = c.ID
                               AND p.ID = $idProduct";
        $productCategory = DB::select($productCategorySQL);

        //Third, find tags for the product
        $productTagsSQL = "SELECT name
                           FROM tags t
                           JOIN product_tags pt
                           WHERE pt.ID = t.ID
                           AND pt.ID_product = $idProduct";
        $productTags = DB::select($productTagsSQL);

        //Product pictures

        return response()->json(['status'=>'success', 'message'=>
        'Data product found', 'response'=>[
        'productData'=>$productData, 'productCategory'=>$productCategory,
        'productTags'=>$productTags]], 200);

    }
}
