x@extends('layouts.app')

@section('content')
    <div>
<!--                  @if (\Session::has('success'))
                      <b-alert show variant="success" dismissible>
                        <p>{{ \Session::get('success') }}</p>
                      </b-alert>
                     @endif
                     @if (\Session::has('errors'))
                       <b-alert show variant="danger" dismissible>
                         <p>{{ \Session::get('errors') }}</p>
                       </b-alert>
                      @endif
                  @if (\Session::has('status'))
                       <b-alert show variant="alert" dismissible>
                         <p>{{ \Session::get('status') }}</p>
                       </b-alert>
                      @endif -->
      <createinvests></createinvests>
       </div>
@endsection