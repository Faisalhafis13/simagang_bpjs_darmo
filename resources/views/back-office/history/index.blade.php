@extends('layouts.back-office')

@section('title','Log History')

@section('content')

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body p-3 p-md-4">

    <div class="table-responsive">

        <table
            class="table table-bordered table-hover align-middle w-100"
            id="historyTable">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>Old Data</th>
                    <th>New Data</th>
                    <th>IP</th>
                    <th>User Agent</th>
                </tr>
            </thead>

            <tbody></tbody>

        </table>

    </div>

</div>

</div>

@endsection

@push('js')

<script>

$(function () {

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    $('#historyTable').DataTable({

        processing: true,
        serverSide: false,

        ajax: {

            url: '/back-office/history/data',

            dataSrc: function (response) {

                return (
                    response &&
                    Array.isArray(response.data)
                ) ? response.data : [];

            },

            error: function (xhr, status, error) {

                console.error(
                    'DataTables AJAX error (history):',
                    status,
                    error,
                    xhr
                );

                let message = 'Gagal memuat data history.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {
                    message = xhr.responseJSON.message;
                }

                $('#historyTable tbody').html(`
                    <tr>
                        <td colspan="10"
                            class="text-center text-muted py-4">
                            ${escapeHtml(message)}
                        </td>
                    </tr>
                `);

            }

        },

        columns: [

            {
                data: null,
                searchable: false,
                orderable: false,

                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },

            {
                data: 'created_at',

                render: function (data) {
                    return data
                        ? `<span class="text-nowrap">${escapeHtml(data)}</span>`
                        : '-';
                }
            },

            {
                data: 'user',

                render: function (data) {
                    return data
                        ? escapeHtml(data)
                        : 'System';
                }
            },

            {
                data: 'module',

                render: function (data) {
                    return data
                        ? escapeHtml(data)
                        : '-';
                }
            },

            {
                data: 'action',

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

            {
                data: 'description',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    return `
                        <div style="
                            min-width:220px;
                            max-width:400px;
                            white-space:normal;
                            overflow-wrap:anywhere;
                            word-break:break-word;
                        ">
                            ${escapeHtml(data)}
                        </div>
                    `;
                }
            },

            {
                data: 'old_data',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    try {

                        const formatted = JSON.stringify(
                            JSON.parse(data),
                            null,
                            2
                        );

                        return `
                            <pre class="mb-0 text-start small"
                                 style="
                                    min-width:250px;
                                    max-width:350px;
                                    max-height:250px;
                                    overflow:auto;
                                    white-space:pre-wrap;
                                    overflow-wrap:anywhere;
                                    word-break:break-word;
                                 ">
${escapeHtml(formatted)}
                            </pre>
                        `;

                    } catch (e) {

                        return `
                            <div style="
                                min-width:250px;
                                max-width:350px;
                                white-space:pre-wrap;
                                overflow-wrap:anywhere;
                                word-break:break-word;
                            ">
                                ${escapeHtml(data)}
                            </div>
                        `;
                    }

                }

            },

            {
                data: 'new_data',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    try {

                        const formatted = JSON.stringify(
                            JSON.parse(data),
                            null,
                            2
                        );

                        return `
                            <pre class="mb-0 text-start small"
                                 style="
                                    min-width:250px;
                                    max-width:350px;
                                    max-height:250px;
                                    overflow:auto;
                                    white-space:pre-wrap;
                                    overflow-wrap:anywhere;
                                    word-break:break-word;
                                 ">
${escapeHtml(formatted)}
                            </pre>
                        `;

                    } catch (e) {

                        return `
                            <div style="
                                min-width:250px;
                                max-width:350px;
                                white-space:pre-wrap;
                                overflow-wrap:anywhere;
                                word-break:break-word;
                            ">
                                ${escapeHtml(data)}
                            </div>
                        `;
                    }

                }

            },

            {
                data: 'ip_address',

                render: function (data) {

                    return data
                        ? `<span class="text-nowrap">${escapeHtml(data)}</span>`
                        : '-';

                }

            },

            {
                data: 'user_agent',
                defaultContent: '-',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    const safeData = escapeHtml(data);

                    return `
                        <div
                            class="user-agent-cell"
                            title="${safeData}"
                            style="
                                min-width:280px;
                                max-width:450px;
                                white-space:normal;
                                overflow-wrap:anywhere;
                                word-break:break-word;
                                line-height:1.5;
                            ">
                            ${safeData}
                        </div>
                    `;
                }

            }

        ],

        order: [[1, 'desc']],

        autoWidth: false,

        responsive: false,

        scrollX: true,

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],

        language: {

            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada log history',
            processing: 'Memuat data...',

            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: '›',
                previous: '‹'
            }

        }

    });

});
</script>

@endpush
