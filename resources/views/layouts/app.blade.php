<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cacoma') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="https://fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<!--  acesso para o javascript de variaveis do sistema  -->
      <script>
       window.aplhavantage_apikey = "{{ env('MIX_APLHAVANTAGE_APIKEY') }}"
      </script>
</head>
<body>
    <div id="app">
         <div class="sk-cube-grid" id="sk-cube-grid" dusk="cubegrid">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
        <div class="sk-cube sk-cube3"></div>
        <div class="sk-cube sk-cube4"></div>
        <div class="sk-cube sk-cube5"></div>
        <div class="sk-cube sk-cube6"></div>
        <div class="sk-cube sk-cube7"></div>
        <div class="sk-cube sk-cube8"></div>
        <div class="sk-cube sk-cube9"></div>
      </div>
      <loading dusk="loading" id="loading"></loading>
      <progressbar dusk="progressbar" id="progressbar"></progressbar>
      <deleteconfirmation dusk="deleteconfirmation" id="deleteconfirmation"></deleteconfirmation>
      <div id="blur" dusk="blur" class="blur">
<!--   componente que mostra mensagens de erro     -->
      <flash message="{{ session('flash') }}" dusk="flash" id="flash" class="flash"></flash> 
<!--       div para fazer "blur" na pagina quando necessario (carregamento de alguma página,  etc) -->

       @include ('layouts.navbar')
        <div class="main">
            @yield('content')
        </div>
    </div>
    </div>
    </div>
</body>
</html>
