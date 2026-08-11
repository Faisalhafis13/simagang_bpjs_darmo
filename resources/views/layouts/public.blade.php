@extends('layouts.app')

@push('css')

<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

@endpush

@section('content')

<div class="public-layout">

    @include('components.public.header')

    @include('components.public.sidebar')

    <main class="public-content">

        @yield('public-content')

    </main>

    @include('components.footer')

</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const gallery = document.querySelector(".gallerySwiper");

    if (gallery) {

        new Swiper(".gallerySwiper", {

            loop: true,

            grabCursor: true,

            spaceBetween: 30,

            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },

            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

            breakpoints: {

                0: {
                    slidesPerView: 1,
                },

                768: {
                    slidesPerView: 2,
                },

                1200: {
                    slidesPerView: 3,
                }

            }

        });

    }

});

</script>

@endpush
