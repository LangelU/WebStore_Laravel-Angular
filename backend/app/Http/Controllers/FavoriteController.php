<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use DB;

class FavoriteController extends Controller
{
    public function index(){
        
    }

    //Add a favorite
    public function createFavorite($idUser, $idProduct){
        $validateExistence = DB::table("favorites")->select("*")
        ->where('ID_product', '=', $idProduct)
        ->where('ID_user', '=', $idUser)->get();

        if ($validateExistence->isEmpty()) {
            $newFavorite = new Favorite;
            $newFavorite->ID_product = $idProduct;
            $newFavorite->ID_user = $idUser;
            $newFavorite->save();

            return response ()->json(['status'=>'success', 'message'=>
            'Favorite added successfully', 'response'=> ['data'=>$newFavorite]],200);
        }
        else {
            return response ()->json(['status'=>'error', 'message'=>
            'Could not add favorite', 'response'=>'Already exists as a favorite'],409);
        }
    }

    //Show user favorites
    public function showUserFavorites($idUser){
        $favorites = DB::table("favorites")->select("*")
        ->where('ID_user', '=', $idUser)->get();

        if ($favorites->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Favorites not found', 'response'=>'The user do not have any favorites'],404);
        }
        else {
            return response ()->json(['status'=>'success', 'message'=>
            'Favorites found', 'response'=>['data'=>$favorites]],200);
        }
    }

    //Delete favorites
    public function deleteFavorite($idProduct, $idUser){
        $deleteSQL = "DELETE FROM favorites
                      WHERE ID_product  = $idProduct
                      AND ID_user = $idUser";
        $deleteFavorite = DB::select($deleteSQL);
        
        return response ()->json(['status'=>'success', 'message'=>
        'Favorite deleted successfully'],200);
    }
}
