<?php

namespace App\Http\Controllers;

use App\IntradayQuote;
use Illuminate\Http\Request;

class IntradayQuoteController extends Controller
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
     * @param  \App\IntradayQuote  $intradayQuote
     * @return \Illuminate\Http\Response
     */
    public function show(IntradayQuote $intradayQuote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IntradayQuote  $intradayQuote
     * @return \Illuminate\Http\Response
     */
    public function edit(IntradayQuote $intradayQuote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IntradayQuote  $intradayQuote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IntradayQuote $intradayQuote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IntradayQuote  $intradayQuote
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntradayQuote $intradayQuote)
    {
        //
    }
}
