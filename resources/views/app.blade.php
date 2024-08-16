<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment App | Inya Lake Hotel</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/payment.png">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->
    <link href="{{asset('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    @vite('resources/sass/app.scss')
    <style>
        body {
            background-color: #e3f0ef;
        }
        h4 {
            text-shadow: 1px 2px 5px #136ff3;
        }
        .card {
            box-shadow: 3px 3px 5px #136ff3;
        }
    </style>

</head>
<body>

<div class="container h-100">
    <div class="text-center mb-5 mt-3">
        <h4>Payment App | Inya Lake Hotel</h4>
    </div>
    @yield('content')
</div>

</body>
</html>
