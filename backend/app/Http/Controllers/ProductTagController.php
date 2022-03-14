<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use Illuminate\Http\Request;
use DB;

class ProductTagController extends Controller
{
    public function index() {
        //
    }

    //Add new tag for the product
    public function createProductTag($idProduct, $idTag){
        $validateExistence = DB::table("product_tags")->select("*")
        ->where('ID_tag','=',$idTag)
        ->where('ID_product', '=', $idProduct)
        ->get();

        if ($validateExistence->isEmpty()) {
            $newTag = new Tag;
            $newTag->ID_tag = $idTag;
            $newTag->ID_product = $idProduct;
            $newTag->save();

            return response ()->json(['status'=>'success', 'message'=>
            'Tag added succesfully', 'response'=>['data'=>$newTag]],200);
        }
        else {
            return response ()->json(['status'=>'error', 'message'=>
            'Could not add the tags', 
            'response'=>'The product already have same tags'],409);
        }
    }

    //Show product tag
    public function showProductTags($idProduct){
        $productTags = DB::table("product_tags")->select("*")
        ->where('ID_product', '=', $idProduct)->get();

        if ($productTags->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Tags not found', 'response'=>'Product not have any tags'],404);
        }
        else {
            return response ()->json(['status'=>'success', 'message'=>
            'Tags found', 'response'=>['data'=>$productTags]],200);
        }
    }

    public function edit(ProductTag $productTag) {
        //
    }

    //Delete product tag
    public function deleteProductTag($idProduct, $idTag){
        $deleteTagsSQL = "DELETE FROM product_tags
                          WHERE ID_product = $idProduct
                          AND ID_tag = $idTag";
        $deleteProductTags = DB::select($deleteTagsSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Tags deleted successfully'],200);
    }
}
