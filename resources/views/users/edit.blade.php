@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <x:notify-messages />
                <div class="card-header bg-white border-primary">
                    Update Place
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update',$user) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control @error ('name') is-invalid @enderror" value="{{ $user->name }}">
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="">Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error ('password') is-invalid @enderror"
                                        placeholder="password here...">
                                    @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="">Email</label>
                                    <input type="text" name="email" id="email" class="form-control @error ('email') is-invalid @enderror"
                                    value="{{ $user->email }}">
                                    @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="">Confirm Password</label>
                                    <input id="password-confirm" type="password" name="password_confirmation"
                                        class="form-control"
                                        placeholder="confirm password here...">
                                </div>
                            </div>
                        </div>
                        <div class="container" id="mapid"></div>
                        <div class="form-group float-right mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </div>
                        <div class="form-group float-right mt-4 mr-3">
                            <a href="{{ route('places.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection