@extends('layouts.app')

@section('content')

      <index :items="{{$dailyQuotes}}"></index>

@endsection