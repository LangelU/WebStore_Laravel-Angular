<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use DB;

class TagController extends Controller
{
    public function index()
    {
        //
    }

    public function createTag(Request $request){
        $tagName = $request->input("tag_name");
        $tagSQL = DB::table("tags")->select("*")
            ->where('name', '=', $tagName)->get();
    
        if($tagSQL->isEmpty()){
            $newTag = new Tag;

            $newTag->name = $request->input("tag_name");
            $newTag->save();
            
            return response()->json(['successfull' => 'tag_created'], 200);  
        }
        return response()->json(['error' => 'could_not_create_tag'], 409);
    }

    public function showTags(Tag $tag){
        $tags = DB::table("tags")->select("*")->get();
        if ($tags->isEmpty()) {
            return response()->json(['tags_not_found'], 404);
       }
       return response()->json($tags);
    }

    public function edit(Tag $tag)
    {
        //
    }

    public function updateTag(Request $request, $id){
        $updateTag = $request->input("tag_name"); 
        $tagSQL = "UPDATE tags SET
                   name = '$updateTag'
                   WHERE ID = $id";
        $categoryUpdated = DB::select($tagSQL);
        return response()->json(['successfull' => 'tag_updated'], 200);
    }

    public function deleteTag($id){
        $deleteSQL = "DELETE from tags
                      WHERE ID = $id";
        $deleteTag = DB::select($deleteSQL);
        return response()->json(['successfull' => 'tag_deleted'], 200);
    }
}
