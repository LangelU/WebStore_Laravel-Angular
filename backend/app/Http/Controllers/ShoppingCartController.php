<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\Customer;
use App\Models\VerifyTokens;
use App\Models\SaleHistory;
use App\Models\Sale;
use App\Models\User;
use App\Mail\EmailVerification;
use DB;
use Mail;

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
            'response'=>['data'=>$productUpdated]], 201);
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
        $saleNumber = rand(0000000001, 9999999999);
        return $saleNumber;
    }

    public function validateCart($idUser, $emailUser) {
        $saleNumber = $this->buyNumberGenerator();
        $validateExistence = DB::table("sales")->select("*")
        ->where('saleNumber', '=', $saleNumber)->get();
        $items = ShoppingCart::where('ID_user', '=', $idUser)->get();
        $purchasedItems = $this->showContent($idUser);
        $email = DB::table("users")->select("email")->where('ID', '=', $idUser)->get();

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
                            SELECT $saleNumber,
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
                                  ($saleNumber,
                                  (SELECT SUM(sub_totalValue)
                                  FROM shopping_carts sc
                                  WHERE sc.ID_user = $idUser),
                                  $idUser,
                                  NOW(),
                                  NOW())";
            $totalValue = DB::select($addSaleHistorySQL);
            
            //Third, delete all items for the shopping cart
            //$deleteShoppingCartSQL = "DELETE FROM shopping_carts WHERE ID_user = $idUser";
            //$deleteShoppingCart = DB::select($deleteShoppingCartSQL);

            //Fourth, capture data to the e-mail notification
            $customerName = DB::table('customers')
            ->join('users', 'users.email', '=', 'customers.email')
            ->join('sales', 'sales.ID_user', '=', 'users.ID')
            ->select('customers.f_name','customers.f_lastname')
            ->limit(1)
            ->get();

            $saleDetail = DB::table('products')
            ->join('sales', 'sales.ID_product', '=', 'products.ID')
            ->where('sales.saleNumber', '=', $saleNumber)
            ->where('sales.ID_user', '=', $idUser)
            ->select("*")
            ->get();

            $email = DB::table("users")
            ->where('ID', '=', $idUser)
            ->select("email")
            ->get();


            $customerName = Customer::where('customers.ID', '=', $idUser )
            ->get();
            //Second: capture the total value of the sale
            $saleHistory = SaleHistory::where('ID_user', '=', $idUser)->get();
                                      
            Mail::send('emailNotifications.sale_notification', ['content'=>$customerName,
                                             'totalValue'=>$saleHistory,
                                             'saleDate'=>$saleHistory,
                                             'saleNumber'=>$saleNumber,
                                             'saleDetail'=>$saleDetail], 
                                             function($message)
                use($customerName, $saleHistory, $saleNumber, $saleDetail, $emailUser) {
                $message->to($emailUser)->subject('Compra validada');
                $message->from('WebStore@gmail.com','Equipo Webstore');
            });

            return response ()->json(['status'=>'success', 'message'=>
            'Buy validated successfully', 
            'response'=>'Sale validated and email notification sent'], 200);
            
        } else {
            return response ()->json(['status'=>'error', 'message'=>
            'Internal error', 'response'=>'Try again'], 500);
        }
    }

    public function deleteCart($idUser){
        $deleteSQL = "DELETE from shopping_carts WHERE ID_user = $idUser";
        $deleteCart = DB::select($deleteSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Cart deleted successfully'], 200);
    }

    public function test($idUser){
        $saleNumber = 3167287159;
        //Customer name
        $customerName = DB::table('customers')
        ->join('users', 'users.email', '=', 'customers.email')
        ->join('sales', 'sales.ID_user', '=', 'users.ID')
        ->select('customers.f_name', 'customers.f_lastname')
        ->limit(1)
        ->get();


        //Sale detail
        $saleDetail = DB::table('products')
        ->join('sales', 'sales.ID_product', '=', 'products.ID')
        ->where('sales.saleNumber', '=', $saleNumber)
        ->where('sales.ID_user', '=', $idUser)
        ->select('products.reference',
                 'products.name',
                 'sales.unitary_value',
                 'sales.amount',
                 'sales.total_value')
        ->get();

        $totalValue = DB::table("sale_histories")->select("total_value")
        ->where('ID_user', '=', $idUser)->get();

        //Array to capture all data for send in e-mail body
        $data = array('name'=>$customerName,
                      'saleDetails'=>$saleDetail,
                      'saleNumber'=>$saleNumber);
        return response ()->json(['status'=>'success', 'message'=>
        'Success',
        'response'=>
        ['name'=>$data['name'], 
         'saleDetails'=>$data['saleDetail'],
         'total'=>$totalValue]], 200);
    }
}

