<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;

class CategoryController extends Controller
{
    public function index() {
        //
    }

    //Create a new category
    public function createCategory(Request $request) {
        $categoryName = $request->input("cat_name");
        $validateCategory = DB::table('categories')->select("*")
        ->where('name', '=', $categoryName)->get();

        if ($validateCategory->isEmpty()) {
            $newCategory = new Category;
            $newCategory->name  = $request->input("cat_name");
            $newCategory->save();

            return response ()->json (['status'=>'success','message'=>
            'Category created successfully','response'=>['data'=>$newCategory]], 201); 
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not create category', 'response'=> 'Already exists the category'], 409); 
        }    
    }

    //Show all categories
    public function showCategories() {
        $categories = DB::table('categories')->select("*")->get();      

        if ($categories->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Categories not found', 'response'=>'Categories table are empty'], 404); 
        }
        else{
            return response ()->json (['status'=>'success','message'=>
            'Categories found','response'=>['data'=>$categories]], 200);
        }       
    }

    //Edit category
    public function editCategory($id) {
        $editCategory = Category::findOrFail($id);
        return (['editCategory' => $editCategory]);
    }

    //Update category
    public function updateCategory(Request $request, $idCategory) {
        $categoryName = $request->input("cat_name");
        $nameSQL = DB::table("categories")->select("name")
        ->where('ID', '=', $idCategory)
        ->where('name','=',$categoryName)->get();

        if ($nameSQL->isEmpty()) {
            $previousCategory =  DB::table("categories")->select("*")
            ->where('ID', '=', $idCategory)->get();
            
            $categorySQL = "UPDATE categories SET
                            name = '$categoryName'
                            WHERE ID = $idCategory";
            $categoryUpdated = DB::select($categorySQL);
            $categoryData = DB::table("categories")->select("*")
            ->where('ID', '=', $idCategory)->get();

            return response ()->json (['status'=>'success','message'=>
            'Category updated successfully', 'response'=>
            ['data'=>['previous'=>$previousCategory, 'new'=>$categoryData]]], 200); 
        }
        else {
            return response ()->json (['status'=>'error','message'=>
            'Could not update the category', 'response'=>
            'The category already have same name'], 409);
        }
    }

    //Delete category
    public function deleteCategory($id) {
        $validateSQL = DB::table("products")->select("*")
        ->where('ID_category', '=', $id)->get();

        if ($validateSQL->isEmpty()) {
            $deleteSQL = "DELETE FROM categories WHERE ID = $id";
            $deleteCategory = DB::select($deleteSQL);
            
            return response ()->json (['status'=>'success','message'=>
            'Category deleted successfully'], 200);
        }
        else {
            return response ()->json (['status'=>'error','message'=>
            'There is at least one product with this category',
            'response'=>['data'=>$validateSQL]], 400);
        }
    }
}
