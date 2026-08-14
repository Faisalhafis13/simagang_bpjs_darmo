@extends('layouts.public')

@section('title', 'Lihat Hasil')

@section('public-content')

<style>
    /* =========================================================
       HALAMAN HASIL
    ========================================================= */

    .hasil-card {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .06);
    }

    .hasil-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
        font-size: 28px;
    }

    /* =========================================================
       KARTU PENGAJUAN
    ========================================================= */

    .kartu-pengajuan {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
    }

    .kartu-header {
        background: linear-gradient(
            135deg,
            #0d6efd,
            #0b5ed7
        );
        color: #fff;
        padding: 28px;
    }

    .kartu-header-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .08em;
        opacity: .85;
        margin-bottom: 6px;
    }

    .kode-pengajuan {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: .04em;
        word-break: break-word;
    }

    .status-badge-kartu {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .25);
    }

    .kartu-body {
        padding: 28px;
    }

    .section-kartu {
        margin-bottom: 28px;
    }

    .section-kartu:last-child {
        margin-bottom: 0;
    }

    .section-kartu-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .section-kartu-title-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
    }

    .info-box-kartu {
        height: 100%;
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        background: #f8f9fa;
    }

    .info-box-label {
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .info-box-value {
        font-weight: 700;
        word-break: break-word;
    }

    /* =========================================================
       KETUA
    ========================================================= */

    .ketua-box {
        border: 1px solid #e9ecef;
        border-radius: 18px;
        padding: 18px;
        background: #fff;
    }

    .ketua-avatar {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
    }

    /* =========================================================
       ANGGOTA
    ========================================================= */

    .anggota-table-wrapper {
        border: 1px solid #e9ecef;
        border-radius: 18px;
        overflow: hidden;
    }

    .anggota-table {
        margin-bottom: 0;
    }

    .anggota-table thead th {
        background: #f8f9fa;
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .anggota-table tbody td {
        font-size: 13px;
    }

    .nomor-anggota {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .08);
        color: #0d6efd;
        font-weight: 700;
    }

    /* =========================================================
       FOOTER KARTU
    ========================================================= */

    .kartu-footer {
        border-top: 1px dashed #dee2e6;
        padding-top: 20px;
        margin-top: 25px;
    }

    .kode-note {
        padding: 15px;
        border-radius: 16px;
        background: #fff8e1;
        border: 1px solid #ffe69c;
        color: #664d03;
    }

    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        body {
            background: #fff !important;
        }

        body * {
            visibility: hidden;
        }

        #printArea,
        #printArea * {
            visibility: visible;
        }

        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        .kartu-pengajuan {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        .kartu-header {
            background: #0d6efd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .info-box-kartu {
            background: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 576px) {

        .hasil-card {
            padding: 20px;
            border-radius: 18px;
        }

        .kartu-header,
        .kartu-body {
            padding: 20px;
        }

        .kode-pengajuan {
            font-size: 20px;
        }

        .anggota-table-wrapper {
            overflow-x: auto;
        }

    }
</style>


<section class="hasil-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8 col-xl-8">

                <div class="hasil-card">

                    {{-- =====================================================
                        HEADER
                    ====================================================== --}}

                    <div class="text-center mb-4 no-print">

                        <div class="hasil-icon mb-4">
                            <i class="bi bi-search"></i>
                        </div>

                        <span class="badge-system mb-3">
                            Status Pengajuan
                        </span>

                        <h2 class="fw-bold mb-3">
                            Lihat Hasil Pengajuan
                        </h2>

                        <p class="text-muted mb-0">
                            Masukkan kode pengajuan untuk melihat
                            kartu pengajuan dan status magang Anda.
                        </p>

                    </div>


                    {{-- =====================================================
                        FORM PENCARIAN
                    ====================================================== --}}

                    <form
                        id="formHasil"
                        class="no-print"
                    >

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


                    {{-- =====================================================
                        HASIL PENGAJUAN
                    ====================================================== --}}

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

</section>

@endsection


@push('js')

<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | FORM CEK HASIL
    |--------------------------------------------------------------------------
    */

    $('#formHasil').on('submit', function (e) {

        e.preventDefault();

        const form = $(this);

        const button = $('#btnCekHasil');

        const kode = $.trim(
            $('#kode_pengajuan').val()
        );


        if (!kode) {

            Swal.fire({

                icon: 'warning',

                title: 'Kode Belum Diisi',

                text: 'Silakan masukkan kode pengajuan Anda.',

                confirmButtonText: 'OK'

            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LOADING
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

                Mencari Pengajuan...

            `);


        $('#hasilPengajuan')
            .hide()
            .html('');


        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('hasil.cari') }}",

            type: 'POST',

            data: {

                _token:
                    "{{ csrf_token() }}",

                kode_pengajuan:
                    kode

            },

            dataType: 'json',

            success: function (response) {

                if (
                    !response.success ||
                    !response.data
                ) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Data Tidak Ditemukan',

                        text:
                            response.message
                            ||
                            'Pengajuan tidak ditemukan.',

                        confirmButtonText: 'Coba Lagi'

                    });

                    return;
                }


                renderKartuPengajuan(
                    response.data
                );

            },

            error: function (xhr) {

                let message =
                    'Terjadi kesalahan saat mencari pengajuan.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                if (xhr.status === 404) {

                    message =
                        'Kode pengajuan tidak ditemukan. Pastikan kode yang Anda masukkan sudah benar.';

                }


                Swal.fire({

                    icon: 'error',

                    title: 'Pengajuan Tidak Ditemukan',

                    text: message,

                    confirmButtonText: 'Coba Lagi'

                });

            },

            complete: function () {

                button
                    .prop('disabled', false)
                    .html(`

                        <i class="bi bi-search me-2"></i>

                        Cek Status Pengajuan

                    `);

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | RENDER KARTU PENGAJUAN
    |--------------------------------------------------------------------------
    */

    function renderKartuPengajuan(data)
    {

        const anggota =
            Array.isArray(data.anggota)
                ? data.anggota
                : [];


        const totalPeserta =
            1 + anggota.length;


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        const status =
            data.status || 'Pending';


        let statusIcon =
            'bi-hourglass-split';


        let statusClass =
            'bg-warning text-dark';


        let statusText =
            status;


        if (
            status === 'Diterima' ||
            status === 'diterima' ||
            status === 'Accepted' ||
            status === 'accepted'
        ) {

            statusIcon =
                'bi-check-circle-fill';

            statusClass =
                'bg-success';

            statusText =
                'Diterima';

        }
        else if (
            status === 'Ditolak' ||
            status === 'ditolak' ||
            status === 'Rejected' ||
            status === 'rejected'
        ) {

            statusIcon =
                'bi-x-circle-fill';

            statusClass =
                'bg-danger';

            statusText =
                'Ditolak';

        }
        else {

            statusIcon =
                'bi-hourglass-split';

            statusClass =
                'bg-warning text-dark';

            statusText =
                'Menunggu';

        }


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA
        |--------------------------------------------------------------------------
        */

        let anggotaHtml = '';


        if (anggota.length > 0) {

            anggota.forEach(function (
                item,
                index
            ) {

                anggotaHtml += `

                    <tr>

                        <td class="text-center">

                            <span class="nomor-anggota">

                                ${index + 2}

                            </span>

                        </td>

                        <td>

                            <div class="fw-semibold">

                                ${escapeHtml(
                                    item.nama_anggota
                                    || '-'
                                )}

                            </div>

                        </td>

                        <td>

                            <span class="text-muted">

                                ${escapeHtml(
                                    item.email
                                    || '-'
                                )}

                            </span>

                        </td>

                        <td>

                            ${escapeHtml(
                                item.no_hp
                                || '-'
                            )}

                        </td>

                        <td class="text-center">

                            <span
                                class="badge rounded-pill bg-light text-dark border"
                            >

                                <i class="bi bi-person me-1"></i>

                                Anggota

                            </span>

                        </td>

                    </tr>

                `;

            });

        }
        else {

            anggotaHtml = `

                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted py-4"
                    >

                        Tidak ada anggota tambahan.

                    </td>

                </tr>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | KARTU
        |--------------------------------------------------------------------------
        */

        const html = `

            <div
                id="printArea"
                class="kartu-pengajuan"
            >

                {{-- HEADER KARTU --}}

                <div class="kartu-header">

                    <div
                        class="d-flex flex-wrap justify-content-between
                               align-items-start gap-3"
                    >

                        <div>

                            <div class="kartu-header-title">

                                Kartu Pengajuan Magang

                            </div>

                            <div class="kode-pengajuan">

                                ${escapeHtml(
                                    data.kode_pengajuan
                                    || '-'
                                )}

                            </div>

                        </div>


                        <div>

                            <span class="status-badge-kartu">

                                <i
                                    class="bi ${statusIcon}"
                                ></i>

                                ${escapeHtml(statusText)}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- BODY --}}

                <div class="kartu-body">

                    {{-- INFORMASI PENGAJUAN --}}

                    <div class="section-kartu">

                        <div class="section-kartu-title">

                            <div class="section-kartu-title-icon">

                                <i class="bi bi-file-earmark-text"></i>

                            </div>

                            <div>

                                Informasi Pengajuan

                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="info-box-kartu">

                                    <div class="info-box-label">

                                        Perguruan Tinggi

                                    </div>

                                    <div class="info-box-value">

                                        ${escapeHtml(
                                            data.universitas
                                            || '-'
                                        )}

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="info-box-kartu">

                                    <div class="info-box-label">

                                        Semester

                                    </div>

                                    <div class="info-box-value">

                                        ${escapeHtml(
                                            data.semester
                                            || '-'
                                        )}

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="info-box-kartu">

                                    <div class="info-box-label">

                                        Tanggal Mulai

                                    </div>

                                    <div class="info-box-value">

                                        ${formatTanggal(
                                            data.tanggal_mulai
                                        )}

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="info-box-kartu">

                                    <div class="info-box-label">

                                        Tanggal Selesai

                                    </div>

                                    <div class="info-box-value">

                                        ${formatTanggal(
                                            data.tanggal_selesai
                                        )}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- KETUA --}}

                    <div class="section-kartu">

                        <div class="section-kartu-title">

                            <div class="section-kartu-title-icon">

                                <i class="bi bi-person-badge"></i>

                            </div>

                            <div>

                                Ketua Kelompok

                            </div>

                        </div>


                        <div class="ketua-box">

                            <div class="d-flex align-items-center">

                                <div class="ketua-avatar me-3">

                                    <i class="bi bi-person-check"></i>

                                </div>

                                <div>

                                    <div class="fw-bold">

                                        ${escapeHtml(
                                            data.nama_ketua
                                            || '-'
                                        )}

                                    </div>

                                    <div class="text-muted small">

                                        ${escapeHtml(
                                            data.email_ketua
                                            || '-'
                                        )}

                                    </div>

                                    <div class="text-muted small mt-1">

                                        <i class="bi bi-phone me-1"></i>

                                        ${escapeHtml(
                                            data.no_hp
                                            || '-'
                                        )}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SELURUH PESERTA --}}

                    <div class="section-kartu">

                        <div
                            class="d-flex justify-content-between
                                   align-items-center mb-3"
                        >

                            <div
                                class="section-kartu-title mb-0"
                            >

                                <div class="section-kartu-title-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                                <div>

                                    Peserta Kelompok

                                </div>

                            </div>


                            <span
                                class="badge rounded-pill
                                       bg-light text-dark border px-3 py-2"
                            >

                                <i class="bi bi-people me-1"></i>

                                ${totalPeserta} Orang

                            </span>

                        </div>


                        <div class="anggota-table-wrapper">

                            <div class="table-responsive">

                                <table
                                    class="table anggota-table
                                           table-hover align-middle"
                                >

                                    <thead>

                                        <tr>

                                            <th
                                                class="text-center"
                                                style="width:60px;"
                                            >
                                                No
                                            </th>

                                            <th>
                                                Nama Peserta
                                            </th>

                                            <th>
                                                Email
                                            </th>

                                            <th>
                                                No. HP
                                            </th>

                                            <th
                                                class="text-center"
                                            >
                                                Peran
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        {{-- KETUA --}}

                                        <tr>

                                            <td class="text-center">

                                                <span
                                                    class="nomor-anggota"
                                                >
                                                    1
                                                </span>

                                            </td>

                                            <td>

                                                <div class="fw-semibold">

                                                    ${escapeHtml(
                                                        data.nama_ketua
                                                        || '-'
                                                    )}

                                                </div>

                                            </td>

                                            <td>

                                                <span
                                                    class="text-muted"
                                                >

                                                    ${escapeHtml(
                                                        data.email_ketua
                                                        || '-'
                                                    )}

                                                </span>

                                            </td>

                                            <td>

                                                ${escapeHtml(
                                                    data.no_hp
                                                    || '-'
                                                )}

                                            </td>

                                            <td
                                                class="text-center"
                                            >

                                                <span
                                                    class="badge
                                                           rounded-pill
                                                           bg-primary
                                                           bg-opacity-10
                                                           text-primary
                                                           border
                                                           border-primary"
                                                >

                                                    <i
                                                        class="bi
                                                               bi-person-check
                                                               me-1"
                                                    ></i>

                                                    Ketua

                                                </span>

                                            </td>

                                        </tr>


                                        ${anggotaHtml}

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    {{-- CATATAN --}}

                    ${
                        data.catatan
                        ? `

                            <div class="section-kartu">

                                <div class="section-kartu-title">

                                    <div
                                        class="section-kartu-title-icon"
                                    >

                                        <i
                                            class="bi bi-chat-left-text"
                                        ></i>

                                    </div>

                                    <div>

                                        Catatan

                                    </div>

                                </div>


                                <div
                                    class="alert
                                           alert-light
                                           border
                                           rounded-4
                                           mb-0"
                                >

                                    <div class="small">

                                        ${escapeHtml(
                                            data.catatan
                                        )}

                                    </div>

                                </div>

                            </div>

                        `
                        : ''
                    }


                    {{-- FOOTER --}}

                    <div class="kartu-footer">

                        <div class="kode-note">

                            <div class="d-flex">

                                <i
                                    class="bi bi-shield-check
                                           fs-5 me-3"
                                ></i>

                                <div class="small">

                                    <div class="fw-bold mb-1">

                                        Simpan Kode Pengajuan Anda

                                    </div>

                                    <div>

                                        Gunakan kode

                                        <strong>
                                            ${escapeHtml(
                                                data.kode_pengajuan
                                                || '-'
                                            )}
                                        </strong>

                                        untuk melihat kembali
                                        status pengajuan Anda.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- BUTTON PRINT --}}

                    <div
                        class="d-flex flex-wrap gap-2 mt-4 no-print"
                    >

                        <button
                            type="button"
                            class="btn btn-primary flex-grow-1"
                            onclick="window.print()"
                        >

                            <i
                                class="bi bi-printer me-2"
                            ></i>

                            Cetak / Simpan PDF

                        </button>


                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="btnResetHasil"
                        >

                            <i
                                class="bi bi-arrow-left me-2"
                            ></i>

                            Cari Kode Lain

                        </button>

                    </div>

                </div>

            </div>

        `;


        $('#hasilPengajuan')
            .html(html)
            .fadeIn(250);


        /*
        |--------------------------------------------------------------------------
        | SCROLL KE KARTU
        |--------------------------------------------------------------------------
        */

        $('html, body').animate({

            scrollTop:
                $('#hasilPengajuan').offset().top - 30

        }, 500);


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        $('#btnResetHasil').on(
            'click',
            function () {

                $('#hasilPengajuan')
                    .slideUp(
                        200,
                        function () {

                            $(this)
                                .html('')
                                .show();

                        }
                    );

                $('#kode_pengajuan')
                    .val('')
                    .focus();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT TANGGAL INDONESIA
    |--------------------------------------------------------------------------
    */

    function formatTanggal(tanggal)
    {

        if (!tanggal) {

            return '-';

        }


        const date =
            new Date(tanggal);


        if (
            isNaN(
                date.getTime()
            )
        ) {

            return escapeHtml(tanggal);

        }


        return date.toLocaleDateString(
            'id-ID',
            {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(text)
    {

        return String(text ?? '')

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }

});

</script>

@endpush