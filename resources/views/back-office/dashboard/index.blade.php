@extends('layouts.back-office')

@section('title', 'Dashboard')

@section('content')

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="mb-4">

            <h5 class="fw-bold mb-1">
                Selamat Datang 👋
            </h5>

        </div>


        <div class="row g-3">

            <div class="col-12 col-md-6">

                <div class="border rounded-3 p-3">

                    <small class="text-muted d-block mb-1">
                        Nama Pengguna
                    </small>

                    <div class="fw-semibold">
                        {{ auth()->user()->name }}
                    </div>

                </div>

            </div>


            <div class="col-12 col-md-6">

                <div class="border rounded-3 p-3">

                    <small class="text-muted d-block mb-1">
                        Role
                    </small>

                    <span class="badge bg-primary">

                        <i class="bi bi-shield-check me-1"></i>

                        {{ optional(auth()->user()->role)->name ?? 'Belum ada role' }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SWEETALERT --}}
{{-- ========================================================= --}}

@if(session('success'))

    @push('js')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({

                icon: 'success',

                title: 'Berhasil!',

                text: @json(session('success')),

                confirmButtonText: 'Lanjutkan',

                confirmButtonColor: '#0d6efd',

                allowOutsideClick: false,

                allowEscapeKey: false

            });

        });

    </script>

    @endpush

@endif

@endsection
