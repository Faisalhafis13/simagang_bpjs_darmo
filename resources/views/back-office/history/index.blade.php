@extends('layouts.back-office')

@section('title', 'Log History')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Log Histori
        </h3>

        <small class="text-muted">
            Riwayat aktivitas pengguna pada sistem.
        </small>
    </div>


    <div>

        <a
            href="{{ route('back-office.history.export') }}"
            class="btn btn-success"
        >
            <i class="bi bi-file-earmark-excel me-1"></i>
            Export Excel
        </a>

    </div>

</div>


{{-- ========================================================= --}}
{{-- TABLE LOG HISTORY --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-3 p-md-4">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle w-100"
                id="historyTable"
            >

                <thead class="table-light">

                    <tr>

                        <th
                            width="5%"
                            class="text-center"
                        >
                            No
                        </th>

                        <th
                            class="text-center text-nowrap"
                        >
                            Waktu
                        </th>

                        <th>
                            User
                        </th>

                        <th>
                            Module
                        </th>

                        <th
                            class="text-center"
                        >
                            Aksi
                        </th>

                        <th>
                            Deskripsi
                        </th>

                        <th>
                            Old Data
                        </th>

                        <th>
                            New Data
                        </th>

                        <th
                            class="text-center"
                        >
                            IP
                        </th>

                        <th>
                            User Agent
                        </th>

                    </tr>

                </thead>


                <tbody>

                    {{-- Data diisi oleh DataTable --}}

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

$(function () {


    /* =========================================================
    | ESCAPE HTML
    | =========================================================
    */

    function escapeHtml(text) {

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


    /* =========================================================
    | FORMAT TANGGAL
    | =========================================================
    |
    | Format:
    |
    | DD-MM-YYYY HH:mm:ss
    |
    | Contoh:
    |
    | 2026-08-14 10:30:45
    |
    | menjadi:
    |
    | 14-08-2026 10:30:45
    |
    | Parsing YYYY-MM-DD dilakukan manual agar tidak terkena
    | perubahan tanggal akibat timezone JavaScript.
    |
    =========================================================
    */

    function formatTanggalWaktu(value) {

        if (
            value === null ||
            value === undefined ||
            String(value).trim() === ''
        ) {

            return '-';

        }


        const rawValue =
            String(value).trim();


        /* =====================================================
        | FORMAT ISO / DATETIME
        |
        | YYYY-MM-DD
        | YYYY-MM-DD HH:mm:ss
        | YYYY-MM-DDTHH:mm:ss
        | YYYY-MM-DDTHH:mm:ss.sss
        =====================================================
        */

        const isoMatch =
            rawValue.match(
                /^(\d{4})-(\d{2})-(\d{2})(?:[T\s](\d{2}):(\d{2})(?::(\d{2})(?:\.(\d+))?)?(?:Z|[+-]\d{2}:?\d{2})?)?/
            );


        if (isoMatch) {

            const year =
                isoMatch[1];

            const month =
                isoMatch[2];

            const day =
                isoMatch[3];

            const hour =
                isoMatch[4] ?? '00';

            const minute =
                isoMatch[5] ?? '00';

            const second =
                isoMatch[6] ?? '00';


            return (
                `${day}-${month}-${year} ` +
                `${hour}:${minute}:${second}`
            );

        }


        /* =====================================================
        | FORMAT DD-MM-YYYY
        | =====================================================
        */

        const dmyMatch =
            rawValue.match(
                /^(\d{2})-(\d{2})-(\d{4})(?:[T\s](\d{2}):(\d{2})(?::(\d{2}))?)?/
            );


        if (dmyMatch) {

            const day =
                dmyMatch[1];

            const month =
                dmyMatch[2];

            const year =
                dmyMatch[3];

            const hour =
                dmyMatch[4] ?? '00';

            const minute =
                dmyMatch[5] ?? '00';

            const second =
                dmyMatch[6] ?? '00';


            return (
                `${day}-${month}-${year} ` +
                `${hour}:${minute}:${second}`
            );

        }


        /* =====================================================
        | FORMAT DD/MM/YYYY
        | =====================================================
        */

        const slashMatch =
            rawValue.match(
                /^(\d{2})\/(\d{2})\/(\d{4})(?:[T\s](\d{2}):(\d{2})(?::(\d{2}))?)?/
            );


        if (slashMatch) {

            const day =
                slashMatch[1];

            const month =
                slashMatch[2];

            const year =
                slashMatch[3];

            const hour =
                slashMatch[4] ?? '00';

            const minute =
                slashMatch[5] ?? '00';

            const second =
                slashMatch[6] ?? '00';


            return (
                `${day}-${month}-${year} ` +
                `${hour}:${minute}:${second}`
            );

        }


        /* =====================================================
        | FALLBACK
        =====================================================
        */

        const date =
            new Date(rawValue);


        if (
            isNaN(
                date.getTime()
            )
        ) {

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


        const hour =
            String(
                date.getHours()
            ).padStart(2, '0');


        const minute =
            String(
                date.getMinutes()
            ).padStart(2, '0');


        const second =
            String(
                date.getSeconds()
            ).padStart(2, '0');


        return (
            `${day}-${month}-${year} ` +
            `${hour}:${minute}:${second}`
        );

    }


    /* =========================================================
    | DATATABLE
    =========================================================
    */

    $('#historyTable').DataTable({

        processing: true,


        /* =====================================================
        | AJAX
        =====================================================
        */

        ajax: {

            url:
                "{{ url('/back-office/history/data') }}",

            type:
                "GET",

            dataType:
                "json",


            dataSrc:
                function (response) {

                    console.log(
                        'Log history:',
                        response
                    );


                    return response.data || [];

                },


            error:
                function (xhr) {

                    console.error(
                        'Gagal memuat log history:',
                        xhr.status,
                        xhr.responseText
                    );


                    let message =
                        'Gagal memuat data log history.';


                    if (
                        xhr.status === 404
                    ) {

                        message =
                            'Route data log history tidak ditemukan.';

                    }


                    else if (
                        xhr.status === 401
                    ) {

                        message =
                            'Session telah berakhir. Silakan login kembali.';

                    }


                    else if (
                        xhr.status === 403
                    ) {

                        message =
                            'Anda tidak memiliki akses ke log history.';

                    }


                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Gagal',

                            text:
                                message

                        });

                    }

                }

        },


        /* =====================================================
        | COLUMNS
        =====================================================
        */

        columns: [


            /* =================================================
            | NO
            =================================================
            */

            {

                data:
                    null,

                className:
                    'text-center',


                render:
                    function (
                        data,
                        type,
                        row,
                        meta
                    ) {

                        return meta.row + 1;

                    }

            },


            /* =================================================
            | WAKTU
            =================================================
            */

            {

                data:
                    'created_at',

                className:
                    'text-nowrap',


                render:
                    function (
                        data,
                        type
                    ) {

                        if (!data) {

                            return '-';

                        }


                        /*
                        | Untuk tampilan:
                        | DD-MM-YYYY HH:mm:ss
                        */

                        if (
                            type === 'display' ||
                            type === 'filter'
                        ) {

                            return formatTanggalWaktu(
                                data
                            );

                        }


                        /*
                        | Untuk sorting DataTable,
                        | tetap gunakan nilai tanggal asli.
                        */

                        return data;

                    }

            },


            /* =================================================
            | USER
            =================================================
            */

            {

                data:
                    'user',


                render:
                    function (
                        data,
                        type,
                        row
                    ) {


                        if (
                            data &&
                            data.name
                        ) {

                            return escapeHtml(
                                data.name
                            );

                        }


                        if (
                            row.user_name
                        ) {

                            return escapeHtml(
                                row.user_name
                            );

                        }


                        return 'System';

                    }

            },


            /* =================================================
            | MODULE
            =================================================
            */

            {

                data:
                    'module',


                render:
                    function (data) {

                        return data

                            ? escapeHtml(data)

                            : '-';

                    }

            },


            /* =================================================
            | AKSI
            =================================================
            */

            {

                data:
                    'action',

                className:
                    'text-center',


                render:
                    function (data) {


                        if (!data) {

                            return '-';

                        }


                        return `

                            <span class="badge bg-primary">

                                ${escapeHtml(data)}

                            </span>

                        `;

                    }

            },


            /* =================================================
            | DESKRIPSI
            =================================================
            */

            {

                data:
                    'description',


                render:
                    function (data) {


                        if (!data) {

                            return '-';

                        }


                        return `

                            <div
                                style="
                                    min-width:250px;
                                    max-width:400px;
                                    white-space:pre-wrap;
                                    overflow-wrap:anywhere;
                                    word-break:break-word;
                                "
                            >

                                ${escapeHtml(data)}

                            </div>

                        `;

                    }

            },


            /* =================================================
            | OLD DATA
            =================================================
            */

            {

                data:
                    'old_data',


                render:
                    function (data) {


                        if (!data) {

                            return '-';

                        }


                        try {

                            const parsed =
                                typeof data === 'string'

                                    ? JSON.parse(data)

                                    : data;


                            const formatted =
                                JSON.stringify(
                                    parsed,
                                    null,
                                    2
                                );


                            return `

                                <pre
                                    class="mb-0 text-start small"
                                    style="
                                        min-width:250px;
                                        max-width:350px;
                                        max-height:250px;
                                        overflow:auto;
                                        white-space:pre-wrap;
                                        overflow-wrap:anywhere;
                                        word-break:break-word;
                                    "
                                >${escapeHtml(formatted)}</pre>

                            `;

                        }


                        catch (error) {

                            return `

                                <div
                                    style="
                                        min-width:250px;
                                        max-width:350px;
                                        white-space:pre-wrap;
                                        overflow-wrap:anywhere;
                                        word-break:break-word;
                                    "
                                >

                                    ${escapeHtml(data)}

                                </div>

                            `;

                        }

                    }

            },


            /* =================================================
            | NEW DATA
            =================================================
            */

            {

                data:
                    'new_data',


                render:
                    function (data) {


                        if (!data) {

                            return '-';

                        }


                        try {

                            const parsed =
                                typeof data === 'string'

                                    ? JSON.parse(data)

                                    : data;


                            const formatted =
                                JSON.stringify(
                                    parsed,
                                    null,
                                    2
                                );


                            return `

                                <pre
                                    class="mb-0 text-start small"
                                    style="
                                        min-width:250px;
                                        max-width:350px;
                                        max-height:250px;
                                        overflow:auto;
                                        white-space:pre-wrap;
                                        overflow-wrap:anywhere;
                                        word-break:break-word;
                                    "
                                >${escapeHtml(formatted)}</pre>

                            `;

                        }


                        catch (error) {

                            return `

                                <div
                                    style="
                                        min-width:250px;
                                        max-width:350px;
                                        white-space:pre-wrap;
                                        overflow-wrap:anywhere;
                                        word-break:break-word;
                                    "
                                >

                                    ${escapeHtml(data)}

                                </div>

                            `;

                        }

                    }

            },


            /* =================================================
            | IP ADDRESS
            =================================================
            */

            {

                data:
                    'ip_address',

                className:
                    'text-center',


                render:
                    function (data) {


                        return data

                            ? `

                                <span class="text-nowrap">

                                    ${escapeHtml(data)}

                                </span>

                            `

                            : '-';

                    }

            },


            /* =================================================
            | USER AGENT
            =================================================
            */

            {

                data:
                    'user_agent',

                defaultContent:
                    '-',


                render:
                    function (data) {


                        if (!data) {

                            return '-';

                        }


                        const safeData =
                            escapeHtml(data);


                        return `

                            <div
                                title="${safeData}"
                                style="
                                    min-width:280px;
                                    max-width:450px;
                                    white-space:normal;
                                    overflow-wrap:anywhere;
                                    word-break:break-word;
                                    line-height:1.5;
                                "
                            >

                                ${safeData}

                            </div>

                        `;

                    }

            }

        ],


        /* =====================================================
        | URUTKAN BERDASARKAN WAKTU TERBARU
        =====================================================
        */

        order: [

            [1, 'desc']

        ],


        /* =====================================================
        | DATATABLE SETTINGS
        =====================================================
        */

        autoWidth:
            false,


        responsive:
            false,


        scrollX:
            true,


        pageLength:
            10,


        lengthMenu: [

            [10, 25, 50, 100],

            [10, 25, 50, 100]

        ],


        /* =====================================================
        | LANGUAGE
        =====================================================
        */

        language: {

            search:
                'Cari:',


            lengthMenu:
                'Tampilkan _MENU_ data',


            info:
                'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',


            infoEmpty:
                'Tidak ada data',


            zeroRecords:
                'Data tidak ditemukan',


            emptyTable:
                'Belum ada log history',


            processing:
                'Memuat data...',


            paginate: {

                first:
                    'Pertama',

                last:
                    'Terakhir',

                next:
                    '›',

                previous:
                    '‹'

            }

        }

    });

});

</script>

@endpush