@extends('layouts.public')

@section('title', 'Ubah Password')

@section('public-content')

<section class="login-page">

    <div class="container">

        <div class="row justify-content-center align-items-center">

            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

                <div class="login-card">

                    {{-- ========================================================= --}}
                    {{-- ICON & TITLE --}}
                    {{-- ========================================================= --}}

                    <div class="text-center mb-4">

                        <div class="login-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                        <h3 class="mt-4 fw-bold">
                            Ubah Password
                        </h3>

                        <p class="text-muted mb-0">
                            Silakan buat password baru untuk akun Anda.
                        </p>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- INFORMASI --}}
                    {{-- ========================================================= --}}

                    <div
                        class="alert alert-info border-0 mb-4"
                        style="border-radius: 12px;"
                    >

                        <div class="d-flex align-items-start">

                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                            <div class="small">
                                Demi keamanan akun, password awal harus
                                diganti sebelum Anda dapat melanjutkan
                                menggunakan website.
                            </div>

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- FORM UBAH PASSWORD --}}
                    {{-- ========================================================= --}}

                    <form
                        action="{{ route('password.change.post') }}"
                        method="POST"
                        id="formChangePassword"
                    >

                        @csrf


                        {{-- ===================================================== --}}
                        {{-- PASSWORD BARU --}}
                        {{-- ===================================================== --}}

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label fw-semibold"
                            >
                                Password Baru
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
                                    placeholder="Masukkan password baru"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >


                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="togglePassword"
                                    tabindex="-1"
                                    aria-label="Tampilkan password"
                                >

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>


                            @error('password')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror


                            <small class="text-muted d-block mt-2">
                                Password minimal 8 karakter.
                            </small>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- KONFIRMASI PASSWORD --}}
                        {{-- ===================================================== --}}

                        <div class="mb-4">

                            <label
                                for="password_confirmation"
                                class="form-label fw-semibold"
                            >
                                Konfirmasi Password
                            </label>


                            <div class="input-group input-group-lg">

                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>


                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >


                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="togglePasswordConfirmation"
                                    tabindex="-1"
                                    aria-label="Tampilkan konfirmasi password"
                                >

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- SUBMIT --}}
                        {{-- ===================================================== --}}

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-3"
                            id="btnChangePassword"
                        >

                            <i class="bi bi-check-circle me-2"></i>

                            Simpan Password

                        </button>

                    </form>


                    {{-- ========================================================= --}}
                    {{-- FOOTER --}}
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


{{-- ========================================================================== --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================================== --}}

@push('js')

<script>

$(document).ready(function () {

    /* ====================================================================== */
    /* TOGGLE PASSWORD */
    /* ====================================================================== */

    $('#togglePassword').on('click', function () {

        const input = $('#password');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {

            input.attr('type', 'text');

            icon
                .removeClass('bi-eye')
                .addClass('bi-eye-slash');

            $(this).attr('aria-label', 'Sembunyikan password');

        } else {

            input.attr('type', 'password');

            icon
                .removeClass('bi-eye-slash')
                .addClass('bi-eye');

            $(this).attr('aria-label', 'Tampilkan password');

        }

    });


    /* ====================================================================== */
    /* TOGGLE KONFIRMASI PASSWORD */
    /* ====================================================================== */

    $('#togglePasswordConfirmation').on('click', function () {

        const input = $('#password_confirmation');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {

            input.attr('type', 'text');

            icon
                .removeClass('bi-eye')
                .addClass('bi-eye-slash');

            $(this).attr('aria-label', 'Sembunyikan konfirmasi password');

        } else {

            input.attr('type', 'password');

            icon
                .removeClass('bi-eye-slash')
                .addClass('bi-eye');

            $(this).attr('aria-label', 'Tampilkan konfirmasi password');

        }

    });


    /* ====================================================================== */
    /* FORM UBAH PASSWORD */
    /* ====================================================================== */

    $('#formChangePassword').on('submit', function (e) {

        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();


        /* ------------------------------------------------------------------ */
        /* VALIDASI PASSWORD MINIMAL 8 KARAKTER */
        /* ------------------------------------------------------------------ */

        if (password.length < 8) {

            e.preventDefault();

            Swal.fire({

                icon: 'warning',

                title: 'Password Belum Memenuhi Syarat',

                text: 'Password baru minimal terdiri dari 8 karakter.',

                confirmButtonText: 'OK',

                confirmButtonColor: '#0d6efd'

            });

            return;

        }


        /* ------------------------------------------------------------------ */
        /* VALIDASI KONFIRMASI PASSWORD */
        /* ------------------------------------------------------------------ */

        if (password !== confirmation) {

            e.preventDefault();

            Swal.fire({

                icon: 'warning',

                title: 'Konfirmasi Password Tidak Sesuai',

                text: 'Pastikan password baru dan konfirmasi password sama.',

                confirmButtonText: 'Coba Lagi',

                confirmButtonColor: '#0d6efd'

            });

            return;

        }


        /* ------------------------------------------------------------------ */
        /* LOADING */
        /* ------------------------------------------------------------------ */

        const button = $('#btnChangePassword');

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


    /* ====================================================================== */
    /* PASSWORD BERHASIL DIUBAH */
    /* ====================================================================== */

    @if(session('password_changed'))

        Swal.fire({

            icon: 'success',

            title: 'Password Berhasil Diubah',

            text: @json(session('password_changed')),

            confirmButtonText: 'Lanjutkan',

            confirmButtonColor: '#0d6efd',

            allowOutsideClick: false,

            allowEscapeKey: false

        }).then(function () {

            // Tidak perlu melakukan redirect di sini.
            // Redirect sudah dilakukan oleh controller.

        });

    @endif


    /* ====================================================================== */
    /* SESSION ERROR */
    /* ====================================================================== */

    @if(session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Gagal Mengubah Password',

            text: @json(session('error')),

            confirmButtonText: 'Coba Lagi',

            confirmButtonColor: '#0d6efd'

        });

    @endif


    /* ====================================================================== */
    /* VALIDATION ERROR */
    /* ====================================================================== */

    @if($errors->any() && !session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Password Tidak Dapat Diubah',

            text: @json($errors->first()),

            confirmButtonText: 'Coba Lagi',

            confirmButtonColor: '#0d6efd'

        });

    @endif

});

</script>

@endpush
