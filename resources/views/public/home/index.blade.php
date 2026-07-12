@extends('layouts.public')

@section('title', 'Beranda')

@section('public-content')

{{-- ===========================
Hero
=========================== --}}

<section class="hero">

    <div class="container-xxl px-4">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="badge-system">
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
                    Cabang Surabaya Darmo. Website ini digunakan sebagai media
                    pengajuan magang, pengumuman hasil seleksi, serta pengelolaan
                    aktivitas magang secara digital.
                </p>

            </div>

            <div class="col-lg-6 text-center">

                <div class="hero-image-wrapper">
                    <img
                        src="{{ asset('assets/images/fotobersama.jpeg') }}"
                        alt="Hero"
                        class="hero-image">
                </div>

            </div>

        </div>

    </div>

</section>
{{-- ===========================
Tentang
=========================== --}}

<section class="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

<img
    src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=900"
    class="img-fluid rounded-4 shadow"
    alt="Tentang Program">

            </div>

            <div class="col-lg-6">

                <h2>

                    Tentang Program Magang

                </h2>

                <p>

                    Program Magang BPJS Ketenagakerjaan memberikan kesempatan
                    kepada mahasiswa untuk memperoleh pengalaman kerja secara
                    langsung di lingkungan profesional.

                </p>

                <p>

                    Peserta akan memperoleh bimbingan dari mentor pada masing-masing
                    divisi sehingga mampu meningkatkan kompetensi akademik maupun
                    keterampilan kerja.

                </p>

            </div>

        </div>

    </div>

</section>

{{-- ===========================
Keunggulan
=========================== --}}

<section class="advantages">

    <div class="container">

        <div class="text-center mb-5">

            <h2>

                Mengapa Memilih BPJS Ketenagakerjaan?

            </h2>

            <p>

                Program magang dirancang untuk memberikan pengalaman terbaik
                bagi mahasiswa.

            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="advantage-card">

                    <i class="bi bi-briefcase-fill"></i>

                    <h5>

                        Lingkungan Profesional

                    </h5>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="advantage-card">

                    <i class="bi bi-people-fill"></i>

                    <h5>

                        Mentor Berpengalaman

                    </h5>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="advantage-card">

                    <i class="bi bi-laptop-fill"></i>

                    <h5>

                        Digital Learning

                    </h5>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="advantage-card">

                    <i class="bi bi-graph-up-arrow"></i>

                    <h5>

                        Pengembangan Skill

                    </h5>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ===========================
Alur
=========================== --}}

<section class="flow">

    <div class="container">

        <div class="text-center mb-5">

            <h2>

                Alur Pengajuan Magang

            </h2>

        </div>

        <div class="row text-center g-4">

            <div class="col">

                <div class="flow-item">

                    <div class="flow-number">

                        1

                    </div>

                    <h5>

                        Pengajuan

                    </h5>

                </div>

            </div>

            <div class="col">

                <div class="flow-item">

                    <div class="flow-number">

                        2

                    </div>

                    <h5>

                        Seleksi

                    </h5>

                </div>

            </div>

            <div class="col">

                <div class="flow-item">

                    <div class="flow-number">

                        3

                    </div>

                    <h5>

                        Pengumuman

                    </h5>

                </div>

            </div>

            <div class="col">

                <div class="flow-item">

                    <div class="flow-number">

                        4

                    </div>

                    <h5>

                        Mulai Magang

                    </h5>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ===========================
Galeri
=========================== --}}

{{-- ===========================
Galeri
=========================== --}}

{{-- ===========================
Galeri
=========================== --}}

<section class="gallery-section">

    <div class="container">

        <div class="row justify-content-center mb-5">

            <div class="col-lg-8 text-center">

                <h2 class="section-title">
                    Galeri Kegiatan
                </h2>

                <p class="section-subtitle">
                    Dokumentasi kegiatan Program Magang BPJS Ketenagakerjaan
                    Cabang Surabaya Darmo.
                </p>

            </div>

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
@endsection