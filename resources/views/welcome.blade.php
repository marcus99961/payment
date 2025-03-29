
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payment DB | Inya Lake Hotel</title>

    <!-- Fonts -->



    <link href="{{asset('backend/css/submenu.css')}}" rel="stylesheet" type="text/css"/>

    <link rel="icon" type="image/png" sizes="32x32" href="/payment.png">
    <style>
        .nav-link.active{
            color: yellow;
            background-color:none; //or use the same colour.
        border-color:none;
            border-bottom: 3px solid blue;
        }
    </style>
    @vite('resources/sass/app.scss')
</head>
<body>
<div class="container" id="app">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Payment DB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <router-link class="nav-link" to="/">Home</router-link>
                    </li>
                    @can('admin')
                    <li class="nav-item">
                        <router-link class="nav-link" to="/departments">Department</router-link>
                    </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/users">User</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/setting">Settings</router-link>
                        </li>
                    @endif
                    <li class="nav-item">
                        <router-link class="nav-link" to="/payments">Payment</router-link>
                    </li>
                    
                    <li class="nav-item">
                        <router-link class="nav-link" to="/records">Paid History</router-link>
                    </li>
                   


                </ul>
                <ul class="navbar-nav float-end mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <router-link class="dropdown-item" to="/profile">Profile</router-link>
                        
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                               Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </div>

                    </li>
                    



                </ul>
            </div>
        </div>
    </nav>







    <router-view></router-view>







</div>
@vite('resources/js/app.js')

<!-- <script src="https://code.jquery.com/jquery-3.6.3.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->
<script>
    window.user = @json(auth()->user());





    // window.user_roles = @json(auth()->user()->roles);
    // window.user_permissions = @json(auth()->user()->permissions);

</script>
<script src="{{asset('backend/js/jquery.min.js')}}"></script>
<script src="{{asset('backend/js/submenu.js')}}"></script>
<!-- <script src="{{asset('backend/js/bootstrap.min.js')}}"></script> -->


</body>
</html>
