<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;

class Analytics extends Controller {
    public function showBestSellers(Request $request){
        $month = $request->input("month");
        $date = DateTime::createFromFormat('!m', $month);
        $monthName = $date->format('F'); // March

        $showBestSellersSQL = "SELECT p.reference, p.name, s.amount, s.created_at
                               FROM products p
                               JOIN sales s
                               WHERE
                               MONTH(s.created_at) = $month
                               AND s.ID_product = p.ID
                               ORDER BY amount DESC
                               LIMIT 10";
        $showBestSellers = DB::select($showBestSellersSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Best sellers in '.$monthName.'', 'response'=>['data'=>$showBestSellers]], 200);
    }

    public function showWorstSellers(Request $request){
        $month = $request->input("month");
        $date = DateTime::createFromFormat('!m', $month);
        $monthName = $date->format('F'); // March

        $showWorstSellersSQL = "SELECT p.reference, p.name, s.amount, s.created_at
                                FROM products p
                                JOIN sales s
                                WHERE
                                MONTH(s.created_at) = $month
                                AND s.ID_product = p.ID
                                ORDER BY amount ASC
                                LIMIT 10";
        $showWorstSellers = DB::select($showBestSellersSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Worst sellers in' .$monthName.'', 'response'=>['data'=>$showWorstSellers]], 200);
    }

    public function usersRegisteredPerMonth(Request $request){
        $month = $request->input("month");
        $usersRegisteredSQL = "SELECT *
                               FROM customers c
                               WHERE
                               MONTH(c.created_at) = $month";
        $usersRegistered = DB::select($usersRegisteredSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Users found', 'response'=>['data'=>$usersRegistered]], 200);
    }

    public function bestCustomersPerMonth(Request $request){
        $month = $request->input("month");

        $bestCustomersSQL = "SELECT c.f_name, c.f_lastname, c.id_number,
                             sh.salenumber, sh.total_value
                             FROM customers c
                             JOIN sale_histories sh
                             WHERE
                             MONTH(sh.created_at) = $month
                             AND sh.ID_user = c.ID
                             ORDER BY sh.total_value DESC
                             LIMIT 20";
        $bestCustomers = DB::select($bestCustomersSQL);

        return response ()->json(['status'=>'success', 'message'=>
        'Best customers found', 'response'=>['data'=>$bestCustomers]], 200);
    }
}
