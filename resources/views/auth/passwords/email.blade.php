@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
		<div id="img_logo_block">	
			<img src="{{ asset('img/Transflow-logo.png') }}" alt="Transflow" class="logo">		
		</div>
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        
                        <p class="text-muted">Forgot Password</p>
                        <div>
                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <input type="email" name="email" class="form-control" required="autofocus" placeholder="Email">
                                @if($errors->has('email'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </em>
                                @endif
                            </div>
                        </div>
                        
						
						<div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">
                                     Reset Password
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-link px-0" href="{{ route('login') }}">
                                    Login
                                </a>

                            </div>
                        </div>
						
						
						
						
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection