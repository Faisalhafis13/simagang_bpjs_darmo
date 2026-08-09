@extends('layouts.public')

@section('title', 'Login Admin')

@section('public-content')

<div class="col-lg-5">

<div class="login-card">

    <div class="text-center mb-4">

        <!--
        <img
            src="{{ asset('images/bpjslogo.png') }}"
            width="90">
        -->

        <h3 class="mt-3 fw-bold">
            Login Sesuai Akun
        </h3>

        <p class="text-muted">
            SIMAGANG BPJS Ketenagakerjaan
        </p>

    </div>


    <form
        action="{{ route('login.post') }}"
        method="POST"
        id="formLogin"
    >

        @csrf


        <div class="mb-3">

            <label
                for="email"
                class="form-label"
            >
                Email
            </label>

            <input
                type="email"
                name="email"
                id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                autocomplete="email"
                required
            >

            @error('email')

                <div class="invalid-feedback">
                    {{ $message }}
                </div>

            @enderror

        </div>


        <div class="mb-4">

            <label
                for="password"
                class="form-label"
            >
                Password
            </label>

            <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                autocomplete="current-password"
                required
            >

            @error('password')

                <div class="invalid-feedback">
                    {{ $message }}
                </div>

            @enderror

        </div>


        <button
            type="submit"
            class="btn btn-primary w-100"
            id="btnLogin"
        >

            <i class="bi bi-box-arrow-in-right me-1"></i>

            Login

        </button>

    </form>

</div>

</div>

@endsection

@push('js')

<script>

$(function () {


    /*
    |--------------------------------------------------------------------------
    | LOGIN GAGAL
    |--------------------------------------------------------------------------
    */

    @if(session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Login Gagal',

            text: @json(session('error')),

            confirmButtonText: 'Coba Lagi'

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | VALIDATION ERROR
    |--------------------------------------------------------------------------
    */

    @if($errors->any() && !session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Data Tidak Valid',

            text: @json($errors->first()),

            confirmButtonText: 'OK'

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | LOGOUT BERHASIL
    |--------------------------------------------------------------------------
    */

    @if(session('logout_success'))

        Swal.fire({

            icon: 'success',

            title: 'Logout Berhasil',

            text: @json(session('logout_success')),

            timer: 1800,

            showConfirmButton: false

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | LOGIN SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#formLogin').on('submit', function () {

        const button =
            $('#btnLogin');


        button
            .prop('disabled', true)
            .html(`

                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                    aria-hidden="true">
                </span>

                Memproses...

            `);

    });

});

</script>

@endpush
