<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        //
    }

    //Create a new category
    public function createCategory(Request $request) {
        $categoryName = $request->input("cat_name");
        $validateCategory = DB::table('categories')
                                ->select("*")->where('name', '=', $categoryName)->get();

        if ($validateCategory->isEmpty()) {
            $newCategory = new Category;

            $newCategory->name  = $request->input("cat_name");
            $newCategory->save();

            return response ()->json (['status'=>'success','message'=>
            'Category created Successfully','response'=>['data'=>$newCategory]], 200); 
        }
        return response ()->json (['status'=>'error','message'=>
        'Could not create category', 'response'=> 'Already exists the category'], 409); 
    }

    //Show all categories
    public function showCategories(Category $category) {
        $categories = DB::table('categories')->select("*")->get();      

        if ($categories->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Categories not found'], 404); 
        }
        return response ()->json (['status'=>'success','message'=>
            'Categories found','response'=>['data'=>$categories]], 200);
    }

    public function editCategory($id) {
        $editCategory = Category::findOrFail($id);
        return (['editCategory' => $editCategory]);
    }

    public function updateCategory(Request $request, $id) {
        $categoryName = $request->input("cat_name");
        $nameSQL = DB::table("categories")->select("name")
        ->where('ID', '=', $id)
        ->where('name','=',$categoryName)->get();

        if ($nameSQL->isEmpty()) {
            $previousCategory =  DB::table("categories")->select("*")
            ->where('ID', '=', $id)->get();
    
            $categorySQL = "UPDATE categories SET
                            name = '$categoryName'
                            WHERE ID = $id";
            $categoryUpdated = DB::select($categorySQL);
            $categoryData = DB::table("categories")->select("*")
            ->where('ID', '=', $id)->get();

            return response ()->json (['status'=>'success','message'=>
            'Category updated Successfully', 'response'=>
            ['data'=>['previous'=>$previousCategory, 'new'=>$categoryData]]], 200); 
        }
        else {
            return response ()->json (['status'=>'error','message'=>
            'Could not update the category', 
            'response'=>'The category already have same name'], 409);
        }
    }

    public function deleteCategory($id) {
        $deleteSQL = "DELETE from categories
                      WHERE ID = $id";
        $deleteCategory = DB::select($deleteSQL);
        return response ()->json (['status'=>'success','message'=>
        'Category deleted Successfully'], 200); 
    }
}
