@extends('layouts.back-office')

@section('title', 'Data Saya')

@section('content')

<div class="container-fluid">

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <h3 class="fw-bold mb-1">
        Data Saya
    </h3>

    <small class="text-muted">
        Informasi pribadi dan kelompok magang Anda.
    </small>

</div>


@if(!$peserta || !$pengajuan)

    {{-- ===================================================== --}}
    {{-- DATA BELUM TERHUBUNG --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body text-center py-5">

            <div
                class="d-inline-flex align-items-center justify-content-center
                       rounded-circle bg-warning bg-opacity-10 text-warning mb-3"
                style="width:60px;height:60px;"
            >
                <i class="bi bi-person-x fs-3"></i>
            </div>

            <h5 class="fw-bold mb-2">
                Data peserta belum ditemukan
            </h5>

            <p class="text-muted small mb-0">
                Akun Anda belum terhubung dengan data
                peserta yang diterima.
            </p>

        </div>

    </div>

@else

    {{-- ===================================================== --}}
    {{-- DATA DIRI + MENTOR --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-3">

        <div class="card-body p-3">

            {{-- Header --}}

            <div class="d-flex align-items-center mb-3">

                <div
                    class="d-flex align-items-center justify-content-center
                           rounded-3 bg-primary bg-opacity-10 text-primary"
                    style="width:44px;height:44px;"
                >
                    <i class="bi bi-person fs-5"></i>
                </div>

                <div class="ms-3">

                    <h6 class="fw-bold mb-0">
                        Data Diri
                    </h6>

                    <small class="text-muted">
                        Informasi peserta yang sedang login.
                    </small>

                </div>

            </div>


            {{-- Informasi --}}

            <div class="row g-2">

                {{-- Nama --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small">
                            Nama
                        </div>

                        <div class="fw-semibold small">
                            {{ $peserta['nama'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Email --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small">
                            Email
                        </div>

                        <div class="fw-semibold small text-break">
                            {{ $peserta['email'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- No HP --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small">
                            No. HP
                        </div>

                        <div class="fw-semibold small">
                            {{ $peserta['no_hp'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Peran --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small mb-1">
                            Peran
                        </div>

                        @if(($peserta['peran'] ?? '') === 'Ketua')

                            <span class="badge bg-primary">
                                <i class="bi bi-person-check me-1"></i>
                                Ketua
                            </span>

                        @else

                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-person me-1"></i>
                                Anggota
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MENTOR --}}
                {{-- ================================================= --}}

<div class="col-12">

    <div
        class="border rounded-3 px-3 py-2
               bg-light bg-opacity-50"
    >

        <div class="text-muted small">
            Mentor
        </div>

        @if($user->mentor)

            <div class="fw-semibold small">

                <i class="bi bi-person-badge text-primary me-1"></i>

                {{ $user->mentor->nama_mentor ?? '-' }}

            </div>

            @if($user->mentor->divisi)

                <div class="text-muted small mt-1">

                    <i class="bi bi-building me-1"></i>

                    {{ $user->mentor->divisi }}

                </div>

            @endif

        @else

            <div class="fw-semibold small text-muted">

                <i class="bi bi-person-badge me-1"></i>

                Mentor belum ditentukan

            </div>

        @endif

    </div>

</div>
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- KELOMPOK MAGANG + ANGGOTA --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-3">

        <div class="card-body p-3">

            {{-- Header --}}

            <div class="d-flex align-items-center justify-content-between mb-3">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-3 bg-success bg-opacity-10 text-success"
                        style="width:44px;height:44px;"
                    >
                        <i class="bi bi-people fs-5"></i>
                    </div>

                    <div class="ms-3">

                        <h6 class="fw-bold mb-0">
                            Kelompok Magang
                        </h6>

                        <small class="text-muted">
                            Informasi kelompok dan anggota.
                        </small>

                    </div>

                </div>


                <span class="badge bg-light text-dark border">

                    <i class="bi bi-people me-1"></i>

                    {{ 1 + $pengajuan->anggota->count() }}
                    Orang

                </span>

            </div>


            {{-- ================================================= --}}
            {{-- INFORMASI KELOMPOK --}}
            {{-- ================================================= --}}

            <div class="row g-2 mb-3">

                {{-- Kode --}}

                <div class="col-md-4">

                    <div class="border rounded-3 px-3 py-2 h-100">

                        <div class="text-muted small">
                            Kode Pengajuan
                        </div>

                        <div class="fw-bold small">
                            {{ $pengajuan->kode_pengajuan }}
                        </div>

                    </div>

                </div>


                {{-- Universitas --}}

                <div class="col-md-4">

                    <div class="border rounded-3 px-3 py-2 h-100">

                        <div class="text-muted small">
                            Universitas
                        </div>

                        <div class="fw-semibold small">
                            {{ $pengajuan->universitas }}
                        </div>

                    </div>

                </div>


                {{-- Status --}}

                <div class="col-md-4">

                    <div class="border rounded-3 px-3 py-2 h-100">

                        <div class="text-muted small mb-1">
                            Status
                        </div>

                        <span class="badge bg-success">

                            <i class="bi bi-check-circle me-1"></i>

                            {{ $pengajuan->status }}

                        </span>

                    </div>

                </div>


                {{-- Tanggal Mulai --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small">
                            Tanggal Mulai
                        </div>

                        <div class="fw-semibold small">
                            {{ $pengajuan->tanggal_mulai ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Tanggal Selesai --}}

                <div class="col-md-6">

                    <div class="border rounded-3 px-3 py-2">

                        <div class="text-muted small">
                            Tanggal Selesai
                        </div>

                        <div class="fw-semibold small">
                            {{ $pengajuan->tanggal_selesai ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ANGGOTA KELOMPOK --}}
            {{-- ================================================= --}}

            <div class="border-top pt-3">

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <div>

                        <div class="fw-bold small">
                            Anggota Kelompok
                        </div>

                        <div class="text-muted" style="font-size:12px;">
                            Peserta yang tergabung dalam kelompok ini.
                        </div>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th
                                    width="6%"
                                    class="text-center small"
                                >
                                    No
                                </th>

                                <th class="small">
                                    Nama Peserta
                                </th>

                                <th class="small">
                                    Email
                                </th>

                                <th class="small">
                                    No. HP
                                </th>

                                <th
                                    width="12%"
                                    class="text-center small"
                                >
                                    Peran
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            {{-- Ketua --}}

                            <tr>

                                <td class="text-center small">
                                    1
                                </td>

                                <td>

                                    <span class="fw-semibold small">
                                        {{ $pengajuan->nama_ketua }}
                                    </span>

                                </td>

                                <td>

                                    <span class="text-muted small">
                                        {{ $pengajuan->email_ketua }}
                                    </span>

                                </td>

                                <td class="small">
                                    {{ $pengajuan->no_hp ?? '-' }}
                                </td>

                                <td class="text-center">

                                    <span
                                        class="badge bg-primary bg-opacity-10
                                               text-primary border border-primary"
                                    >
                                        Ketua
                                    </span>

                                </td>

                            </tr>


                            {{-- Anggota --}}

                            @foreach($pengajuan->anggota as $index => $anggota)

                                <tr>

                                    <td class="text-center small">
                                        {{ $index + 2 }}
                                    </td>

                                    <td>

                                        <span class="fw-semibold small">
                                            {{ $anggota->nama_anggota }}
                                        </span>

                                    </td>

                                    <td>

                                        <span class="text-muted small">
                                            {{ $anggota->email }}
                                        </span>

                                    </td>

                                    <td class="small">
                                        {{ $anggota->no_hp ?? '-' }}
                                    </td>

                                    <td class="text-center">

                                        <span
                                            class="badge bg-light
                                                   text-dark border"
                                        >
                                            Anggota
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- SURAT PENERIMAAN --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-3">

            <div class="d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-3 bg-danger bg-opacity-10 text-danger"
                        style="width:44px;height:44px;"
                    >
                        <i class="bi bi-file-earmark-pdf fs-5"></i>
                    </div>

                    <div class="ms-3">

                        <h6 class="fw-bold mb-0">
                            Surat Penerimaan
                        </h6>

                        <small class="text-muted">
                            Surat penerimaan kelompok Anda.
                        </small>

                    </div>

                </div>


                @if($pengajuan->surat_penerimaan)

                    <a
                        href="{{ asset('storage/' . $pengajuan->surat_penerimaan) }}"
                        target="_blank"
                        class="btn btn-success btn-sm px-3"
                    >

                        <i class="bi bi-eye me-1"></i>

                        Lihat Surat

                    </a>

                @endif

            </div>


            @if($pengajuan->surat_penerimaan)

                <div class="mt-3">

                    <div
                        class="alert alert-success mb-0 py-2 px-3
                               d-flex align-items-center"
                    >

                        <i class="bi bi-check-circle me-2"></i>

                        <div class="small">

                            <strong>
                                Surat penerimaan tersedia.
                            </strong>

                            <span class="text-muted ms-1">
                                Berlaku untuk kelompok
                                {{ $pengajuan->kode_pengajuan }}.
                            </span>

                        </div>

                    </div>

                </div>

            @else

                <div class="mt-3">

                    <div
                        class="alert alert-warning mb-0 py-2 px-3"
                    >

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        <span class="small">
                            Surat penerimaan untuk kelompok Anda
                            belum tersedia.
                        </span>

                    </div>

                </div>

            @endif

        </div>

    </div>

@endif

</div>

@endsection
