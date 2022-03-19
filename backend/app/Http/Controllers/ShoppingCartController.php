<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use DB;
use App\Models\Sale;
use App\Models\SaleHistory;

class ShoppingCartController extends Controller
{
    public function index() {
        //
    }

    public function create() {
        //
    }

    //Add a product for a shopping cart
    public function addNewProduct(Request $request, $idProduct, $idUser, $productPrice) {
        //First, find existence for this prodcut in the shopping cart
        $validateExistence = DB::table("shopping_carts")->select("*")
        ->where('ID_product', '=', $idProduct)
        ->where('ID_user', '=', $idUser)->get();
        $ID_user = $idUser;
        $ID_product = $idProduct;
        $amount = $request->input("amount");

        //If not exist, add
        if ($validateExistence->isEmpty()) {
            $addAProduct = new ShoppingCart;

            $addAProduct->ID_user           = $idUser;
            $addAProduct->ID_product        = $idProduct;
            $addAProduct->amount            = $amount;
            $addAProduct->unit_price        = $productPrice;
            $addAProduct->sub_totalValue    = $productPrice * $amount;
            $addAProduct->save();
            
            $productAdded = DB::table("shopping_carts")->select("*")
            ->where('ID_product', '=', $idProduct)
            ->where('ID_user', '=', $idUser)->get();

            return response ()->json(['status'=>'success', 'message'=>
            'Product added successfully', 'response'=>['data'=>$productAdded]],200);
        }
        //If exist, update the amount
        else {
            $updateAmountSQL = "UPDATE shopping_carts SET
                                amount = $amount
                                WHERE ID_product = $idProduct
                                AND ID_user = $idUser";
            $updateAmount = DB::select($updateAmountSQL);
            $productUpdated = DB::table("shopping_carts")->select("*")
            ->where('ID_product', '=', $idProduct)
            ->where('ID_user', '=', $idUser)->get();

            return response ()->json(['status'=>'success', 'message'=>
            'Amount updated successfully', 
            'response'=>['data'=>$productUpdated]], 409);
        }
    }

    //Show shoppin cart user's
    public function showContent($idUser) {
        //First, find products added for the shopping cart
        $shopingCartContent = DB::table("shopping_carts")->select("*")
        ->where('ID_user','=',$idUser)->get();

        //If it doesn't exist, return an empty cart response
        if ($shopingCartContent->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Products not found', 'response'=>'Shopping cart are empty'], 404);
        } 
        //If exist, return all products added, and the total value of the buy
        else {
            $totalValueSQL = "SELECT SUM(sub_totalValue) 
                              FROM shopping_carts
                              WHERE ID_user = $idUser";
            $totalValue = DB::select($totalValueSQL);

            return response ()->json(['status'=>'success', 'message'=>
            'Products found', 'response'=>
            ['data'=>$shopingCartContent, 'totalValue'=>$totalValue]], 200);
        }
    }

    //Delete a product for the shopping cart
    public function deleteProduct($idUser, $idProduct) {
        $deleteSQL = "DELETE FROM shopping_carts 
                      WHERE ID_user = $idUser 
                      AND ID_product = $idProduct";
        $deleteProduct = DB::select($deleteSQL);
        $cartUpdated = $this->showContent($idUser);

        return response ()->json(['status'=>'success', 'message'=>
        'Shopping cart updated successfully', 'response'=>['data'=>$cartUpdated]], 200);
    }

    //Number generator to Request unique number
    public function buyNumberGenerator(){
        $sellNumber = rand(0000000001, 9999999999);
        return $sellNumber;
    }

    public function validateCart($idUser) {
        $sellNumber = $this->buyNumberGenerator();
        $validateExistence = DB::table("sales")->select("*")
        ->where('saleNumber', '=', $sellNumber)->get();
        $items = ShoppingCart::where('ID_user', '=', $idUser)->get();
        $purchasedItems = $this->showContent($idUser);

        //First, add the buy to the Sales table
        if ($validateExistence->isEmpty()) {
            //First, add buy to the sales table
            $addSalesSQL = "INSERT INTO sales 
                            (saleNumber,
                            ID_product,
                            ID_user,
                            amount,
                            unitary_value,
                            total_value,
                            created_at,
                            updated_at)
                            SELECT $sellNumber,
                            ID_product,
                            ID_user,
                            amount,
                            unit_price,
                            sub_totalValue,
                            NOW(),
                            NOW()
                            FROM shopping_carts sc
                            WHERE sc.ID_user = $idUser";
            $addSales = DB::select($addSalesSQL);

            //Second, add sale to the sale history
            $addSaleHistorySQL = "INSERT INTO sale_histories 
                                  (saleNumber,
                                  total_value,
                                  ID_user,
                                  created_at,
                                  updated_at) 
                                  VALUES 
                                  ($sellNumber,
                                  (SELECT SUM(sub_totalValue)
                                  FROM shopping_carts sc
                                  WHERE sc.ID_user = $idUser),
                                  $idUser,
                                  NOW(),
                                  NOW())";
            $totalValue = DB::select($addSaleHistorySQL);
            
            //Third, delete all items for the shopping cart
            $deleteShoppingCartSQL = "DELETE FROM shopping_carts WHERE ID_user = $idUser";
            $deleteShoppingCart = DB::select($deleteShoppingCartSQL);
            
            return response ()->json(['status'=>'success', 'message'=>
            'Buy validated successfully', 'response'=>
            ['purchasedItems'=>$purchasedItems]], 200);
            
        } else {
            return response ()->json(['status'=>'error', 'message'=>
            'Internal error', 'response'=>'Try again'], 500);
        }
    }

    public function test($id){
        $result = ShoppingCart::where('ID_user', '=', $id)->get();
        return $result;
        
    }

    public function deleteCart($idUser){
        $deleteSQL = "DELETE from shopping_carts WHERE ID_user = $idUser";
        $deleteCart = DB::select($deleteSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Cart deleted successfully'], 200);
    }
}
