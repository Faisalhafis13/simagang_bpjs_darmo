@extends('layouts.back-office')

@section('title', 'Data Saya')

@section('content')

<div class="container-fluid">

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="d-flex flex-wrap justify-content-between align-items-end mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Data Saya
        </h3>

        <div class="text-muted small">
            Informasi pribadi, kelompok, mentor, dan masa magang Anda.
        </div>
    </div>

    @if($peserta && $pengajuan)

        <div class="mt-2 mt-md-0">

            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">

                <i class="bi bi-shield-check me-1"></i>

                Peserta Terdaftar

            </span>

        </div>

    @endif

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
                style="width:70px;height:70px;"
            >
                <i class="bi bi-person-x fs-2"></i>
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
    {{-- RINGKASAN MASA MAGANG --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-4 bg-primary bg-opacity-10 text-primary"
                        style="width:52px;height:52px;"
                    >
                        <i class="bi bi-calendar2-week fs-4"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="fw-bold mb-1">
                            Masa Magang
                        </h5>

                        <div class="text-muted small">
                            Periode pelaksanaan magang Anda.
                        </div>

                    </div>

                </div>


                @if($statusWaktuMagang === 'belum_mulai')

                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-3 py-2 mt-2 mt-md-0">

                        <i class="bi bi-calendar-event me-1"></i>

                        Belum Dimulai

                    </span>

                @elseif($statusWaktuMagang === 'berlangsung')

                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2 mt-2 mt-md-0">

                        <i class="bi bi-broadcast me-1"></i>

                        Sedang Berlangsung

                    </span>

                @elseif($statusWaktuMagang === 'selesai')

                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 mt-2 mt-md-0">

                        <i class="bi bi-check2-all me-1"></i>

                        Selesai

                    </span>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- TANGGAL --}}
            {{-- ================================================= --}}

            <div class="row g-3 mb-4">

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light bg-opacity-50">

                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar-event me-1"></i>
                            Tanggal Mulai
                        </div>

                        <div class="fw-bold">
                            {{ $pengajuan->tanggal_mulai ?? '-' }}
                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light bg-opacity-50">

                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar-check me-1"></i>
                            Tanggal Selesai
                        </div>

                        <div class="fw-bold">
                            {{ $pengajuan->tanggal_selesai ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- STATUS WAKTU --}}
            {{-- ================================================= --}}

            @if($statusWaktuMagang === 'belum_mulai')

                <div class="rounded-4 border border-info-subtle bg-info-subtle p-4">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center
                                   rounded-circle bg-white text-info me-3 shadow-sm"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>

                        <div>

                            <div class="fw-bold text-info-emphasis">
                                Magang belum dimulai
                            </div>

                            <div class="small text-info-emphasis">

                                Magang Anda akan dimulai dalam

                                <strong>
                                    {{ $sisaHariMagang }} hari
                                </strong>.

                            </div>

                        </div>

                    </div>

                </div>


            @elseif($statusWaktuMagang === 'berlangsung')

                @php

                    $progressMagang = 0;

                    if (($totalHariMagang ?? 0) > 0) {

                        $progressMagang =
                            (($totalHariMagang - $sisaHariMagang)
                            / $totalHariMagang) * 100;

                    }

                    $progressMagang =
                        max(0, min(100, $progressMagang));

                @endphp


                <div class="rounded-4 border border-success-subtle bg-success-subtle p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <div class="fw-bold text-success-emphasis">
                                Magang sedang berlangsung
                            </div>

                            <div class="small text-success-emphasis">
                                Perjalanan magang Anda sudah berjalan.
                            </div>

                        </div>

                        <div class="text-end">

                            <div class="fw-bold text-success fs-5">
                                {{ $sisaHariMagang }}
                            </div>

                            <div class="text-muted small">
                                hari tersisa
                            </div>

                        </div>

                    </div>


                    <div class="progress mb-2"
                         style="height:10px;">

                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: {{ $progressMagang }}%;"
                            aria-valuenow="{{ $progressMagang }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>


                    <div class="d-flex justify-content-between">

                        <small class="text-muted">
                            {{ round($progressMagang) }}% selesai
                        </small>

                        <small class="text-muted">
                            {{ $totalHariMagang }} hari total
                        </small>

                    </div>


                    @if($sisaHariMagang == 0)

                        <div class="alert alert-warning mt-3 mb-0 py-2 px-3">

                            <i class="bi bi-exclamation-circle me-1"></i>

                            <small>
                                <strong>Hari terakhir magang.</strong>
                                Semangat menyelesaikan kegiatan magang Anda hari ini.
                            </small>

                        </div>

                    @endif

                </div>


            @elseif($statusWaktuMagang === 'selesai')

                <div class="rounded-4 border border-secondary-subtle bg-secondary-subtle p-4">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center
                                   rounded-circle bg-white text-secondary me-3 shadow-sm"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-check2-all fs-5"></i>
                        </div>

                        <div>

                            <div class="fw-bold text-secondary-emphasis">
                                Masa magang telah selesai
                            </div>

                            <div class="small text-secondary-emphasis">
                                Terima kasih telah menyelesaikan kegiatan magang Anda.
                            </div>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DATA DIRI --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-3">

        <div class="card-body p-4">

            <div class="d-flex align-items-center mb-4">

                <div
                    class="d-flex align-items-center justify-content-center
                           rounded-4 bg-primary bg-opacity-10 text-primary"
                    style="width:48px;height:48px;"
                >
                    <i class="bi bi-person fs-4"></i>
                </div>

                <div class="ms-3">

                    <h5 class="fw-bold mb-1">
                        Data Diri
                    </h5>

                    <div class="text-muted small">
                        Informasi pribadi peserta yang sedang login.
                    </div>

                </div>

            </div>


            <div class="row g-3">

                {{-- Nama --}}

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Nama
                        </div>

                        <div class="fw-semibold">
                            {{ $peserta['nama'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Email --}}

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Email
                        </div>

                        <div class="fw-semibold text-break">
                            {{ $peserta['email'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- No HP --}}

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-1">
                            No. HP
                        </div>

                        <div class="fw-semibold">
                            {{ $peserta['no_hp'] ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Peran --}}

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-2">
                            Peran
                        </div>

                        @if(($peserta['peran'] ?? '') === 'Ketua')

                            <span class="badge rounded-pill bg-primary px-3 py-2">

                                <i class="bi bi-person-check me-1"></i>

                                Ketua

                            </span>

                        @else

                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">

                                <i class="bi bi-person me-1"></i>

                                Anggota

                            </span>

                        @endif

                    </div>

                </div>


                {{-- Mentor --}}

                <div class="col-12">

                    <div class="border rounded-4 p-3 bg-light bg-opacity-50">

                        <div class="text-muted small mb-2">
                            Mentor
                        </div>

                        @if($user->mentor)

                            <div class="d-flex align-items-center">

                                <div
                                    class="d-flex align-items-center justify-content-center
                                           rounded-circle bg-primary bg-opacity-10 text-primary me-3"
                                    style="width:42px;height:42px;"
                                >
                                    <i class="bi bi-person-badge"></i>
                                </div>

                                <div>

                                    <div class="fw-semibold">

                                        {{ $user->mentor->nama_mentor ?? '-' }}

                                    </div>

                                    @if($user->mentor->divisi)

                                        <div class="text-muted small">

                                            <i class="bi bi-building me-1"></i>

                                            {{ $user->mentor->divisi }}

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @else

                            <div class="text-muted small">

                                <i class="bi bi-person-badge me-1"></i>

                                Mentor belum ditentukan.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- KELOMPOK MAGANG --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-3">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-4 bg-success bg-opacity-10 text-success"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-people fs-4"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="fw-bold mb-1">
                            Kelompok Magang
                        </h5>

                        <div class="text-muted small">
                            Informasi kelompok dan anggota.
                        </div>

                    </div>

                </div>


                <span class="badge rounded-pill bg-light text-dark border px-3 py-2 mt-2 mt-md-0">

                    <i class="bi bi-people me-1"></i>

                    {{ 1 + $pengajuan->anggota->count() }}

                    Orang

                </span>

            </div>


            {{-- ================================================= --}}
            {{-- INFORMASI KELOMPOK --}}
            {{-- ================================================= --}}

            <div class="row g-3 mb-4">

                {{-- Kode --}}

                <div class="col-md-4">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Kode Pengajuan
                        </div>

                        <div class="fw-bold">
                            {{ $pengajuan->kode_pengajuan }}
                        </div>

                    </div>

                </div>


                {{-- Universitas --}}

                <div class="col-md-4">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Perguruan Tinggi
                        </div>

                        <div class="fw-semibold">
                            {{ $pengajuan->universitas }}
                        </div>

                    </div>

                </div>


                {{-- Status --}}

                <div class="col-md-4">

                    <div class="border rounded-4 p-3 h-100">

                        <div class="text-muted small mb-2">
                            Status Pengajuan
                        </div>

                        <span class="badge rounded-pill bg-success px-3 py-2">

                            <i class="bi bi-check-circle me-1"></i>

                            {{ $pengajuan->status }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ANGGOTA KELOMPOK --}}
            {{-- ================================================= --}}

            <div class="border-top pt-4">

                <div class="mb-3">

                    <div class="fw-bold">
                        Anggota Kelompok
                    </div>

                    <div class="text-muted small">
                        Peserta yang tergabung dalam kelompok ini.
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

                                <td class="text-center small fw-semibold">
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
                                        class="badge rounded-pill
                                               bg-primary bg-opacity-10
                                               text-primary border
                                               border-primary px-3"
                                    >
                                        Ketua
                                    </span>

                                </td>

                            </tr>


                            {{-- Anggota --}}

                            @foreach($pengajuan->anggota as $index => $anggota)

                                <tr>

                                    <td class="text-center small fw-semibold">
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
                                            class="badge rounded-pill
                                                   bg-light text-dark border px-3"
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

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-4 bg-danger bg-opacity-10 text-danger"
                        style="width:48px;height:48px;"
                    >
                        <i class="bi bi-file-earmark-pdf fs-4"></i>
                    </div>

                    <div class="ms-3">

                        <h5 class="fw-bold mb-1">
                            Surat Penerimaan
                        </h5>

                        <div class="text-muted small">
                            Surat penerimaan kelompok Anda.
                        </div>

                    </div>

                </div>


                @if($pengajuan->surat_penerimaan)

                    <a
                        href="{{ asset('storage/' . $pengajuan->surat_penerimaan) }}"
                        target="_blank"
                        class="btn btn-success btn-sm px-3 rounded-3 mt-3 mt-md-0"
                    >

                        <i class="bi bi-eye me-1"></i>

                        Lihat Surat

                    </a>

                @endif

            </div>


            @if($pengajuan->surat_penerimaan)

                <div class="mt-4">

                    <div
                        class="alert alert-success mb-0 py-3 px-3
                               d-flex align-items-center rounded-4"
                    >

                        <i class="bi bi-check-circle fs-5 me-3"></i>

                        <div class="small">

                            <div class="fw-semibold">
                                Surat penerimaan tersedia.
                            </div>

                            <div class="text-muted">
                                Berlaku untuk kelompok
                                {{ $pengajuan->kode_pengajuan }}.
                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="mt-4">

                    <div
                        class="alert alert-warning mb-0 py-3 px-3 rounded-4"
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
