@extends('app')

@section('content')

    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center bg-dark">
                    <h5 class="text-light">Update Password</h5>
                </div>
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        We've already reset with your new password. Try Login again!
                    </div>
                @endif
                <div class="card-body">
                    <form action="{{route('password.update')}}" method="POST">
                        @csrf

                        <input type="hidden" name="token" value="{{$request->route('token')}} ">
                        <div class="form-group my-4">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$request->email}}" placeholder="Email Address" required>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group my-4">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" placeholder="New Password" autocomplete="new-password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group my-4">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group my-4">
                            <button type="submit" class="btn btn-success float-start">
                                Update
                            </button>

                            <a href="{{route('login')}}" class="btn btn-dark float-end">Login</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection