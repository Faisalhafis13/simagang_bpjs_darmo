@extends('layouts.back-office')

@section('title', 'Monitoring Logbook (Mentor)')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h4 class="mb-1">
                Monitoring Logbook Peserta
            </h4>

            <small class="text-muted">
                Pantau, review, dan berikan feedback pada logbook peserta.
            </small>

        </div>


        <div class="card-body">

            <div class="mb-4">

                <label class="form-label fw-bold">
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

            </div>


            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                    id="tableMonitoring"
                >

                    <thead class="table-light">

                        <tr>

                            <th>No</th>

                            <th>Tanggal</th>

                            <th>Aktivitas</th>

                            <th>Hasil</th>

                            <th>Catatan Peserta</th>

                            <th>Bukti</th>

                            <th>Status</th>

                            <th>Catatan Mentor</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody id="logbookData">

                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >
                                Pilih peserta terlebih dahulu
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- MODAL FEEDBACK --}}
<div
    class="modal fade"
    id="modalFeedback"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0 rounded-4">

            <form id="formFeedback">

                @csrf

                <input
                    type="hidden"
                    id="feedbackLogbookId"
                >

                <div class="modal-header">

                    <h5 class="modal-title">
                        Catatan Mentor
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Feedback / Catatan
                    </label>

                    <textarea
                        id="catatanMentor"
                        class="form-control"
                        rows="6"
                        placeholder="Berikan feedback untuk peserta..."
                    ></textarea>

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
                    >
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

let modalFeedback;

let tableMonitoring;


$(function () {

    modalFeedback = new bootstrap.Modal(
        document.getElementById('modalFeedback')
    );


    loadPeserta();


    $('#peserta').on('change', function () {

        const userId = $(this).val();

        if (!userId) {

            $('#logbookData').html(`
                <tr>
                    <td
                        colspan="9"
                        class="text-center text-muted py-4"
                    >
                        Pilih peserta terlebih dahulu
                    </td>
                </tr>
            `);

            return;
        }

        loadLogbook(userId);

    });

});


async function loadPeserta()
{
    try {

        const response = await fetch(
            '/mentor/logbook/peserta'
        );

        const peserta = await response.json();

        let html = `
            <option value="">
                -- Pilih Peserta --
            </option>
        `;

        peserta.forEach(function (item) {

            html += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;

        });

        $('#peserta').html(html);

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal memuat data peserta.'
        });

    }
}


async function loadLogbook(userId)
{
    try {

        const response = await fetch(
            `/mentor/logbook/data?user_id=${userId}`
        );

        const data = await response.json();

        renderLogbook(data);

    } catch (error) {

        console.error(error);

        $('#logbookData').html(`
            <tr>
                <td
                    colspan="9"
                    class="text-center text-danger"
                >
                    Gagal memuat logbook.
                </td>
            </tr>
        `);

    }
}


function statusBadge(status)
{
    if (
        String(status).toLowerCase() === 'disetujui'
    ) {

        return `
            <span class="badge bg-success">
                Disetujui
            </span>
        `;

    }

    return `
        <span class="badge bg-warning text-dark">
            Menunggu
        </span>
    `;
}


function renderLogbook(data)
{
    let html = '';

    if (!data || data.length === 0) {

        html = `
            <tr>
                <td
                    colspan="9"
                    class="text-center text-muted py-4"
                >
                    Belum ada logbook.
                </td>
            </tr>
        `;

        $('#logbookData').html(html);

        return;
    }


    data.forEach(function (item, index) {

        const approved =
            String(item.status).toLowerCase() === 'disetujui';


        const approveButton = approved

            ? `
                <button
                    class="btn btn-sm btn-success"
                    disabled
                >
                    <i class="bi bi-check-circle"></i>
                    Sudah Disetujui
                </button>
            `

            : `
                <button
                    class="btn btn-sm btn-success btn-approve"
                    data-id="${item.id}"
                >
                    <i class="bi bi-check-circle"></i>
                    Approve
                </button>
            `;


        const bukti = item.bukti_url

            ? `
                <button
                    class="btn btn-sm btn-outline-primary btn-bukti"
                    data-url="${item.bukti_url}"
                >
                    <i class="bi bi-image"></i>
                    Lihat
                </button>
            `

            : '<span class="text-muted">-</span>';


        html += `

            <tr>

                <td>
                    ${index + 1}
                </td>

                <td>
                    ${item.tanggal || '-'}
                </td>

                <td>
                    ${item.aktivitas || '-'}
                </td>

                <td>
                    ${item.hasil || '-'}
                </td>

                <td>
                    ${item.catatan || '-'}
                </td>

                <td>
                    ${bukti}
                </td>

                <td>
                    ${statusBadge(item.status)}
                </td>

                <td>
                    ${item.catatan_mentor || '-'}
                </td>

                <td>

                    <div class="d-flex gap-1">

                        ${approveButton}

                        <button
                            class="btn btn-sm btn-warning btn-feedback"
                            data-id="${item.id}"
                            data-catatan="${encodeURIComponent(item.catatan_mentor || '')}"
                        >
                            <i class="bi bi-chat-left-text"></i>
                            Catatan
                        </button>

                    </div>

                </td>

            </tr>

        `;

    });


    $('#logbookData').html(html);
}


$(document).on(
    'click',
    '.btn-approve',
    async function ()
{

    const id = $(this).data('id');

    const confirm = await Swal.fire({

        icon: 'question',

        title: 'Approve Logbook?',

        text: 'Logbook ini akan ditandai sebagai Disetujui.',

        showCancelButton: true,

        confirmButtonText: 'Ya, Approve',

        cancelButtonText: 'Batal'

    });


    if (!confirm.isConfirmed) {
        return;
    }


    try {

        const response = await fetch(
            `/mentor/logbook/${id}/approve`,
            {
                method: 'PUT',

                headers: {
                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content')
                }
            }
        );


        const result = await response.json();


        if (!response.ok) {
            throw new Error(
                result.message || 'Gagal approve.'
            );
        }


        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: result.message,

            timer: 1200,

            showConfirmButton: false

        });


        const userId = $('#peserta').val();

        loadLogbook(userId);

    } catch (error) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: error.message

        });

    }

});


$(document).on(
    'click',
    '.btn-feedback',
    function ()
{

    const id = $(this).data('id');

    const catatan = decodeURIComponent(
        $(this).data('catatan') || ''
    );


    $('#feedbackLogbookId').val(id);

    $('#catatanMentor').val(catatan);

    modalFeedback.show();

});


$('#formFeedback').on(
    'submit',
    async function (event)
{

    event.preventDefault();


    const id =
        $('#feedbackLogbookId').val();


    try {

        const response = await fetch(
            `/mentor/logbook/${id}/feedback`,
            {
                method: 'PUT',

                headers: {
                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content')
                },

                body: JSON.stringify({

                    catatan_mentor:
                        $('#catatanMentor').val()

                })
            }
        );


        const result = await response.json();


        if (!response.ok) {
            throw new Error(
                result.message ||
                'Gagal menyimpan catatan.'
            );
        }


        modalFeedback.hide();


        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: result.message,

            timer: 1200,

            showConfirmButton: false

        });


        loadLogbook(
            $('#peserta').val()
        );


    } catch (error) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: error.message

        });

    }

});


$(document).on(
    'click',
    '.btn-bukti',
    function ()
{

    const url = $(this).data('url');


    Swal.fire({

        title: 'Bukti Kegiatan',

        imageUrl: url,

        imageAlt: 'Bukti kegiatan',

        width: '800px',

        showConfirmButton: false,

        showCloseButton: true

    });

});

</script>

@endpush