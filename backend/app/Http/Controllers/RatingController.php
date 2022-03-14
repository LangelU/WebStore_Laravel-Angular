<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use DB;

class RatingController extends Controller
{
    public function index() {
        //
    }

    public function create() {
        //
    }

    //Create a new rating (client)
    public function createRating(Request $request, $idUser, $idProduct) {
        $newRating = new Rating;
        $newRating->ID_product = $idProduct;
        $newRating->ID_user = $idUser;
        $newRating->score = $request->input("score");
        $newRating->review = $request->input("review");
        $newRating->save();

        $userSQL = "SELECT c.f_name, c.f_lastname
                    FROM customers c
                    JOIN users u
                    ON c.ID = u.ID
                    WHERE u.ID = $idUser";
        $user = DB::select($userSQL);
        $product = DB::table("products")->select("name")
        ->where('ID', '=', $idProduct)->get();

        return response ()->json (['status'=>'success','message'=>
        'Comment created Successfully', 'response'=>
        ['data'=>['comment'=>$newRating, 'user'=>$user, 'product'=>$product]]], 200);
    }

    //Show ratings for a product
    public function showRatings($id´Product){
        $ratings = DB::table("ratings")->select("*")
        ->where('ID_product','=',$idProduct)->get();

        if ($ratings->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Ratings not found', 'response'=>'The product do not have any rating'], 404);
        }
        else{
            return response ()->json (['status'=>'success','message'=>
            'Ratings found', 'response'=>['data'=>['ratings'=>$ratings]]], 200);
        } 
    }

    public function edit(Rating $rating){
        //
    }

    //Update a rating
    public function updateRating(Request $request, $idUser, $idRating){
        $score = $request->input("score");
        $review = $request->input("review");
        $updateRatingSQL = "UPDATE FROM ratings SET
                            score = $score,
                            review = '$review'
                            WHERE 'ID' = $idRating
                            AND 'ID_user' = $idUser";
        $updateRating = DB::select($updateRatingSQL);
        $ratingUpdated = DB::table("ratings")->select("*")
        ->where('ID', '=', $idRating)->get();
        
        return response ()->json(['status'=>'success', 'message'=>
        'Rating updated successfully', 'response'=>['data'=>$ratingUpdated]],200);
    }

    public function deleteRating($idRating) {
        $deleteRatingSQL = "DELETE FROM ratings WHERE ID = $idRating";
        $deleteRating = DB::select($deleteSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Rating deleted successfully'],200);
    }

    //Response a comment (Staf)
    public function responseAComment(Request $request, $idRating){
        $answer = $request->input("com_answer");

        $responseSQL = "UPDATE FROM ratings SET answer = '$answer' WHERE ID = $idRating";
        $responseUpdated = DB::select($responseSQL);
        $response = DB::table("ratings")->select("*")->where('ID', '=', $idRating);
        
        return response ()->json(['status'=>'success', 'message'=>
        'Response saved successfully', 'response'=>['data'=>$response]], 200);
    }
}
