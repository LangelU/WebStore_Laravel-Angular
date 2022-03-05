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

    public function createCategory(Request $request) {
        $categoryName = $request->input("cat_name");
        $validateCategory = DB::table('categories')
                                ->select("*")->where('name', '=', $categoryName)->get();

        if ($validateCategory->isEmpty()) {
            $newCategory = new Category;

            $newCategory->name  = $request->input("cat_name");
            $newCategory->save();

            return response()->json(['successfull' => 'category_created'], 200);
        }
        return response()->json(['error' => 'could_not_create_Category'], 409);
    }

    public function showCategories(Category $category) {
        $categories = DB::table('categories')->select("*")->get();      

        if ($categories->isEmpty()) {
             return response()->json(['categories_not_found'], 404);
        }
        return response()->json($categories);
    }

    public function editCategory($id) {
        $editCategory = Category::findOrFail($id);
        return (['editCategory' => $editCategory]);
    }

    public function updateCategory(Request $request, $id) {
        $updateCategory = $request->input("cat_name"); 
        $categorySQL = "UPDATE categories SET
                        name = '$updateCategory'
                        WHERE ID = $id";
        $categoryUpdated = DB::select($categorySQL);
        return response()->json(['successfull' => 'category_updated'], 200);  
    }

    public function deleteCategory($id) {
        $deleteSQL = "DELETE from categories
                      WHERE ID = $id";
        $deleteCategory = DB::select($deleteSQL);
        return response()->json(['successfull' => 'category_deleted'], 200);
    }
}
