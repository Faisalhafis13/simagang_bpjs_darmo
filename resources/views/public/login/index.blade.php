@extends('layouts.public')

@section('title','Login Admin')

@section('public-content')

<section class="login-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5">

                <div class="login-card">

                    <div class="text-center mb-4">

                        <img
                            src="{{ asset('images/bpjslogo.png') }}"
                            width="90">

                        <h3 class="mt-3 fw-bold">
                            Login Administrator
                        </h3>

                        <p class="text-muted">
                            SIMAGANG BPJS Kesehatan
                        </p>

                    </div>

                    <form
                        action="{{ route('login.post') }}"
                        method="POST">

                        @csrf

                        <div class="mb-3">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control">

                        </div>

                        <div class="mb-4">

                            <label>Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control">

                        </div>

                        <button
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection