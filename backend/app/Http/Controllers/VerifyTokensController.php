<?php

namespace App\Http\Controllers;

use App\Models\VerifyTokens;
use Illuminate\Http\Request;

class VerifyTokensController extends Controller
{
    
    /**
     *Verification tokens Code:
     * '1' User email verification
     * '2'  
     */
    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VerifyTokens  $verifyTokens
     * @return \Illuminate\Http\Response
     */
    public function show(VerifyTokens $verifyTokens)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VerifyTokens  $verifyTokens
     * @return \Illuminate\Http\Response
     */
    public function edit(VerifyTokens $verifyTokens)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VerifyTokens  $verifyTokens
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VerifyTokens $verifyTokens)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VerifyTokens  $verifyTokens
     * @return \Illuminate\Http\Response
     */
    public function destroy(VerifyTokens $verifyTokens)
    {
        //
    }
}
