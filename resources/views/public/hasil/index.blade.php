@extends('layouts.public')

@section('title', 'Lihat Hasil')

@section('public-content')

<section class="hasil-page">

```
<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-xl-6">

            <div class="hasil-card">

                {{-- ========================================================= --}}
                {{-- ICON --}}
                {{-- ========================================================= --}}

                <div class="hasil-icon mb-4">

                    <i class="bi bi-search"></i>

                </div>


                {{-- ========================================================= --}}
                {{-- HEADER --}}
                {{-- ========================================================= --}}

                <div class="text-center mb-4">

                    <span class="badge-system mb-3">
                        Status Pengajuan
                    </span>

                    <h2 class="fw-bold mb-3">
                        Lihat Hasil Pengajuan
                    </h2>

                    <p class="text-muted mb-0">
                        Masukkan kode pengajuan yang telah diberikan
                        untuk melihat status pengajuan magang Anda.
                    </p>

                </div>


                {{-- ========================================================= --}}
                {{-- FORM --}}
                {{-- ========================================================= --}}

                <form id="formHasil">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="kode_pengajuan"
                            class="form-label fw-semibold"
                        >
                            Kode Pengajuan
                        </label>


                        <div class="input-group input-group-lg">

                            <span class="input-group-text">

                                <i class="bi bi-upc-scan"></i>

                            </span>


                            <input
                                type="text"
                                name="kode_pengajuan"
                                id="kode_pengajuan"
                                class="form-control"
                                placeholder="Contoh: MAGANG-AB12CD34"
                                autocomplete="off"
                                required
                            >

                        </div>


                        <small class="text-muted d-block mt-2">

                            Gunakan kode pengajuan yang Anda terima
                            setelah mengirim formulir.

                        </small>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100 py-3 mt-3"
                        id="btnCekHasil"
                    >

                        <i class="bi bi-search me-2"></i>

                        Cek Status Pengajuan

                    </button>

                </form>


                {{-- ========================================================= --}}
                {{-- RESULT --}}
                {{-- ========================================================= --}}

                <div
                    id="hasilPengajuan"
                    class="mt-4"
                    style="display:none;"
                >
                </div>

            </div>

        </div>

    </div>

</div>
```

</section>

@endsection

@push('js')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CEK HASIL PENGAJUAN
    |--------------------------------------------------------------------------
    */

    $('#formHasil')
        .off('submit.hasil')
        .on('submit.hasil', function (e) {

            e.preventDefault();


            const form = this;

            const button = $('#btnCekHasil');

            const hasilContainer = $('#hasilPengajuan');


            /*
            |--------------------------------------------------------------------------
            | Batalkan request sebelumnya jika masih berjalan
            |--------------------------------------------------------------------------
            */

            if (
                window.requestCekHasil &&
                window.requestCekHasil.readyState !== 4
            ) {

                window.requestCekHasil.abort();

            }


            /*
            |--------------------------------------------------------------------------
            | Reset hasil sebelumnya
            |--------------------------------------------------------------------------
            */

            hasilContainer
                .stop(true, true)
                .hide()
                .empty();


            /*
            |--------------------------------------------------------------------------
            | Loading
            |--------------------------------------------------------------------------
            */

            button
                .prop('disabled', true)
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true">
                    </span>
                    Memeriksa...
                `);


            const formData = new FormData(form);


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            window.requestCekHasil = $.ajax({

                url: '/api/public/hasil',

                type: 'POST',

                data: formData,

                processData: false,

                contentType: false,


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                success: function (response) {

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan response valid
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !response ||
                        response.success !== true ||
                        !response.data
                    ) {

                        return;

                    }


                    const data = response.data;


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    let badge = '';

                    let loginNote = '';

                    const status = String(
                        data.status || ''
                    ).toLowerCase();


                    if (
                        status === 'menunggu' ||
                        status === 'pending'
                    ) {

                        badge = `
                            <span class="badge bg-warning text-dark">
                                Menunggu
                            </span>
                        `;

                    }

                    else if (
                        status === 'diterima' ||
                        status === 'accepted'
                    ) {

                        badge = `
                            <span class="badge bg-success">
                                Diterima
                            </span>
                        `;


                        loginNote = `

                            <div class="alert alert-info mb-4">

                                <strong>
                                    Informasi Login Peserta
                                </strong>

                                <ul class="mb-0 mt-2">

                                    <li>
                                        Ketua dan seluruh anggota menggunakan
                                        <strong>email masing-masing</strong>
                                        yang didaftarkan saat pengajuan.
                                    </li>

                                    <li>
                                        Password awal seluruh peserta adalah
                                        <strong>${data.kode_pengajuan}</strong>.
                                    </li>

                                    <li>
                                        Pada login pertama, setiap peserta wajib
                                        mengganti password sebelum dapat menggunakan
                                        website.
                                    </li>

                                </ul>

                            </div>

                        `;

                    }

                    else if (
                        status === 'ditolak' ||
                        status === 'rejected'
                    ) {

                        badge = `
                            <span class="badge bg-danger">
                                Ditolak
                            </span>
                        `;

                    }

                    else {

                        badge = `
                            <span class="badge bg-secondary">
                                Tidak diketahui
                            </span>
                        `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ANGGOTA
                    |--------------------------------------------------------------------------
                    */

                    let anggota = '';

                    const anggotaList = Array.isArray(data.anggota)
                        ? data.anggota
                        : [];


                    if (anggotaList.length === 0) {

                        anggota = `
                            <li>
                                Tidak ada anggota
                            </li>
                        `;

                    } else {

                        anggotaList.forEach(function (item) {

                            anggota += `
                                <li>
                                    ${item.nama_anggota || '-'}
                                </li>
                            `;

                        });

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TAMPILKAN HASIL
                    |--------------------------------------------------------------------------
                    */

                    hasilContainer.html(`

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                ${loginNote}

                                <h4 class="mb-4">

                                    Status ${badge}

                                </h4>


                                <table class="table">

                                    <tr>

                                        <th width="35%">
                                            Kode Pengajuan
                                        </th>

                                        <td>
                                            ${data.kode_pengajuan || '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <th>
                                            Ketua
                                        </th>

                                        <td>
                                            ${data.nama_ketua || '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <th>
                                            Universitas
                                        </th>

                                        <td>
                                            ${data.universitas || '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <th>
                                            Tanggal Mulai
                                        </th>

                                        <td>
                                            ${data.tanggal_mulai || '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <th>
                                            Tanggal Selesai
                                        </th>

                                        <td>
                                            ${data.tanggal_selesai || '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <th>
                                            Anggota
                                        </th>

                                        <td>

                                            <ul class="mb-0">

                                                ${anggota}

                                            </ul>

                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                    `);


                    hasilContainer
                        .stop(true, true)
                        .fadeIn(200);

                },


                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                error: function (xhr, textStatus) {

                    /*
                    |--------------------------------------------------------------------------
                    | Jangan tampilkan apa pun kalau request dibatalkan
                    |--------------------------------------------------------------------------
                    */

                    if (textStatus === 'abort') {

                        return;

                    }


                    const response = xhr.responseJSON || {};

                    const type = response.type || '';


                    /*
                    |--------------------------------------------------------------------------
                    | KODE SUDAH TIDAK AKTIF
                    |--------------------------------------------------------------------------
                    */

                    if (
                        xhr.status === 410 &&
                        type === 'inactive'
                    ) {

                        Swal.fire({

                            icon: 'info',

                            title: 'Kode Pengajuan Sudah Tidak Aktif',

                            html: `

                                <div class="text-center">

                                    <p class="mb-3">
                                        Kode pengajuan ini sudah tidak dapat digunakan
                                        untuk melihat informasi hasil pengajuan.
                                    </p>

                                    <p class="mb-0">
                                        Apabila ingin mengajukan magang kembali,
                                        silakan melakukan
                                        <strong>pengajuan baru</strong>
                                        melalui halaman pengajuan.
                                    </p>

                                </div>

                            `,

                            confirmButtonText: 'Saya Mengerti',

                            confirmButtonColor: '#0d6efd',

                            allowOutsideClick: false,

                            allowEscapeKey: false

                        });

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | KODE TIDAK PERNAH ADA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        xhr.status === 404 &&
                        type === 'not_found'
                    ) {

                        Swal.fire({

                            icon: 'warning',

                            title: 'Kode Pengajuan Tidak Ditemukan',

                            text:
                                'Silakan periksa kembali kode pengajuan yang Anda masukkan.',

                            confirmButtonText: 'Coba Lagi',

                            confirmButtonColor: '#0d6efd'

                        });

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR LAIN
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({

                        icon: 'error',

                        title: 'Terjadi Kesalahan',

                        text:
                            'Silakan coba beberapa saat lagi.',

                        confirmButtonText: 'OK'

                    });

                },


                /*
                |--------------------------------------------------------------------------
                | SELESAI
                |--------------------------------------------------------------------------
                */

                complete: function () {

                    button
                        .prop('disabled', false)
                        .html(`
                            <i class="bi bi-search me-2"></i>
                            Cek Status Pengajuan
                        `);


                    window.requestCekHasil = null;

                }

            });

        });

});

</script>

@endpush