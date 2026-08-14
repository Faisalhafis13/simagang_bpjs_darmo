@extends('layouts.back-office')

@section('title','Data Perguruan Tinggi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Data Perguruan Tinggi
        </h3>

        <small class="text-muted">
            Daftar perguruan tinggi berdasarkan pengajuan dan jumlah peserta yang diterima.
        </small>

    </div>


    <div>

        <a
            href="{{ route('back-office.perguruan-tinggi.export') }}"
            class="btn btn-success"
        >

            <i class="bi bi-file-earmark-excel me-1"></i>

            Export Excel

        </a>

    </div>

</div>


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle w-100"
                id="universitasTable"
            >

                <thead class="table-light">

                    <tr>

                        <th
                            width="5%"
                            class="text-center"
                        >
                            No
                        </th>

                        <th>
                            Perguruan Tinggi
                        </th>

                        <th
                            class="text-center"
                        >
                            Pengajuan Aktif
                        </th>

                        <th
                            class="text-center"
                        >
                            Pengajuan Nonaktif
                        </th>

                        <th
                            class="text-center"
                        >
                            Total Pengajuan
                        </th>

                        <th
                            class="text-center"
                        >
                            Peserta Aktif
                        </th>

                        <th
                            class="text-center"
                        >
                            Peserta Nonaktif
                        </th>

                        <th
                            class="text-center"
                        >
                            Total Peserta
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td
                            colspan="8"
                            class="text-center text-muted py-5"
                        >
                            Memuat data...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Helper SweetAlert
    |--------------------------------------------------------------------------
    */

    function showError(message) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: message

        });

    }


    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    $('#universitasTable').DataTable({

        destroy: true,

        processing: true,

        serverSide: false,


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        ajax: {

            url: '/api/back-office/perguruan-tinggi',

            dataSrc: function (response) {

                if (
                    response &&
                    Array.isArray(response.data)
                ) {

                    return response.data;

                }

                return [];

            },


            error: function (xhr) {

                console.error(
                    'DataTable Perguruan Tinggi Error:',
                    xhr
                );


                let message =
                    'Gagal memuat data perguruan tinggi.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showError(message);

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Columns
        |--------------------------------------------------------------------------
        */

        columns: [

            /*
            |--------------------------------------------------------------------------
            | No
            |--------------------------------------------------------------------------
            */

            {

                data: null,

                className: 'text-center',

                orderable: false,

                searchable: false,

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
            | Perguruan Tinggi
            |--------------------------------------------------------------------------
            */

            {

                data: 'universitas',

                render: function (data) {

                    if (!data) {

                        return `
                            <span class="text-muted">
                                Tidak diketahui
                            </span>
                        `;

                    }


                    return `

                        <div class="fw-semibold">

                            <i class="bi bi-building me-1 text-primary"></i>

                            ${data}

                        </div>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Pengajuan Aktif
            |--------------------------------------------------------------------------
            */

            {

                data: 'pengajuan_aktif',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-success">

                            <i class="bi bi-check-circle me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Pengajuan Nonaktif
            |--------------------------------------------------------------------------
            */

            {

                data: 'pengajuan_nonaktif',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-secondary">

                            <i class="bi bi-archive me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Total Pengajuan
            |--------------------------------------------------------------------------
            */

            {

                data: 'pengajuan_total',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-primary">

                            <i class="bi bi-file-earmark-text me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Peserta Aktif
            |--------------------------------------------------------------------------
            */

            {

                data: 'peserta_aktif',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-success">

                            <i class="bi bi-person-check me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Peserta Nonaktif
            |--------------------------------------------------------------------------
            */

            {

                data: 'peserta_nonaktif',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-secondary">

                            <i class="bi bi-person-x me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | Total Peserta
            |--------------------------------------------------------------------------
            */

            {

                data: 'peserta_total',

                className: 'text-center',

                render: function (data) {

                    const jumlah =
                        Number(data) || 0;


                    return `

                        <span class="badge bg-primary">

                            <i class="bi bi-people me-1"></i>

                            ${jumlah}

                        </span>

                    `;

                }

            }

        ],


        /*
        |--------------------------------------------------------------------------
        | Bahasa DataTable
        |--------------------------------------------------------------------------
        */

        language: {

            emptyTable:
                'Belum ada data perguruan tinggi.',

            processing:
                'Memuat data...',

            search:
                'Cari:',

            lengthMenu:
                'Tampilkan _MENU_ data',

            info:
                'Menampilkan _START_ sampai _END_ dari _TOTAL_ perguruan tinggi',

            infoEmpty:
                'Tidak ada data',

            infoFiltered:
                '(difilter dari _MAX_ total data)',

            zeroRecords:
                'Perguruan tinggi tidak ditemukan.',

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

        },


        /*
        |--------------------------------------------------------------------------
        | Tampilan
        |--------------------------------------------------------------------------
        */

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],


        order: [
            [1, 'asc']
        ]

    });

});

</script>

@endpush