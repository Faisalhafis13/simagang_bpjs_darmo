@extends('layouts.public')

@section('title', 'Beranda')

@section('public-content')

{{-- =========================================================
HERO
========================================================= --}}

<section class="hero">

<div class="container">
    <div class="row align-items-center g-5">

        {{-- Hero Content --}}
        <div class="col-lg-6">

            <span class="badge-system">
                <i class="bi bi-building me-2"></i>
                BPJS Ketenagakerjaan Cabang Surabaya Darmo
            </span>

            <h1>
                Sistem Informasi
                <span class="text-primary">
                    Magang
                </span>
            </h1>

            <p>
                Selamat datang di Sistem Informasi Magang BPJS Ketenagakerjaan
                Cabang Surabaya Darmo. Ajukan magang, pantau proses seleksi,
                dan dapatkan informasi hasil pengajuan secara digital.
            </p>

            {{-- Hero Buttons --}}
            <div class="hero-button mt-4">

                <a
                    href="{{ route('pengajuan') }}"
                    class="btn-primary-custom"
                >
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Ajukan Magang
                </a>

                <a
                    href="{{ route('hasil') }}"
                    class="btn-outline-custom"
                >
                    <i class="bi bi-search me-2"></i>
                    Lihat Hasil
                </a>

            </div>

            {{-- Hero Information --}}
            <div class="hero-info mt-4">

                <div class="info-box">

                    <h3>
                        <i class="bi bi-laptop"></i>
                    </h3>

                    <span>
                        Proses Digital
                    </span>

                </div>

                <div class="info-box">

                    <h3>
                        <i class="bi bi-shield-check"></i>
                    </h3>

                    <span>
                        Data Terintegrasi
                    </span>

                </div>

                <div class="info-box">

                    <h3>
                        <i class="bi bi-clock-history"></i>
                    </h3>

                    <span>
                        Mudah & Cepat
                    </span>

                </div>

            </div>

        </div>

        {{-- Hero Image --}}
        <div class="col-lg-6 text-center">

            <div class="hero-image-wrapper">

                <img
                    src="{{ asset('assets/images/fotobersama.jpeg') }}"
                    alt="Kegiatan bersama peserta magang BPJS Ketenagakerjaan"
                    class="hero-image"
                >

                <div class="hero-image-overlay">

                    <div class="hero-overlay-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>
                        <strong>
                            Program Magang
                        </strong>

                        <small>
                            BPJS Ketenagakerjaan
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

</section>

{{-- =========================================================
TENTANG PROGRAM
========================================================= --}}

<section class="about">

<div class="container">

    <div class="row align-items-center g-5">

        <div class="col-lg-6">

            <div class="about-image-wrapper">

                <img
                    src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=900"
                    class="about-image"
                    alt="Lingkungan kerja profesional"
                >

                <div class="about-image-card">

                    <div class="about-image-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div>
                        <strong>
                            Pengalaman Profesional
                        </strong>

                        <small>
                            Belajar langsung di lingkungan kerja
                        </small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <span class="section-label">
                TENTANG PROGRAM
            </span>

            <h2 class="section-heading">
                Membangun Pengalaman,
                <span class="text-primary">
                    Mengembangkan Potensi
                </span>
            </h2>

            <p class="section-description">
                Program Magang BPJS Ketenagakerjaan memberikan kesempatan
                kepada mahasiswa untuk memperoleh pengalaman kerja secara
                langsung di lingkungan profesional.
            </p>

            <p class="section-description">
                Peserta akan memperoleh bimbingan dari mentor pada masing-masing
                divisi sehingga mampu meningkatkan kompetensi akademik maupun
                keterampilan kerja.
            </p>

            <div class="about-points">

                <div class="about-point">
                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        Pengalaman kerja secara langsung
                    </span>
                </div>

                <div class="about-point">
                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        Bimbingan dari mentor
                    </span>
                </div>

                <div class="about-point">
                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        Pengembangan kompetensi dan keterampilan
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>

</section>

{{-- =========================================================
KEUNGGULAN
========================================================= --}}

<section class="advantages">

<div class="container">

    <div class="text-center mb-5">

        <span class="section-label">
            KEUNGGULAN PROGRAM
        </span>

        <h2 class="section-heading">
            Mengapa Memilih
            <span class="text-primary">
                BPJS Ketenagakerjaan?
            </span>
        </h2>

        <p class="section-description mx-auto">
            Program magang dirancang untuk memberikan pengalaman terbaik
            bagi mahasiswa melalui lingkungan kerja yang profesional
            dan mendukung proses pembelajaran.
        </p>

    </div>

    <div class="row g-4">

        <div class="col-lg-3 col-md-6">

            <div class="advantage-card">

                <div class="advantage-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>

                <h5>
                    Lingkungan Profesional
                </h5>

                <p>
                    Mengenal dan merasakan lingkungan kerja profesional
                    secara langsung.
                </p>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="advantage-card">

                <div class="advantage-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <h5>
                    Mentor Berpengalaman
                </h5>

                <p>
                    Mendapatkan arahan dan bimbingan selama menjalankan
                    kegiatan magang.
                </p>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="advantage-card">

                <div class="advantage-icon">
                    <i class="bi bi-laptop-fill"></i>
                </div>

                <h5>
                    Digital Learning
                </h5>

                <p>
                    Mendukung proses pembelajaran dengan pemanfaatan
                    teknologi digital.
                </p>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="advantage-card">

                <div class="advantage-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <h5>
                    Pengembangan Skill
                </h5>

                <p>
                    Meningkatkan kemampuan akademik dan keterampilan
                    yang dibutuhkan di dunia kerja.
                </p>

            </div>

        </div>

    </div>

</div>

</section>

{{-- =========================================================
ALUR PENGAJUAN
========================================================= --}}

<section class="flow">

<div class="container">

    <div class="text-center mb-5">

        <span class="section-label">
            PROSES PENGAJUAN
        </span>

        <h2 class="section-heading">
            Alur Pengajuan Magang
        </h2>

        <p class="section-description mx-auto">
            Ikuti beberapa tahapan berikut untuk mengajukan program magang.
        </p>

    </div>

    <div class="flow-wrapper">

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="flow-item">

                    <div class="flow-number">
                        1
                    </div>

                    <div class="flow-icon">
                        <i class="bi bi-file-earmark-plus-fill"></i>
                    </div>

                    <h5>
                        Pengajuan
                    </h5>

                    <p>
                        Lengkapi data dan dokumen pengajuan magang.
                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="flow-item">

                    <div class="flow-number">
                        2
                    </div>

                    <div class="flow-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h5>
                        Seleksi
                    </h5>

                    <p>
                        Pengajuan akan melalui proses pemeriksaan dan seleksi.
                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="flow-item">

                    <div class="flow-number">
                        3
                    </div>

                    <div class="flow-icon">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>

                    <h5>
                        Pengumuman
                    </h5>

                    <p>
                        Hasil pengajuan dapat dilihat menggunakan kode pengajuan.
                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="flow-item">

                    <div class="flow-number">
                        4
                    </div>

                    <div class="flow-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                    <h5>
                        Mulai Magang
                    </h5>

                    <p>
                        Peserta yang diterima dapat memulai kegiatan magang.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

</section>

{{-- ===========================
Galeri
=========================== --}}

<section class="gallery-section">

    <div class="container">

        <div class="gallery-heading">

            <h2 class="section-title">
                Galeri Kegiatan
            </h2>

            <p class="section-subtitle">
                Dokumentasi kegiatan Program Magang BPJS Ketenagakerjaan
                Cabang Surabaya Darmo.
            </p>

        </div>

        @php

            $photos = [
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200',
                'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=1200',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200',
                'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200',
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200',
            ];

        @endphp

        <div class="swiper gallerySwiper">

            <div class="swiper-wrapper">

                @foreach ($photos as $photo)

                    <div class="swiper-slide">

                        <div class="gallery-card">

                            <img
                                src="{{ $photo }}"
                                class="gallery-image"
                                alt="Galeri Magang">

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="swiper-button-prev"></div>

            <div class="swiper-button-next"></div>

            <div class="swiper-pagination"></div>

        </div>

    </div>

</section>
{{-- =========================================================
CTA
========================================================= --}}

<section class="home-cta">

<div class="container">

    <div class="cta-card">

        <div class="row align-items-center g-4">

            <div class="col-lg-8">

                <span class="cta-label">
                    SIAP MEMULAI?
                </span>

                <h2>
                    Ajukan Magang Anda Sekarang
                </h2>

                <p>
                    Lengkapi data pengajuan dan ikuti proses seleksi
                    melalui Sistem Informasi Magang BPJS Ketenagakerjaan.
                </p>

            </div>

            <div class="col-lg-4 text-lg-end">

                <a
                    href="{{ route('pengajuan') }}"
                    class="btn-cta"
                >
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Ajukan Magang
                </a>

            </div>

        </div>

    </div>

</div>

</section>

@endsection
