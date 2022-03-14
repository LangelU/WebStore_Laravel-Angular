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

    //Create a new Tag
    public function createTag(Request $request){
        $tagName = $request->input("tag_name");
        $tagSQL = DB::table("tags")->select("*")
            ->where('name', '=', $tagName)->get();
    
        if($tagSQL->isEmpty()){
            $newTag = new Tag;
            $newTag->name = $request->input("tag_name");
            $newTag->save();
            
            return response ()->json (['status'=>'success','message'=>
            'Tag created successfully','response'=>['data'=> $newTag]], 200);  
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not create the tag','response'=>'Already exists the tag'], 409);  
        }
        
    }

    //Show all tags
    public function showTags(Tag $tag){
        $tags = DB::table("tags")->select("*")->get();
        if ($tags->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
        'Tags not found'], 404);
       }
       else{
            return response ()->json (['status'=>'success','message'=>
            'Tags found','response'=>['data'=>$tags]], 200);
       }
       
    }

    public function edit(Tag $tag)
    {
        //
    }

    public function updateTag(Request $request, $id){
        $tagName = $request->input("tag_name");
        $nameSQL = DB::table("tags")->select("name")
        ->where('ID', '=', $id)
        ->where('name','=',$tagName)->get();

        if ($nameSQL->isEmpty()) {
            $previousTag =  DB::table("tags")->select("*")
            ->where('ID', '=', $id)->get();
            $tagSQL = "UPDATE tags SET name = '$tagName' WHERE ID = $id";
            $tagUpdated = DB::select($tagSQL);
            $tagData = DB::table("tags")->select("*")
            ->where('ID', '=', $id)->get();

            return response ()->json (['status'=>'success','message'=>
            'Tag updated successfully', 'response'=>
            ['data'=>['previous'=>$previousTag, 'new'=>$tagData]]], 200);
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not update the tag', 
            'response'=>'The tag already have same name'], 409);
        }

        
    }

    public function deleteTag($id){
        $deleteSQL = "DELETE from tags
                      WHERE ID = $id";
        $deleteTag = DB::select($deleteSQL);
        return response ()->json (['status'=>'success','message'=>
            'Tag deleted Successfully'], 200);
    }
}
