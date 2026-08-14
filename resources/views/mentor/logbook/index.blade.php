@extends('layouts.back-office')

@section('title', 'Monitoring Logbook (Mentor)')

@section('content')

<div class="card border-0 shadow-sm rounded-4">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">

        <div>
            <h4 class="fw-bold mb-1">
                Monitoring Logbook Peserta
            </h4>

            <small class="text-muted">
                Pantau, review, dan berikan feedback pada logbook peserta.
            </small>
        </div>

        <div>
            <button
                type="button"
                class="btn btn-success"
                id="btnExportExcel"
                disabled
            >
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export Excel
            </button>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BODY --}}
    {{-- ========================================================= --}}

    <div class="card-body">

        {{-- PILIH PESERTA --}}
        <div class="mb-4">

            <label
                for="peserta"
                class="form-label fw-bold"
            >
                Pilih Peserta
            </label>

            <select
                id="peserta"
                class="form-select"
            >
                <option value="">
                    -- Pilih Peserta --
                </option>
            </select>

            <div class="form-text">
                Pilih peserta untuk melihat monitoring logbook dan melakukan export.
            </div>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle w-100"
                id="tableMonitoring"
            >

                <thead class="table-light">

                    <tr>

                        <th
                            width="5%"
                            class="text-center"
                        >
                            No
                        </th>

                        <th width="10%">
                            Tanggal
                        </th>

                        <th>
                            Aktivitas
                        </th>

                        <th>
                            Hasil
                        </th>

                        <th>
                            Catatan Peserta
                        </th>

                        <th
                            width="10%"
                            class="text-center"
                        >
                            Bukti
                        </th>

                        <th
                            width="10%"
                            class="text-center"
                        >
                            Status
                        </th>

                        <th>
                            Catatan Mentor
                        </th>

                        <th
                            width="15%"
                            class="text-center"
                        >
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody id="logbookData">

                    <tr>

                        <td
                            colspan="9"
                            class="text-center text-muted py-4"
                        >
                            Pilih peserta terlebih dahulu.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL FEEDBACK --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalFeedback"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <form id="formFeedback">

                @csrf

                <input
                    type="hidden"
                    id="feedbackLogbookId"
                >

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title fw-bold mb-1">
                            Catatan Mentor
                        </h5>

                        <small class="text-muted">
                            Berikan feedback atau catatan untuk logbook peserta.
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <div class="modal-body">

                    <label
                        for="catatanMentor"
                        class="form-label fw-semibold"
                    >
                        Feedback / Catatan
                    </label>

                    <textarea
                        id="catatanMentor"
                        class="form-control"
                        rows="6"
                        maxlength="5000"
                        placeholder="Berikan feedback untuk peserta..."
                    ></textarea>

                    <div class="form-text">
                        Catatan ini akan tersimpan pada logbook peserta.
                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpanFeedback"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan Catatan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@endsection


@push('js')

<script>

let modalFeedback = null;
let selectedPesertaId = null;


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

const csrfToken =
    $('meta[name="csrf-token"]').attr('content');


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    const modalElement =
        document.getElementById('modalFeedback');

    if (modalElement) {

        modalFeedback =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

    }

    loadPeserta();

});


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {

    if (
        value === null ||
        value === undefined
    ) {

        return '-';

    }

    return $('<div>')
        .text(String(value))
        .html();

}


/*
|--------------------------------------------------------------------------
| ESCAPE ATTRIBUTE
|--------------------------------------------------------------------------
*/

function escapeAttribute(value) {

    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

}


/*
|--------------------------------------------------------------------------
| FORMAT TANGGAL
|--------------------------------------------------------------------------
|
| PENTING:
|
| Jangan menggunakan:
|
| new Date('2026-08-14')
|
| karena JavaScript dapat memperlakukannya sebagai UTC
| dan tanggal bisa bergeser di timezone Indonesia.
|
| Fungsi ini memproses tanggal database secara manual.
|
| Contoh:
|
| 2026-08-14
|       ↓
| 14-08-2026
|
| 2026-08-14 00:00:00
|       ↓
| 14-08-2026
|
| 2026-08-14T00:00:00
|       ↓
| 14-08-2026
|
| 14-08-2026
|       ↓
| 14-08-2026
|
| 14/08/2026
|       ↓
| 14-08-2026
|
*/

function formatTanggal(value) {

    if (
        value === null ||
        value === undefined
    ) {

        return '-';

    }


    const rawValue =
        String(value).trim();


    if (rawValue === '') {

        return '-';

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATABASE:
    | YYYY-MM-DD
    |--------------------------------------------------------------------------
    */

    const isoDateMatch =
        rawValue.match(
            /^(\d{4})-(\d{2})-(\d{2})(?:$|[ T])/
        );


    if (isoDateMatch) {

        const year =
            isoDateMatch[1];

        const month =
            isoDateMatch[2];

        const day =
            isoDateMatch[3];


        return `${day}-${month}-${year}`;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DD-MM-YYYY
    |--------------------------------------------------------------------------
    */

    const dmyMatch =
        rawValue.match(
            /^(\d{2})-(\d{2})-(\d{4})/
        );


    if (dmyMatch) {

        const day =
            dmyMatch[1];

        const month =
            dmyMatch[2];

        const year =
            dmyMatch[3];


        return `${day}-${month}-${year}`;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DD/MM/YYYY
    |--------------------------------------------------------------------------
    */

    const slashMatch =
        rawValue.match(
            /^(\d{2})\/(\d{2})\/(\d{4})/
        );


    if (slashMatch) {

        const day =
            slashMatch[1];

        const month =
            slashMatch[2];

        const year =
            slashMatch[3];


        return `${day}-${month}-${year}`;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT YYYY/MM/DD
    |--------------------------------------------------------------------------
    */

    const yearSlashMatch =
        rawValue.match(
            /^(\d{4})\/(\d{2})\/(\d{2})/
        );


    if (yearSlashMatch) {

        const year =
            yearSlashMatch[1];

        const month =
            yearSlashMatch[2];

        const day =
            yearSlashMatch[3];


        return `${day}-${month}-${year}`;

    }


    /*
    |--------------------------------------------------------------------------
    | FALLBACK
    |--------------------------------------------------------------------------
    |
    | Hanya digunakan jika format tanggal tidak dikenali.
    |
    */

    const date =
        new Date(rawValue);


    if (isNaN(date.getTime())) {

        return escapeHtml(rawValue);

    }


    const day =
        String(
            date.getDate()
        ).padStart(2, '0');


    const month =
        String(
            date.getMonth() + 1
        ).padStart(2, '0');


    const year =
        date.getFullYear();


    return `${day}-${month}-${year}`;

}


/*
|--------------------------------------------------------------------------
| NORMALIZE STATUS
|--------------------------------------------------------------------------
*/

function normalizeStatus(status) {

    return String(status ?? '')
        .trim()
        .toLowerCase();

}


/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function statusBadge(status) {

    const normalized =
        normalizeStatus(status);


    if (
        normalized === 'disetujui' ||
        normalized === 'approved' ||
        normalized === 'approve'
    ) {

        return `
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Disetujui
            </span>
        `;

    }


    if (
        normalized === 'ditolak' ||
        normalized === 'rejected' ||
        normalized === 'reject'
    ) {

        return `
            <span class="badge bg-danger">
                <i class="bi bi-x-circle me-1"></i>
                Ditolak
            </span>
        `;

    }


    return `
        <span class="badge bg-warning text-dark">
            <i class="bi bi-clock me-1"></i>
            Menunggu
        </span>
    `;

}


/*
|--------------------------------------------------------------------------
| LOAD PESERTA
|--------------------------------------------------------------------------
*/

async function loadPeserta() {

    const select =
        $('#peserta');


    select
        .prop('disabled', true)
        .html(`
            <option value="">
                Memuat peserta...
            </option>
        `);


    $('#btnExportExcel')
        .prop('disabled', true);


    try {

        const response =
            await fetch(
                '/mentor/logbook/peserta',
                {
                    method: 'GET',

                    headers: {
                        'Accept':
                            'application/json'
                    }
                }
            );


        const result =
            await response.json();


        if (!response.ok) {

            throw new Error(
                result.message ||
                'Gagal memuat data peserta.'
            );

        }


        const peserta =
            Array.isArray(result)
                ? result
                : (result.data || []);


        let html = `
            <option value="">
                -- Pilih Peserta --
            </option>
        `;


        if (peserta.length === 0) {

            html = `
                <option value="">
                    Belum ada peserta
                </option>
            `;

        } else {

            peserta.forEach(function (item) {

                const id =
                    escapeAttribute(item.id);


                const nama =
                    escapeHtml(
                        item.name ??
                        item.nama ??
                        item.nama_peserta ??
                        '-'
                    );


                html += `
                    <option value="${id}">
                        ${nama}
                    </option>
                `;

            });

        }


        select.html(html);


    } catch (error) {

        console.error(error);


        select.html(`
            <option value="">
                Gagal memuat peserta
            </option>
        `);


        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text:
                error.message ||
                'Gagal memuat data peserta.'
        });


    } finally {

        select.prop('disabled', false);

    }

}


/*
|--------------------------------------------------------------------------
| CHANGE PESERTA
|--------------------------------------------------------------------------
*/

$('#peserta').on(
    'change',
    function () {

        const userId =
            $(this).val();


        selectedPesertaId =
            userId || null;


        /*
        |--------------------------------------------------------------------------
        | ENABLE / DISABLE EXPORT
        |--------------------------------------------------------------------------
        */

        $('#btnExportExcel')
            .prop(
                'disabled',
                !selectedPesertaId
            );


        /*
        |--------------------------------------------------------------------------
        | RESET TABLE
        |--------------------------------------------------------------------------
        */

        if (!userId) {

            $('#logbookData').html(`
                <tr>
                    <td
                        colspan="9"
                        class="text-center text-muted py-4"
                    >
                        Pilih peserta terlebih dahulu.
                    </td>
                </tr>
            `);

            return;

        }


        loadLogbook(userId);

    }
);


/*
|--------------------------------------------------------------------------
| LOAD LOGBOOK
|--------------------------------------------------------------------------
*/

async function loadLogbook(userId) {

    $('#logbookData').html(`
        <tr>
            <td
                colspan="9"
                class="text-center py-5"
            >

                <div
                    class="spinner-border spinner-border-sm text-primary me-2"
                    role="status"
                ></div>

                <span class="text-muted">
                    Memuat logbook...
                </span>

            </td>
        </tr>
    `);


    try {

        const url =
            `/mentor/logbook/data?user_id=${encodeURIComponent(userId)}`;


        const response =
            await fetch(
                url,
                {
                    method: 'GET',

                    headers: {
                        'Accept':
                            'application/json'
                    }
                }
            );


        const result =
            await response.json();


        if (!response.ok) {

            throw new Error(
                result.message ||
                'Gagal memuat logbook.'
            );

        }


        const data =
            Array.isArray(result)
                ? result
                : (result.data || []);


        renderLogbook(data);


    } catch (error) {

        console.error(error);


        $('#logbookData').html(`
            <tr>
                <td
                    colspan="9"
                    class="text-center text-danger py-4"
                >

                    <i class="bi bi-exclamation-circle me-1"></i>

                    ${escapeHtml(
                        error.message ||
                        'Gagal memuat logbook.'
                    )}

                </td>
            </tr>
        `);

    }

}


/*
|--------------------------------------------------------------------------
| RENDER LOGBOOK
|--------------------------------------------------------------------------
*/

function renderLogbook(data) {

    if (
        !Array.isArray(data) ||
        data.length === 0
    ) {

        $('#logbookData').html(`
            <tr>
                <td
                    colspan="9"
                    class="text-center text-muted py-5"
                >

                    <i class="bi bi-journal-x fs-3 d-block mb-2"></i>

                    Belum ada logbook untuk peserta ini.

                </td>
            </tr>
        `);

        return;

    }


    let html = '';


    data.forEach(
        function (item, index) {


            const status =
                normalizeStatus(item.status);


            const approved =
                status === 'disetujui' ||
                status === 'approved' ||
                status === 'approve';


            /*
            |--------------------------------------------------------------------------
            | APPROVE BUTTON
            |--------------------------------------------------------------------------
            */

            let approveButton = '';


            if (approved) {

                approveButton = `
                    <button
                        type="button"
                        class="btn btn-sm btn-success"
                        disabled
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Disetujui
                    </button>
                `;

            } else {

                approveButton = `
                    <button
                        type="button"
                        class="btn btn-sm btn-success btn-approve"
                        data-id="${escapeAttribute(item.id)}"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Approve
                    </button>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | BUKTI
            |--------------------------------------------------------------------------
            */

            let bukti = `
                <span class="text-muted">
                    Tidak ada
                </span>
            `;


            if (item.bukti_url) {

                bukti = `
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary btn-bukti"
                        data-url="${escapeAttribute(item.bukti_url)}"
                    >
                        <i class="bi bi-image me-1"></i>
                        Lihat
                    </button>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | CATATAN MENTOR
            |--------------------------------------------------------------------------
            */

            let catatanMentor = `
                <span class="text-muted">
                    Belum ada catatan
                </span>
            `;


            if (item.catatan_mentor) {

                catatanMentor = `
                    <div
                        class="small"
                        style="
                            min-width:180px;
                            max-width:300px;
                            white-space:normal;
                            word-break:break-word;
                        "
                    >
                        ${escapeHtml(
                            item.catatan_mentor
                        )}
                    </div>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | ROW
            |--------------------------------------------------------------------------
            */

            html += `

                <tr>

                    <td class="text-center">
                        ${index + 1}
                    </td>

                    <td class="text-nowrap">
                        ${formatTanggal(
                            item.tanggal
                        )}
                    </td>

                    <td>

                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(
                                item.aktivitas
                            )}
                        </div>

                    </td>

                    <td>

                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(
                                item.hasil
                            )}
                        </div>

                    </td>

                    <td>

                        <div
                            class="small"
                            style="
                                min-width:160px;
                                max-width:280px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(
                                item.catatan
                            )}
                        </div>

                    </td>

                    <td class="text-center">
                        ${bukti}
                    </td>

                    <td class="text-center">
                        ${statusBadge(
                            item.status
                        )}
                    </td>

                    <td>
                        ${catatanMentor}
                    </td>

                    <td class="text-center">

                        <div
                            class="d-flex flex-column gap-1"
                        >

                            ${approveButton}

                            <button
                                type="button"
                                class="btn btn-sm btn-warning btn-feedback"
                                data-id="${escapeAttribute(item.id)}"
                                data-catatan="${escapeAttribute(
                                    item.catatan_mentor || ''
                                )}"
                            >
                                <i class="bi bi-chat-left-text me-1"></i>
                                Catatan
                            </button>

                        </div>

                    </td>

                </tr>

            `;

        }
    );


    $('#logbookData')
        .html(html);

}


/*
|--------------------------------------------------------------------------
| APPROVE LOGBOOK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-approve',
    async function () {

        const button =
            $(this);


        const id =
            button.data('id');


        if (!id) {

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text:
                    'ID logbook tidak ditemukan.'
            });

            return;

        }


        const confirm =
            await Swal.fire({

                icon: 'question',

                title:
                    'Approve Logbook?',

                text:
                    'Logbook ini akan ditandai sebagai Disetujui.',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, Approve',

                cancelButtonText:
                    'Batal',

                reverseButtons:
                    true

            });


        if (!confirm.isConfirmed) {

            return;

        }


        button
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                ></span>
                Memproses...
            `);


        try {

            const response =
                await fetch(
                    `/mentor/logbook/${encodeURIComponent(id)}/approve`,
                    {
                        method: 'PUT',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken
                        }
                    }
                );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Gagal approve logbook.'
                );

            }


            await Swal.fire({

                icon:
                    'success',

                title:
                    'Berhasil',

                text:
                    result.message ||
                    'Logbook berhasil disetujui.',

                timer:
                    1200,

                showConfirmButton:
                    false

            });


            if (selectedPesertaId) {

                loadLogbook(
                    selectedPesertaId
                );

            }


        } catch (error) {

            console.error(error);


            button
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-check-circle me-1"></i>
                    Approve
                `);


            Swal.fire({

                icon:
                    'error',

                title:
                    'Gagal',

                text:
                    error.message ||
                    'Gagal menyetujui logbook.'

            });

        }

    }
);


/*
|--------------------------------------------------------------------------
| BUKA MODAL FEEDBACK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-feedback',
    function () {

        const id =
            $(this).data('id');


        const catatan =
            $(this).attr(
                'data-catatan'
            ) || '';


        if (!id) {

            Swal.fire({
                icon:
                    'error',

                title:
                    'Gagal',

                text:
                    'ID logbook tidak ditemukan.'
            });

            return;

        }


        $('#feedbackLogbookId')
            .val(id);


        $('#catatanMentor')
            .val(
                $('<textarea>')
                    .html(catatan)
                    .text()
            );


        if (modalFeedback) {

            modalFeedback.show();

        }

    }
);


/*
|--------------------------------------------------------------------------
| SIMPAN FEEDBACK
|--------------------------------------------------------------------------
*/

$('#formFeedback').on(
    'submit',
    async function (event) {

        event.preventDefault();


        const id =
            $('#feedbackLogbookId').val();


        const catatan =
            $('#catatanMentor')
                .val()
                .trim();


        const button =
            $('#btnSimpanFeedback');


        if (!id) {

            Swal.fire({
                icon:
                    'error',

                title:
                    'Gagal',

                text:
                    'ID logbook tidak ditemukan.'
            });

            return;

        }


        button
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                ></span>
                Menyimpan...
            `);


        try {

            const response =
                await fetch(
                    `/mentor/logbook/${encodeURIComponent(id)}/feedback`,
                    {
                        method:
                            'PUT',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken
                        },

                        body:
                            JSON.stringify({
                                catatan_mentor:
                                    catatan
                            })
                    }
                );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Gagal menyimpan catatan.'
                );

            }


            if (modalFeedback) {

                modalFeedback.hide();

            }


            await Swal.fire({

                icon:
                    'success',

                title:
                    'Berhasil',

                text:
                    result.message ||
                    'Catatan mentor berhasil disimpan.',

                timer:
                    1200,

                showConfirmButton:
                    false

            });


            $('#catatanMentor')
                .val('');


            if (selectedPesertaId) {

                loadLogbook(
                    selectedPesertaId
                );

            }


        } catch (error) {

            console.error(error);


            Swal.fire({

                icon:
                    'error',

                title:
                    'Gagal',

                text:
                    error.message ||
                    'Gagal menyimpan catatan.'

            });


        } finally {

            button
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-save me-1"></i>
                    Simpan Catatan
                `);

        }

    }
);


/*
|--------------------------------------------------------------------------
| LIHAT BUKTI
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-bukti',
    function () {

        const url =
            $(this).attr(
                'data-url'
            );


        if (!url) {

            Swal.fire({

                icon:
                    'warning',

                title:
                    'Bukti Tidak Tersedia',

                text:
                    'File bukti kegiatan tidak ditemukan.'

            });

            return;

        }


        Swal.fire({

            title:
                'Bukti Kegiatan',

            imageUrl:
                url,

            imageAlt:
                'Bukti kegiatan',

            width:
                '850px',

            padding:
                '1rem',

            showConfirmButton:
                false,

            showCloseButton:
                true,

            imageClass:
                'img-fluid rounded border',

            didOpen: () => {

                const image =
                    Swal.getPopup()
                        .querySelector(
                            '.swal2-image'
                        );


                if (image) {

                    image.style.maxHeight =
                        '650px';

                    image.style.objectFit =
                        'contain';

                }

            },

            imageHeight:
                'auto'

        });

    }
);


/*
|--------------------------------------------------------------------------
| EXPORT EXCEL
|--------------------------------------------------------------------------
*/

$('#btnExportExcel').on(
    'click',
    function () {

        if (!selectedPesertaId) {

            Swal.fire({

                icon:
                    'warning',

                title:
                    'Pilih Peserta',

                text:
                    'Silakan pilih peserta terlebih dahulu sebelum melakukan export.'

            });

            return;

        }


        const url =
            '/mentor/logbook/export' +
            '?user_id=' +
            encodeURIComponent(
                selectedPesertaId
            );


        window.location.href =
            url;

    }
);

</script>

@endpush