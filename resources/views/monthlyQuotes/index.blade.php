@extends('layouts.app')

@section('content')

      <index :items="{{$monthlyQuotes}}"></index>

@endsection