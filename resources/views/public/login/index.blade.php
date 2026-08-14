@extends('layouts.public')

@section('title', 'Login Admin')

@section('public-content')

<section class="login-page">

    <div class="container">

        <div class="row justify-content-center align-items-center">

            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

                <div class="login-card">

                    {{-- ========================================================= --}}
                    {{-- LOGIN ICON --}}
                    {{-- ========================================================= --}}

                    <div class="text-center mb-4">

                        <div class="login-icon">
                            <i class="bi bi-person-lock"></i>
                        </div>

                        <h3 class="mt-4 fw-bold">
                            Login Yuk!
                        </h3>

                        <p class="text-muted mb-0">
                            Masuk untuk melanjutkan
                        </p>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- LOGIN FORM --}}
                    {{-- ========================================================= --}}

                    <form
                        action="{{ route('login.post') }}"
                        method="POST"
                        id="formLogin"
                    >

                        @csrf


                        {{-- ===================================================== --}}
                        {{-- EMAIL --}}
                        {{-- ===================================================== --}}

                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label fw-semibold"
                            >
                                Email
                            </label>


                            <div class="input-group input-group-lg">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>


                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="Masukkan email"
                                    required
                                >

                            </div>


                            @error('email')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ===================================================== --}}
                        {{-- PASSWORD --}}
                        {{-- ===================================================== --}}

                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label fw-semibold"
                            >
                                Password
                            </label>


                            <div class="input-group input-group-lg">

                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>


                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    required
                                >

                            </div>


                            @error('password')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ===================================================== --}}
                        {{-- SUBMIT --}}
                        {{-- ===================================================== --}}

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-3"
                            id="btnLogin"
                        >

                            <i class="bi bi-box-arrow-in-right me-2"></i>

                            Login

                        </button>

                    </form>


                    {{-- ========================================================= --}}
                    {{-- FOOTER INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="text-center mt-4">

                        <small class="text-muted">
                            SIMAGANG BPJS Ketenagakerjaan
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection


@push('js')

<script>

$(function () {


    /*
    |--------------------------------------------------------------------------
    | MAGANG SUDAH SELESAI / AKUN SUDAH DIARSIPKAN
    |--------------------------------------------------------------------------
    */

    @if(session('magang_selesai'))

        Swal.fire({

            icon: 'info',

            title: 'Magang Anda Telah Selesai',

            html: `
                <div class="text-center">

                    <div class="mb-3">

                        <i
                            class="bi bi-mortarboard-fill text-primary"
                            style="font-size: 3rem;"
                        ></i>

                    </div>


                    <p class="mb-3">
                        {{ session('magang_selesai_message') }}
                    </p>


                    <div
                        class="alert alert-info text-start mb-3"
                        style="border-radius: 12px;"
                    >

                        <div class="fw-semibold mb-1">

                            <i class="bi bi-award me-1"></i>

                            Membutuhkan sertifikat?

                        </div>

                        <div class="small">

                            Jika Anda memerlukan sertifikat magang
                            atau membutuhkan informasi lebih lanjut,
                            silakan menghubungi admin/pengelola
                            SIMAGANG melalui nomor kontak yang telah
                            ditentukan.

                        </div>

                    </div>


                    <div class="small text-muted">

                        <i class="bi bi-info-circle me-1"></i>

                        Akun tidak dapat digunakan kembali
                        setelah pengajuan magang diarsipkan.

                    </div>

                </div>
            `,

            confirmButtonText: 'Saya Mengerti',

            confirmButtonColor: '#0d6efd',

            allowOutsideClick: false,

            allowEscapeKey: false

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | LOGIN GAGAL
    |--------------------------------------------------------------------------
    |
    | Jangan tampilkan jika statusnya adalah peserta yang sudah selesai.
    |
    */

    @if(session('error') && !session('magang_selesai'))

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

    @if($errors->any() && !session('error') && !session('magang_selesai'))

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

        const button = $('#btnLogin');


        button

            .prop('disabled', true)

            .html(`

                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true">
                </span>

                Memproses...

            `);

    });

});

</script>

@endpush