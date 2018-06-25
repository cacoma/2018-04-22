<?php

namespace App\Http\Controllers;

use App\TreasuryQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreasuryQuoteController extends Controller
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
     * @param  \App\TreasuryQuote  $treasuryQuote
     * @return \Illuminate\Http\Response
     */
    public function show(TreasuryQuote $treasuryQuote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TreasuryQuote  $treasuryQuote
     * @return \Illuminate\Http\Response
     */
    public function edit(TreasuryQuote $treasuryQuote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TreasuryQuote  $treasuryQuote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TreasuryQuote $treasuryQuote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TreasuryQuote  $treasuryQuote
     * @return \Illuminate\Http\Response
     */
    public function destroy(TreasuryQuote $treasuryQuote)
    {
        //
    }
    
    public function scrp()
    {
        //
      $user = Auth::user();
      if ($user->role_id === 1){
        return view('users.scrp');
      } else {
        return back();
      }
      
    }
}
