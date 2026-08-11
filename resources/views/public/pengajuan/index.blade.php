@extends('layouts.public')

@section('title', 'Pengajuan Magang')

@section('public-content')

<section class="application-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">

            <div class="application-card">

                {{-- HEADER --}}
                <div class="application-header">
                    <div class="application-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>

                    <div>
                        <span class="application-label">
                            SIMAGANG
                        </span>

                        <h2 class="application-title">
                            Form Pengajuan Magang
                        </h2>

                        <p class="application-subtitle">
                            Lengkapi data berikut dengan benar untuk mengajukan
                            permohonan magang di BPJS Ketenagakerjaan.
                        </p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success application-alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form
                    id="formPengajuan"
                    enctype="multipart/form-data">

                    @csrf

                    {{-- =========================
                         DATA KETUA
                    ========================== --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-number">
                                01
                            </div>

                            <div>
                                <h5>Data Ketua</h5>
                                <p>
                                    Informasi mahasiswa yang menjadi ketua
                                    kelompok pengajuan.
                                </p>
                            </div>
                        </div>

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Nama Ketua
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-person"></i>

                                    <input
                                        type="text"
                                        name="nama_ketua"
                                        class="form-control"
                                        placeholder="Masukkan nama lengkap"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Universitas
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-building"></i>

                                    <input
                                        type="text"
                                        name="universitas"
                                        class="form-control"
                                        placeholder="Nama universitas"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Semester
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-mortarboard"></i>

                                    <input
                                        type="number"
                                        name="semester"
                                        class="form-control"
                                        placeholder="Contoh: 6"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Nomor HP
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-telephone"></i>

                                    <input
                                        type="text"
                                        name="no_hp"
                                        class="form-control"
                                        placeholder="08xxxxxxxxxx"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Email Ketua
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-envelope"></i>

                                    <input
                                        type="email"
                                        name="email_ketua"
                                        class="form-control"
                                        placeholder="nama@email.com"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Mulai
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-event"></i>

                                    <input
                                        type="date"
                                        name="tanggal_mulai"
                                        class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Selesai
                                </label>

                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-check"></i>

                                    <input
                                        type="date"
                                        name="tanggal_selesai"
                                        class="form-control"
                                        required>
                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- =========================
                         ANGGOTA TIM
                    ========================== --}}
                    <div class="form-section">

                        <div class="section-heading section-heading-between">

                            <div class="d-flex align-items-start gap-3">

                                <div class="section-number">
                                    02
                                </div>

                                <div>
                                    <h5>Anggota Tim</h5>

                                    <p>
                                        Tambahkan anggota kelompok jika
                                        pengajuan dilakukan secara tim.
                                    </p>
                                </div>

                            </div>

                            <button
                                type="button"
                                class="btn btn-add-member"
                                onclick="tambahAnggota()">

                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Anggota

                            </button>

                        </div>

                        <div id="anggota-wrapper" class="member-wrapper">
                        </div>

                        <div class="empty-member-info">
                            <i class="bi bi-people"></i>

                            <span>
                                Belum ada anggota tambahan.
                                Klik <strong>Tambah Anggota</strong>
                                jika diperlukan.
                            </span>
                        </div>

                    </div>


                    {{-- =========================
                         DOKUMEN
                    ========================== --}}
                    <div class="form-section">

                        <div class="section-heading">

                            <div class="section-number">
                                03
                            </div>

                            <div>
                                <h5>Dokumen Pengajuan</h5>

                                <p>
                                    Upload dokumen pendukung dalam format PDF.
                                </p>
                            </div>

                        </div>

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="document-upload">

                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>

                                    <div class="document-content">

                                        <label class="form-label">
                                            Proposal
                                        </label>

                                        <small>
                                            Format PDF
                                        </small>

                                        <input
                                            type="file"
                                            name="proposal"
                                            class="form-control"
                                            accept=".pdf"
                                            required>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="document-upload">

                                    <div class="document-icon">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>

                                    <div class="document-content">

                                        <label class="form-label">
                                            Surat Permohonan
                                        </label>

                                        <small>
                                            Format PDF
                                        </small>

                                        <input
                                            type="file"
                                            name="surat_permohonan"
                                            class="form-control"
                                            accept=".pdf"
                                            required>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =========================
                         SUBMIT
                    ========================== --}}
                    <div class="application-submit">

                        <div class="submit-info">
                            <i class="bi bi-shield-check"></i>

                            <div>
                                <strong>Pastikan data sudah benar</strong>

                                <small>
                                    Periksa kembali seluruh informasi
                                    sebelum mengirim pengajuan.
                                </small>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-submit">

                            <span>
                                Kirim Pengajuan
                            </span>

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

</section>

@endsection
