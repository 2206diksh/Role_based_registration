@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="col-md-8 mx-auto text-center">
            <div class="alert alert-success">
                {{ session('message') ?? 'You have logged in successfully!' }}
            </div>

            <h3>Welcome to the Dashboard</h3>
        </div>
    </div>
@endsection
