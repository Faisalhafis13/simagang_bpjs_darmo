@extends('layouts.public')

@section('title','Ganti Password')

@section('public-content')

<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Ubah Password Awal</h2>
                            <p class="text-muted">
                                Anda harus mengganti password awal sebelum melanjutkan.
                            </p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('password.change.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
