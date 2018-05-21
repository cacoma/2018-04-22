<nav class="navbar navbar-expand-md navbar-light navbar-cacoma">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Cacoma
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
               <!-- Left Side Of Navbar -->
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @auth
<!--                   <b-navbar-nav>
                      <b-nav-item href="/home">Home</b-nav-item>
                       <b-nav-item href="/invests">Invest</b-nav-item>
                            <b-nav-item-dropdown text="Investimentos" right>
                              <b-dropdown-item href="/invests">Indexar</b-dropdown-item>
                              <b-dropdown-item href="/invests/create">Adicionar</b-dropdown-item>
                            </b-nav-item-dropdown>
                  </b-navbar-nav> -->
                        <navbaradmin :auth="{{ auth()->user() }}" :alerts="{{ json_encode(Session::all()) }}"></navbaradmin>
                @endauth
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Registrar-se') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" dusk="navbarusername" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                   <a class="dropdown-item" href="{{ url('users/profile') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('profile').submit();">
                                        {{ __('Perfil') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" dusk="navbarlogout">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                  <form id="profile" action="{{ url('users/profile') }}" method="GET" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
</nav>