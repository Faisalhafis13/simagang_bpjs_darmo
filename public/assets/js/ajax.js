/*
|--------------------------------------------------------------------------
| POST DATA HELPER
|--------------------------------------------------------------------------
*/

function postData(url, data, successCallback, errorCallback = null) {

    $.ajax({

        url: url,

        type: 'POST',

        data: data,

        processData: false,

        contentType: false,

        success: successCallback,

        error: errorCallback

    });

}


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | VALIDASI FORM PENGAJUAN
    |--------------------------------------------------------------------------
    */

    function validatePengajuanForm() {

        let errors = [];


        /*
        |--------------------------------------------------------------------------
        | DATA KETUA
        |--------------------------------------------------------------------------
        */

        const namaKetua = $.trim(
            $('input[name="nama_ketua"]').val()
        );

        const universitas = $.trim(
            $('input[name="universitas"]').val()
        );

        const semester = $.trim(
            $('input[name="semester"]').val()
        );

        const noHp = $.trim(
            $('input[name="no_hp"]').val()
        );

        const emailKetua = $.trim(
            $('input[name="email_ketua"]').val()
        );

        const tanggalMulai = $.trim(
            $('input[name="tanggal_mulai"]').val()
        );

        const tanggalSelesai = $.trim(
            $('input[name="tanggal_selesai"]').val()
        );

        const proposal = $(
            'input[name="proposal"]'
        ).val();

        const surat = $(
            'input[name="surat_permohonan"]'
        ).val();


        /*
        |--------------------------------------------------------------------------
        | EMAIL REGEX
        |--------------------------------------------------------------------------
        */

        const emailRegex =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA KETUA
        |--------------------------------------------------------------------------
        */

        if (!namaKetua) {

            errors.push(
                'Nama ketua wajib diisi.'
            );

        }


        if (!universitas) {

            errors.push(
                'Universitas wajib diisi.'
            );

        }


        if (!semester) {

            errors.push(
                'Semester wajib diisi.'
            );

        }


        if (!noHp) {

            errors.push(
                'Nomor HP ketua wajib diisi.'
            );

        }


        if (!emailKetua) {

            errors.push(
                'Email ketua wajib diisi.'
            );

        } else if (
            !emailRegex.test(emailKetua)
        ) {

            errors.push(
                'Email ketua tidak valid.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI TANGGAL
        |--------------------------------------------------------------------------
        */

        if (!tanggalMulai) {

            errors.push(
                'Tanggal mulai wajib diisi.'
            );

        }


        if (!tanggalSelesai) {

            errors.push(
                'Tanggal selesai wajib diisi.'
            );

        }


        if (
            tanggalMulai &&
            tanggalSelesai &&
            new Date(tanggalSelesai) <
            new Date(tanggalMulai)
        ) {

            errors.push(
                'Tanggal selesai harus sama dengan atau setelah tanggal mulai.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DOKUMEN
        |--------------------------------------------------------------------------
        */

        if (!proposal) {

            errors.push(
                'Proposal wajib diunggah.'
            );

        }


        if (!surat) {

            errors.push(
                'Surat permohonan wajib diunggah.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI ANGGOTA
        |--------------------------------------------------------------------------
        */

        $('#anggota-wrapper .anggota-item').each(
            function (index) {

                const itemIndex =
                    index + 1;


                const namaAnggota = $.trim(
                    $(this)
                        .find(
                            'input[name*="[nama]"]'
                        )
                        .val()
                );


                const emailAnggota = $.trim(
                    $(this)
                        .find(
                            'input[name*="[email]"]'
                        )
                        .val()
                );


                const noHpAnggota = $.trim(
                    $(this)
                        .find(
                            'input[name*="[no_hp]"]'
                        )
                        .val()
                );


                if (!namaAnggota) {

                    errors.push(
                        `Nama anggota ${itemIndex} wajib diisi.`
                    );

                }


                if (!emailAnggota) {

                    errors.push(
                        `Email anggota ${itemIndex} wajib diisi.`
                    );

                } else if (
                    !emailRegex.test(emailAnggota)
                ) {

                    errors.push(
                        `Email anggota ${itemIndex} tidak valid.`
                    );

                }


                if (!noHpAnggota) {

                    errors.push(
                        `Nomor HP anggota ${itemIndex} wajib diisi.`
                    );

                }

            }
        );


        return errors;

    }


    /*
    |--------------------------------------------------------------------------
    | KEMBALIKAN TOMBOL SUBMIT KE KONDISI NORMAL
    |--------------------------------------------------------------------------
    */

    function enableSubmitButton() {

        const submitButton =
            $('#formPengajuan').find(
                'button[type="submit"]'
            );


        submitButton
            .prop('disabled', false)
            .html(`

                <span>
                    Kirim Pengajuan
                </span>

                <i class="bi bi-arrow-right"></i>

            `);

    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT FORM PENGAJUAN
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'submit',
        '#formPengajuan',
        function (e) {

            e.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | AMBIL FORM
            |--------------------------------------------------------------------------
            */

            const form = this;


            /*
            |--------------------------------------------------------------------------
            | VALIDASI
            |--------------------------------------------------------------------------
            */

            const validationErrors =
                validatePengajuanForm();


            /*
            |--------------------------------------------------------------------------
            | JIKA VALIDASI GAGAL
            |--------------------------------------------------------------------------
            */

            if (
                validationErrors.length > 0
            ) {

                Swal.fire({

                    icon: 'error',

                    title: 'Validasi Gagal',

                    html:
                        validationErrors.join(
                            '<br>'
                        ),

                    confirmButtonText:
                        'Coba Lagi',

                    confirmButtonColor:
                        '#0d6efd'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | TOMBOL SUBMIT
            |--------------------------------------------------------------------------
            |
            | Begitu user klik tombol dan data lolos
            | validasi, tombol langsung berubah menjadi
            | "Memproses..."
            |
            */

            const submitButton =
                $('#formPengajuan').find(
                    'button[type="submit"]'
                );


            submitButton
                .prop('disabled', true)
                .html(`

                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true">
                    </span>

                    Memproses...

                `);


            /*
            |--------------------------------------------------------------------------
            | FORM DATA
            |--------------------------------------------------------------------------
            */

            const formData =
                new FormData(form);


            /*
            |--------------------------------------------------------------------------
            | AJAX SUBMIT
            |--------------------------------------------------------------------------
            */

            $.ajax({

                url: '/api/public/pengajuan',

                type: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                cache: false,


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                success: function (response) {

                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL DATA RESPONSE
                    |--------------------------------------------------------------------------
                    */

                    const data =
                        response.data;


                    /*
                    |--------------------------------------------------------------------------
                    | DATA ANGGOTA
                    |--------------------------------------------------------------------------
                    */

                    let anggotaHtml = '';


                    const anggotaList =
                        Array.isArray(
                            data.anggota
                        )
                            ? data.anggota
                            : [];


                    /*
                    |--------------------------------------------------------------------------
                    | JIKA TIDAK ADA ANGGOTA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        anggotaList.length === 0
                    ) {

                        anggotaHtml = `

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted"
                                >

                                    Tidak ada anggota tambahan

                                </td>

                            </tr>

                        `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | JIKA ADA ANGGOTA
                    |--------------------------------------------------------------------------
                    */

                    else {

                        anggotaList.forEach(
                            function (
                                item,
                                index
                            ) {

                                anggotaHtml += `

                                    <tr>

                                        <td>
                                            ${index + 1}
                                        </td>

                                        <td>
                                            ${item.nama_anggota || '-'}
                                        </td>

                                        <td>
                                            ${item.email || '-'}
                                        </td>

                                        <td>
                                            ${item.no_hp || '-'}
                                        </td>

                                    </tr>

                                `;

                            }
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SWAL KARTU PENGAJUAN
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({

                        icon: 'success',

                        title: 'Pengajuan Berhasil',

                        width: '850px',

                        html: `

                            <div
                                id="kartuPengajuan"
                                style="
                                    text-align:left;
                                    background:#fff;
                                    border:1px solid #dee2e6;
                                    border-radius:12px;
                                    padding:25px;
                                    margin-top:15px;
                                "
                            >

                                <!-- HEADER -->

                                <div
                                    style="
                                        text-align:center;
                                        border-bottom:2px solid #0F4C81;
                                        padding-bottom:15px;
                                        margin-bottom:20px;
                                    "
                                >

                                    <div
                                        style="
                                            font-size:13px;
                                            font-weight:600;
                                            color:#0F4C81;
                                            letter-spacing:1px;
                                        "
                                    >

                                        SIMAGANG

                                    </div>


                                    <h3
                                        style="
                                            margin:5px 0;
                                            font-weight:700;
                                            color:#222;
                                        "
                                    >

                                        KARTU PENGAJUAN MAGANG

                                    </h3>


                                    <small
                                        style="
                                            color:#6c757d;
                                        "
                                    >

                                        BPJS Ketenagakerjaan

                                    </small>

                                </div>


                                <!-- KODE PENGAJUAN -->

                                <div
                                    style="
                                        background:#f1f7fc;
                                        border:1px dashed #0F4C81;
                                        border-radius:10px;
                                        padding:15px;
                                        text-align:center;
                                        margin-bottom:20px;
                                    "
                                >

                                    <div
                                        style="
                                            font-size:12px;
                                            color:#6c757d;
                                            margin-bottom:5px;
                                        "
                                    >

                                        KODE PENGAJUAN

                                    </div>


                                    <div
                                        style="
                                            font-size:24px;
                                            font-weight:800;
                                            color:#0F4C81;
                                            letter-spacing:2px;
                                        "
                                    >

                                        ${data.kode_pengajuan || '-'}

                                    </div>


                                    <small
                                        style="
                                            color:#6c757d;
                                        "
                                    >

                                        Simpan kartu ini untuk
                                        keperluan pengecekan pengajuan.

                                    </small>

                                </div>


                                <!-- DATA PENGAJUAN -->

                                <table
                                    style="
                                        width:100%;
                                        border-collapse:collapse;
                                        margin-bottom:20px;
                                        font-size:14px;
                                    "
                                >

                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                width:35%;
                                                font-weight:600;
                                            "
                                        >

                                            Nama Ketua

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.nama_ketua || '-'}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                font-weight:600;
                                            "
                                        >

                                            Universitas

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.universitas || '-'}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                font-weight:600;
                                            "
                                        >

                                            Semester

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.semester || '-'}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                font-weight:600;
                                            "
                                        >

                                            Email Ketua

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.email_ketua || '-'}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                font-weight:600;
                                            "
                                        >

                                            Nomor HP

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.no_hp || '-'}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td
                                            style="
                                                padding:8px 0;
                                                font-weight:600;
                                            "
                                        >

                                            Periode Magang

                                        </td>


                                        <td
                                            style="
                                                padding:8px 0;
                                            "
                                        >

                                            ${data.tanggal_mulai || '-'}
                                            s/d
                                            ${data.tanggal_selesai || '-'}

                                        </td>

                                    </tr>

                                </table>


                                <!-- DATA ANGGOTA -->

                                <div
                                    style="
                                        font-weight:700;
                                        font-size:15px;
                                        margin-bottom:10px;
                                        color:#0F4C81;
                                    "
                                >

                                    Data Anggota

                                </div>


                                <div
                                    style="
                                        overflow-x:auto;
                                    "
                                >

                                    <table
                                        style="
                                            width:100%;
                                            border-collapse:collapse;
                                            font-size:13px;
                                        "
                                    >

                                        <thead>

                                            <tr
                                                style="
                                                    background:#f8f9fa;
                                                "
                                            >

                                                <th
                                                    style="
                                                        border:1px solid #dee2e6;
                                                        padding:8px;
                                                        text-align:center;
                                                    "
                                                >

                                                    No

                                                </th>


                                                <th
                                                    style="
                                                        border:1px solid #dee2e6;
                                                        padding:8px;
                                                        text-align:left;
                                                    "
                                                >

                                                    Nama

                                                </th>


                                                <th
                                                    style="
                                                        border:1px solid #dee2e6;
                                                        padding:8px;
                                                        text-align:left;
                                                    "
                                                >

                                                    Email

                                                </th>


                                                <th
                                                    style="
                                                        border:1px solid #dee2e6;
                                                        padding:8px;
                                                        text-align:left;
                                                    "
                                                >

                                                    No. HP

                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            ${anggotaHtml}

                                        </tbody>

                                    </table>

                                </div>


                                <!-- STATUS -->

                                <div
                                    style="
                                        margin-top:20px;
                                        padding:12px;
                                        background:#fff8e1;
                                        border-radius:8px;
                                        font-size:13px;
                                    "
                                >

                                    <strong>
                                        Status Pengajuan:
                                    </strong>


                                    <span
                                        style="
                                            color:#856404;
                                            font-weight:700;
                                        "
                                    >

                                        ${data.status || 'Pending'}

                                    </span>

                                </div>


                                <!-- FOOTER -->

                                <div
                                    style="
                                        margin-top:20px;
                                        text-align:center;
                                        font-size:11px;
                                        color:#6c757d;
                                    "
                                >

                                    Kartu ini merupakan bukti bahwa
                                    pengajuan magang telah berhasil
                                    dikirim melalui sistem SIMAGANG.

                                </div>

                            </div>

                        `,

                        showCancelButton: true,

                        confirmButtonText: `

                            <i class="bi bi-printer me-1"></i>

                            Cetak Kartu

                        `,

                        cancelButtonText:
                            'Tutup',

                        reverseButtons: true,

                        confirmButtonColor:
                            '#0d6efd'

                    }).then(
                        function (result) {

                            /*
                            |--------------------------------------------------------------------------
                            | CETAK KARTU
                            |--------------------------------------------------------------------------
                            */

                            if (
                                result.isConfirmed
                            ) {

                                cetakKartuPengajuan(
                                    data
                                );

                            }

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RESET FORM
                    |--------------------------------------------------------------------------
                    */

                    $('#formPengajuan')[0]
                        .reset();


                    $('#anggota-wrapper')
                        .empty();


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE MEMBER STATE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        typeof updateMemberState ===
                        'function'
                    ) {

                        updateMemberState();

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | KEMBALIKAN TOMBOL
                    |--------------------------------------------------------------------------
                    */

                    enableSubmitButton();

                },


                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                error: function (xhr) {

                    /*
                    |--------------------------------------------------------------------------
                    | KEMBALIKAN TOMBOL
                    |--------------------------------------------------------------------------
                    */

                    enableSubmitButton();


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDATION ERROR 422
                    |--------------------------------------------------------------------------
                    */

                    if (
                        xhr.status === 422
                    ) {

                        let pesan = '';


                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.errors
                        ) {

                            $.each(
                                xhr.responseJSON.errors,
                                function (
                                    key,
                                    value
                                ) {

                                    if (
                                        Array.isArray(
                                            value
                                        )
                                    ) {

                                        pesan +=
                                            value[0] +
                                            '<br>';

                                    } else {

                                        pesan +=
                                            value +
                                            '<br>';

                                    }

                                }
                            );

                        } else {

                            pesan =
                                'Data yang dikirim tidak valid.';

                        }


                        Swal.fire({

                            icon: 'error',

                            title:
                                'Validasi Gagal',

                            html: pesan,

                            confirmButtonText:
                                'Coba Lagi',

                            confirmButtonColor:
                                '#0d6efd'

                        });

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR SERVER
                    |--------------------------------------------------------------------------
                    */

                    else {

                        Swal.fire({

                            icon: 'error',

                            title:
                                'Terjadi Kesalahan',

                            text:
                                'Silakan coba beberapa saat lagi.',

                            confirmButtonText:
                                'Coba Lagi',

                            confirmButtonColor:
                                '#0d6efd'

                        });

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | COMPLETE
                |--------------------------------------------------------------------------
                |
                | Request AJAX selesai.
                |
                | Tidak mengaktifkan tombol di sini karena
                | success dan error sudah mengaturnya.
                |
                */

                complete: function () {

                    console.log(
                        'Request pengajuan selesai.'
                    );

                }

            });

        }

    );

});


/*
|--------------------------------------------------------------------------
| CETAK KARTU PENGAJUAN
|--------------------------------------------------------------------------
*/

function cetakKartuPengajuan(data) {

    let anggotaHtml = '';


    /*
    |--------------------------------------------------------------------------
    | DATA ANGGOTA
    |--------------------------------------------------------------------------
    */

    const anggotaList =
        Array.isArray(
            data.anggota
        )
            ? data.anggota
            : [];


    /*
    |--------------------------------------------------------------------------
    | JIKA TIDAK ADA ANGGOTA
    |--------------------------------------------------------------------------
    */

    if (
        anggotaList.length === 0
    ) {

        anggotaHtml = `

            <tr>

                <td
                    colspan="4"
                    style="
                        text-align:center;
                    "
                >

                    Tidak ada anggota tambahan

                </td>

            </tr>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | JIKA ADA ANGGOTA
    |--------------------------------------------------------------------------
    */

    else {

        anggotaList.forEach(
            function (
                item,
                index
            ) {

                anggotaHtml += `

                    <tr>

                        <td>
                            ${index + 1}
                        </td>

                        <td>
                            ${item.nama_anggota || '-'}
                        </td>

                        <td>
                            ${item.email || '-'}
                        </td>

                        <td>
                            ${item.no_hp || '-'}
                        </td>

                    </tr>

                `;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | OPEN PRINT WINDOW
    |--------------------------------------------------------------------------
    */

    const printWindow =
        window.open(
            '',
            '_blank'
        );


    /*
    |--------------------------------------------------------------------------
    | POPUP DIBLOKIR
    |--------------------------------------------------------------------------
    */

    if (!printWindow) {

        Swal.fire({

            icon:
                'warning',

            title:
                'Popup Diblokir',

            text:
                'Browser memblokir jendela cetak. ' +
                'Silakan izinkan popup untuk website ini.',

            confirmButtonText:
                'OK',

            confirmButtonColor:
                '#0d6efd'

        });

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | PRINT DOCUMENT
    |--------------------------------------------------------------------------
    */

    printWindow.document.write(`

        <!DOCTYPE html>

        <html>

        <head>

            <title>
                Kartu Pengajuan -
                ${data.kode_pengajuan || ''}
            </title>


            <meta charset="UTF-8">


            <style>

                * {
                    box-sizing: border-box;
                }


                body {

                    font-family:
                        Arial,
                        Helvetica,
                        sans-serif;

                    margin: 0;

                    padding: 30px;

                    color: #222;

                    background: #fff;

                }


                .card {

                    max-width: 800px;

                    margin: auto;

                    border: 1px solid #ccc;

                    border-radius: 12px;

                    padding: 30px;

                }


                .header {

                    text-align: center;

                    border-bottom:
                        3px solid #0F4C81;

                    padding-bottom: 18px;

                    margin-bottom: 25px;

                }


                .system {

                    color: #0F4C81;

                    font-size: 14px;

                    font-weight: bold;

                    letter-spacing: 2px;

                }


                h1 {

                    margin: 8px 0;

                    font-size: 24px;

                }


                .subtitle {

                    color: #666;

                    font-size: 13px;

                }


                .kode {

                    text-align: center;

                    border:
                        2px dashed #0F4C81;

                    border-radius: 10px;

                    padding: 18px;

                    margin-bottom: 25px;

                }


                .kode-label {

                    font-size: 12px;

                    color: #666;

                }


                .kode-value {

                    font-size: 28px;

                    font-weight: bold;

                    color: #0F4C81;

                    letter-spacing: 3px;

                    margin: 7px 0;

                }


                table {

                    width: 100%;

                    border-collapse: collapse;

                    margin-bottom: 25px;

                }


                th,
                td {

                    border:
                        1px solid #ddd;

                    padding: 9px;

                    font-size: 13px;

                }


                th {

                    background:
                        #f5f5f5;

                }


                .data-table td:first-child {

                    width: 35%;

                    font-weight: bold;

                    border: none;

                    border-bottom:
                        1px solid #eee;

                }


                .data-table td:last-child {

                    border: none;

                    border-bottom:
                        1px solid #eee;

                }


                .section-title {

                    color: #0F4C81;

                    font-weight: bold;

                    margin-bottom: 10px;

                }


                .status {

                    margin-top: 20px;

                    padding: 12px;

                    background:
                        #fff8e1;

                    border-radius: 8px;

                    font-size: 13px;

                }


                .footer {

                    text-align: center;

                    color: #777;

                    font-size: 11px;

                    margin-top: 25px;

                }


                @media print {

                    body {

                        padding: 0;

                    }


                    .card {

                        border: none;

                    }

                }

            </style>

        </head>


        <body>

            <div class="card">


                <!-- HEADER -->

                <div class="header">

                    <div class="system">

                        SIMAGANG

                    </div>


                    <h1>

                        KARTU PENGAJUAN MAGANG

                    </h1>


                    <div class="subtitle">

                        BPJS Ketenagakerjaan

                    </div>

                </div>


                <!-- KODE -->

                <div class="kode">

                    <div class="kode-label">

                        KODE PENGAJUAN

                    </div>


                    <div class="kode-value">

                        ${data.kode_pengajuan || '-'}

                    </div>


                    <div class="kode-label">

                        Simpan kode ini untuk pengecekan pengajuan.

                    </div>

                </div>


                <!-- DATA PENGAJUAN -->

                <table class="data-table">

                    <tr>

                        <td>

                            Nama Ketua

                        </td>

                        <td>

                            ${data.nama_ketua || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Universitas

                        </td>

                        <td>

                            ${data.universitas || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Semester

                        </td>

                        <td>

                            ${data.semester || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Email Ketua

                        </td>

                        <td>

                            ${data.email_ketua || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Nomor HP

                        </td>

                        <td>

                            ${data.no_hp || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Tanggal Mulai

                        </td>

                        <td>

                            ${data.tanggal_mulai || '-'}

                        </td>

                    </tr>


                    <tr>

                        <td>

                            Tanggal Selesai

                        </td>

                        <td>

                            ${data.tanggal_selesai || '-'}

                        </td>

                    </tr>

                </table>


                <!-- ANGGOTA -->

                <div class="section-title">

                    DATA ANGGOTA

                </div>


                <table>

                    <thead>

                        <tr>

                            <th>

                                No

                            </th>

                            <th>

                                Nama

                            </th>

                            <th>

                                Email

                            </th>

                            <th>

                                No. HP

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        ${anggotaHtml}

                    </tbody>

                </table>


                <!-- STATUS -->

                <div class="status">

                    <strong>

                        Status Pengajuan:

                    </strong>


                    ${data.status || 'Pending'}

                </div>


                <!-- FOOTER -->

                <div class="footer">

                    Kartu ini merupakan bukti bahwa
                    pengajuan magang telah berhasil
                    dikirim melalui sistem SIMAGANG.

                </div>


            </div>

        </body>

        </html>

    `);


    /*
    |--------------------------------------------------------------------------
    | CLOSE DOCUMENT
    |--------------------------------------------------------------------------
    */

    printWindow.document.close();


    /*
    |--------------------------------------------------------------------------
    | FOCUS
    |--------------------------------------------------------------------------
    */

    printWindow.focus();


    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    setTimeout(
        function () {

            printWindow.print();

        },
        500
    );

}