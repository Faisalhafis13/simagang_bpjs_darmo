@extends('layouts.back-office')

@section('title', 'Log History')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

    <h3 class="fw-bold mb-1">
        Log History
    </h3>

    <small class="text-muted">
        Riwayat aktivitas pengguna dalam sistem.
    </small>

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

    $('#historyTable').DataTable({

        processing: true,

        ajax: {

            url: "{{ url('/back-office/history/data') }}",

            type: "GET",

            dataType: "json",

            dataSrc: function (response) {

                console.log(
                    'Log history:',
                    response
                );

                return response.data || [];

            },

            error: function (xhr) {

                console.error(
                    'Gagal memuat log history:',
                    xhr.status,
                    xhr.responseText
                );

                let message =
                    'Gagal memuat data log history.';


                if (xhr.status === 404) {

                    message =
                        'Route data log history tidak ditemukan.';

                } else if (xhr.status === 401) {

                    message =
                        'Session telah berakhir. Silakan login kembali.';

                } else if (xhr.status === 403) {

                    message =
                        'Anda tidak memiliki akses ke log history.';

                }


                if (
                    typeof Swal !== 'undefined'
                ) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        text: message

                    });

                }

            }

        },


        columns: [

            /*
            |--------------------------------------------------------------------------
            | No
            |--------------------------------------------------------------------------
            */

            {

                data: null,

                className: 'text-center',

                render: function (
                    data,
                    type,
                    row,
                    meta
                ) {

                    return meta.row + 1;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Waktu
            |--------------------------------------------------------------------------
            */

            {

                data: 'created_at',

                className: 'text-nowrap',

                render: function (data) {

                    if (!data) {

                        return '-';

                    }


                    const date =
                        new Date(data);


                    if (
                        isNaN(
                            date.getTime()
                        )
                    ) {

                        return escapeHtml(data);

                    }


                    return date.toLocaleString(

                        'id-ID',

                        {

                            day: '2-digit',

                            month: '2-digit',

                            year: 'numeric',

                            hour: '2-digit',

                            minute: '2-digit',

                            second: '2-digit'

                        }

                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            {

                data: 'user',

                render: function (
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


            /*
            |--------------------------------------------------------------------------
            | Module
            |--------------------------------------------------------------------------
            */

            {

                data: 'module',

                render: function (data) {

                    return data
                        ? escapeHtml(data)
                        : '-';

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Aksi
            |--------------------------------------------------------------------------
            */

            {

                data: 'action',

                className: 'text-center',

                render: function (data) {

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


            /*
            |--------------------------------------------------------------------------
            | Deskripsi
            |--------------------------------------------------------------------------
            */

            {

                data: 'description',

                render: function (data) {

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


            /*
            |--------------------------------------------------------------------------
            | Old Data
            |--------------------------------------------------------------------------
            */

            {

                data: 'old_data',

                render: function (data) {

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

                } catch (error) {

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


        /*
        |--------------------------------------------------------------------------
        | New Data
        |--------------------------------------------------------------------------
        */

        {

            data: 'new_data',

            render: function (data) {

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

                } catch (error) {

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


        /*
        |--------------------------------------------------------------------------
        | IP Address
        |--------------------------------------------------------------------------
        */

        {

            data: 'ip_address',

            className: 'text-center',

            render: function (data) {

                return data

                    ? `

                        <span class="text-nowrap">

                            ${escapeHtml(data)}

                        </span>

                    `

                    : '-';

            }

        },


        /*
        |--------------------------------------------------------------------------
        | User Agent
        |--------------------------------------------------------------------------
        */

        {

            data: 'user_agent',

            defaultContent: '-',

            render: function (data) {

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


    /*
    |--------------------------------------------------------------------------
    | DataTable Settings
    |--------------------------------------------------------------------------
    */

    order: [

        [1, 'desc']

    ],


    autoWidth: false,


    responsive: false,


    scrollX: true,


    pageLength: 10,


    lengthMenu: [

        [10, 25, 50, 100],

        [10, 25, 50, 100]

    ],


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


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
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

});

</script>

@endpush
