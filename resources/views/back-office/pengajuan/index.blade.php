@extends('layouts.back-office')

@section('title', 'Data Pengajuan')

@section('content')

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

    <h3 class="fw-bold mb-1">
        Data Pengajuan
    </h3>

    <small class="text-muted">
        Kelola pengajuan peserta dengan tombol Approve / Reject.
    </small>

</div>

</div>

{{-- ========================================================= --}}
{{-- TABLE DATA PENGAJUAN --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body p-3 p-md-4">

    <div class="table-responsive">

        <table
            id="tablePengajuan"
            class="table table-bordered table-hover align-middle w-100"
        >

            <thead class="table-light">

                <tr>

                    <th
                        width="5%"
                        class="text-center text-nowrap"
                    >
                        No
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Kode Pengajuan
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Nama Ketua
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Email
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Nomor HP
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Perguruan Tinggi
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Semester
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Status
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Periode
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Anggota
                    </th>

                    <th
                        class="text-nowrap"
                    >
                        Catatan
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Proposal
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Surat Permohonan
                    </th>

                    <th
                        class="text-center text-nowrap"
                    >
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody id="pengajuanTableBody">

                <tr>

                    <td
                        colspan="14"
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

/*
|--------------------------------------------------------------------------
| Status Badge
|--------------------------------------------------------------------------
*/

function renderStatusBadge(status) {

    const normalized =
        String(status || '').toLowerCase();


    if (
        normalized === 'diterima' ||
        normalized === 'accepted'
    ) {

        return `
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Diterima
            </span>
        `;

    }


    if (
        normalized === 'ditolak' ||
        normalized === 'rejected'
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


let tablePengajuan;


/*
|--------------------------------------------------------------------------
| DataTable
|--------------------------------------------------------------------------
*/

$(function () {

    tablePengajuan =
        $('#tablePengajuan').DataTable({

            destroy: true,

            processing: true,

            serverSide: false,

            /*
            |--------------------------------------------------------------------------
            | Responsive / Horizontal Scroll
            |--------------------------------------------------------------------------
            */

            autoWidth: false,

            responsive: false,

            scrollX: true,

            scrollCollapse: true,

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            ajax: {

                url:
                    '/api/back-office/pengajuan',

                dataSrc: function (response) {

                    return response.data || [];

                },

                error: function (xhr) {

                    console.error(
                        'DataTables AJAX Error:',
                        xhr
                    );


                    const message =
                        xhr.responseJSON?.message ||
                        'Gagal memuat data pengajuan.';


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal Memuat Data',

                        text: message,

                        confirmButtonText: 'OK'

                    });

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

                    className:
                        'text-center text-nowrap',

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
                | Kode Pengajuan
                |--------------------------------------------------------------------------
                */

                {

                    data: 'kode_pengajuan',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return `

                            <span class="fw-semibold">

                                ${data ?? '-'}

                            </span>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Nama Ketua
                |--------------------------------------------------------------------------
                */

                {

                    data: 'nama_ketua',

                    render: function (data) {

                        return data || '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Email
                |--------------------------------------------------------------------------
                */

                {

                    data: 'email_ketua',

                    render: function (data) {

                        return data || '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Nomor HP
                |--------------------------------------------------------------------------
                */

                {

                    data: 'no_hp',

                    render: function (data) {

                        return data || '-';

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

                        return data || '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Semester
                |--------------------------------------------------------------------------
                */

                {

                    data: 'semester',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return data || '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                {

                    data: 'status',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return renderStatusBadge(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Periode
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        const mulai =
                            data.tanggal_mulai || '-';

                        const selesai =
                            data.tanggal_selesai || '-';


                        return `

                            <span class="text-nowrap">

                                ${mulai}

                                -

                                ${selesai}

                            </span>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Anggota
                |--------------------------------------------------------------------------
                */

                {

                    data: 'anggota',

                    render: function (data) {

                        if (
                            !Array.isArray(data) ||
                            data.length === 0
                        ) {

                            return '-';

                        }


                        return data
                            .map(function (anggota) {

                                return anggota.nama_anggota;

                            })
                            .join(', ');

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Catatan
                |--------------------------------------------------------------------------
                */

                {

                    data: 'catatan',

                    render: function (data) {

                        return data || '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Proposal
                |--------------------------------------------------------------------------
                */

                {

                    data: 'proposal',

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        if (!data) {

                            return `

                                <span class="text-muted small">

                                    <i
                                        class="bi bi-file-earmark-x me-1"
                                    ></i>

                                    Tidak tersedia

                                </span>

                            `;

                        }


                        const filename =
                            String(data)
                                .split('/')
                                .pop();


                        const url =
                            `/file/preview/proposal/${encodeURIComponent(filename)}`;


                        return `

                            <a
                                href="${url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >

                                <i
                                    class="bi bi-eye me-1"
                                ></i>

                                Lihat

                            </a>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Surat Permohonan
                |--------------------------------------------------------------------------
                */

                {

                    data: 'surat_permohonan',

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        if (!data) {

                            return `

                                <span class="text-muted small">

                                    <i
                                        class="bi bi-file-earmark-x me-1"
                                    ></i>

                                    Tidak tersedia

                                </span>

                            `;

                        }


                        const filename =
                            String(data)
                                .split('/')
                                .pop();


                        const url =
                            `/file/preview/surat_permohonan/${encodeURIComponent(filename)}`;


                        return `

                            <a
                                href="${url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >

                                <i
                                    class="bi bi-eye me-1"
                                ></i>

                                Lihat

                            </a>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Aksi
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center',

                    render: function (data) {

                        const status =
                            String(
                                data.status || ''
                            ).toLowerCase();


                        const sudahDiputus =
                            status === 'diterima' ||
                            status === 'accepted' ||
                            status === 'ditolak' ||
                            status === 'rejected';


                        if (sudahDiputus) {

                            return `

                                <div
                                    class="d-flex flex-column gap-1"
                                    style="min-width: 120px;"
                                >

                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        disabled
                                    >

                                        <i
                                            class="bi bi-check-circle me-1"
                                        ></i>

                                        Terima

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        disabled
                                    >

                                        <i
                                            class="bi bi-x-circle me-1"
                                        ></i>

                                        Tolak

                                    </button>


                                    <small
                                        class="text-muted text-center"
                                    >

                                        Sudah diputuskan

                                    </small>

                                </div>

                            `;

                        }


                        return `

                            <div
                                class="d-flex flex-column gap-1"
                                style="min-width: 120px;"
                            >

                                <button
                                    type="button"
                                    class="btn btn-success btn-sm"
                                    onclick="updatePengajuanStatus(
                                        ${data.id},
                                        'Diterima'
                                    )"
                                >

                                    <i
                                        class="bi bi-check-circle me-1"
                                    ></i>

                                    Terima

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="updatePengajuanStatus(
                                        ${data.id},
                                        'Ditolak'
                                    )"
                                >

                                    <i
                                        class="bi bi-x-circle me-1"
                                    ></i>

                                    Tolak

                                </button>

                            </div>

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
                    'Belum ada data pengajuan.',

                processing:
                    'Memuat data...',

                search:
                    'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_ sampai _END_ dari _TOTAL_ pengajuan',

                infoEmpty:
                    'Tidak ada data',

                zeroRecords:
                    'Pengajuan tidak ditemukan.',

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


/*
|--------------------------------------------------------------------------
| Update Status Pengajuan
|--------------------------------------------------------------------------
*/

function updatePengajuanStatus(
    id,
    status
) {

    const isApprove =
        status === 'Diterima';


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Terima
    |--------------------------------------------------------------------------
    */

    if (isApprove) {

        Swal.fire({

            icon: 'question',

            title: 'Terima Pengajuan?',

            text:
                'Pengajuan ini akan diterima dan akun peserta akan dibuat.',

            showCancelButton: true,

            confirmButtonText: `

                <i class="bi bi-check-circle me-1"></i>

                Ya, Terima

            `,

            cancelButtonText: `

                <i class="bi bi-x-circle me-1"></i>

                Batal

            `,

            confirmButtonColor:
                '#198754',

            cancelButtonColor:
                '#6c757d',

            reverseButtons: true

        }).then(function (result) {

            if (!result.isConfirmed) {

                return;

            }


            prosesUpdatePengajuan(
                id,
                status
            );

        });


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Tolak + Catatan
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        icon: 'warning',

        title: 'Tolak Pengajuan?',

        text:
            'Pengajuan yang sudah ditolak tidak dapat diubah kembali.',

        input: 'textarea',

        inputPlaceholder:
            'Masukkan catatan penolakan (opsional)...',

        inputAttributes: {

            'aria-label':
                'Catatan penolakan'

        },

        showCancelButton: true,

        confirmButtonText: `

            <i class="bi bi-x-circle me-1"></i>

            Ya, Tolak

        `,

        cancelButtonText: `

            <i class="bi bi-arrow-left me-1"></i>

            Batal

        `,

        confirmButtonColor:
            '#dc3545',

        cancelButtonColor:
            '#6c757d',

        reverseButtons: true

    }).then(function (result) {

        if (!result.isConfirmed) {

            return;

        }


        prosesUpdatePengajuan(
            id,
            status,
            result.value || null
        );

    });

}


/*
|--------------------------------------------------------------------------
| Request Update
|--------------------------------------------------------------------------
*/

function prosesUpdatePengajuan(
    id,
    status,
    catatan = null
) {

    Swal.fire({

        title: 'Memproses...',

        text:
            'Mohon tunggu sebentar.',

        allowOutsideClick: false,

        allowEscapeKey: false,

        didOpen: function () {

            Swal.showLoading();

        }

    });


    const payload = {

        status: status

    };


    if (catatan !== null) {

        payload.catatan =
            catatan;

    }


    $.ajax({

        url:
            `/api/back-office/pengajuan/${id}`,

        type:
            'PUT',

        contentType:
            'application/json',

        data:
            JSON.stringify(payload),

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                    .attr('content')

        },


        success: function (response) {

            if (tablePengajuan) {

                tablePengajuan.ajax.reload(
                    null,
                    false
                );

            }


            Swal.fire({

                icon:
                    'success',

                title:
                    'Berhasil!',

                text:
                    response.message ||
                    (
                        status === 'Diterima'
                            ? 'Pengajuan berhasil diterima.'
                            : 'Pengajuan berhasil ditolak.'
                    ),

                timer:
                    1800,

                showConfirmButton:
                    false

            });

        },


        error: function (xhr) {

            console.error(
                'Update Pengajuan Error:',
                xhr
            );


            let message =
                'Terjadi kesalahan saat memproses pengajuan.';


            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;

            }


            /*
            |--------------------------------------------------------------------------
            | Validation Error
            |--------------------------------------------------------------------------
            */

            if (
                xhr.status === 422 &&
                xhr.responseJSON?.errors
            ) {

                const errors =
                    xhr.responseJSON.errors;


                message =
                    Object.values(errors)
                        .flat()
                        .join('<br>');

            }


            Swal.fire({

                icon:
                    'error',

                title:
                    'Gagal!',

                html:
                    message,

                confirmButtonText:
                    'OK'

            });

        }

    });

}

</script>

@endpush
