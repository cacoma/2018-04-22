@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <homecarousel :results="{{$results}}" :portperf="{{$portPerf}}" :portperfp="{{json_encode($portPerfP)}}" :pie="{{json_encode($pie)}}"></homecarousel>
<!--             <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Logado!
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
