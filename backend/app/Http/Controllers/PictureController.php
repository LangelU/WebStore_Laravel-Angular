<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;

class PictureController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    //Upload pictures for the new product
    public function uploadProductPicture(Request $request) {
        $file = $request->file('file');
        $path = public_path().'/uploads';
        $fileName =  'pic'.time().$file->getClientOriginalName();
        $file->move($path, $fileName);

        $newPicture = new Picture();
        $newPicture->productReference = $request->input("reference");
        $newPicture->picture = $fileName;
        $newPicture->save();
        
        return response ()->json (['status'=>'success','message'=>
        'Pictures uploaded Successfully'], 201);
    } 

    public function show(Picture $picture)
    {
        //
    }

    public function edit(Picture $picture)
    {
        //
    }

    public function update(Request $request, Picture $picture)
    {
        //
    }

    public function destroy(Picture $picture)
    {
        //
    }
}
