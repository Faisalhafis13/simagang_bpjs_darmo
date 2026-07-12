@extends('layouts.back-office')

@section('title','Dashboard')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h3 class="fw-bold">

            Selamat Datang 👋

        </h3>

        <p class="mb-1">

            {{ auth()->user()->name }}

        </p>

        <small class="text-muted">

            Role :
            {{ auth()->user()->role->name }}

        </small>

    </div>

</div>

@endsection