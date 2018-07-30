<?php

namespace App\Http\Controllers;

use App\investTreasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestTreasuryController extends Controller
{
      public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  \App\investTreasury  $investTreasury
     * @return \Illuminate\Http\Response
     */
    public function show(investTreasury $investTreasury)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\investTreasury  $investTreasury
     * @return \Illuminate\Http\Response
     */
    public function edit(investTreasury $investTreasury)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\investTreasury  $investTreasury
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, investTreasury $investTreasury)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\investTreasury  $investTreasury
     * @return \Illuminate\Http\Response
     */
    public function destroy(investTreasury $investTreasury)
    {
        //
    }
}
