@extends('layouts.public')

@section('title', 'Pengajuan Magang')

@section('public-content')

<section class="py-5 mt-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card shadow border-0 rounded-4">

                    <div class="card-body p-5">

                        <div class="text-center mb-5">

                            <h2 class="fw-bold">
                                Form Pengajuan Magang
                            </h2>

                            <p class="text-muted">
                                Silakan lengkapi seluruh data di bawah ini.
                            </p>

                        </div>

                        @if(session('success'))

                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>

                        @endif

<form
    id="formPengajuan"
    enctype="multipart/form-data">

    @csrf

                            <h5 class="mb-3">
                                Data Ketua
                            </h5>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Nama Ketua
                                    </label>

                                    <input
                                        type="text"
                                        name="nama_ketua"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Universitas
                                    </label>

                                    <input
                                        type="text"
                                        name="universitas"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Semester
                                    </label>

                                    <input
                                        type="number"
                                        name="semester"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Nomor HP
                                    </label>

                                    <input
                                        type="text"
                                        name="no_hp"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Email Ketua
                                    </label>

                                    <input
                                        type="email"
                                        name="email_ketua"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tanggal Mulai
                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_mulai"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        Tanggal Selesai
                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_selesai"
                                        class="form-control"
                                        required>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h5 class="mb-0">

                                    Anggota Tim (Opsional)

                                </h5>

<button
type="button"
class="btn btn-primary"
onclick="tambahAnggota()">

<i class="bi bi-plus-circle"></i>

Tambah Anggota

</button>
                            </div>

                            <div id="anggota-wrapper"></div>

                            <hr class="my-4">

                            <h5 class="mb-3">

                                Dokumen

                            </h5>

                            <div class="mb-3">

                                <label class="form-label">

                                    Proposal (.pdf)

                                </label>

                                <input
                                    type="file"
                                    name="proposal"
                                    class="form-control"
                                    accept=".pdf"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">

                                    Surat Permohonan (.pdf)

                                </label>

                                <input
                                    type="file"
                                    name="surat_permohonan"
                                    class="form-control"
                                    accept=".pdf"
                                    required>

                            </div>

                            <button
                                class="btn btn-primary w-100 py-3">

                                Kirim Pengajuan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection

