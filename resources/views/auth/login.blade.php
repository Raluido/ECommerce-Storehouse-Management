@extends('layouts.master')

@section('content')

<div class="d-flex align-items-center flex-column mt-5">

    <h5 class="loginTitle">Logueate</h5>

    <div id="messages">
        @include('layouts.partials.messages')
    </div>

    <form method="post" action="{{ route('login.perform') }}" class="w-25 mt-2 shadow-lg p-4">
        @csrf

        <div class="mb-4">
            <input type="text" name="name" placeholder="Usuario" require="required">
        </div>
        <div class="">
            <input type="text" name="password" placeholder="Contraseña" require="required">
        </div>
        <div class="mt-5 d-flex justify-content-around btn-sm">
            <input type="submit" value="Acceder" class="btn btn-success">
            <button class="btn btn-primary btn-sm"><a href="{{ route('main') }}" class="text-white">Menú principal</a></button>
        </div>

    </form>

</div>

@endsection