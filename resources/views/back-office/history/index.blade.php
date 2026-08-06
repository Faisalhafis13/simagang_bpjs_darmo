@extends('layouts.back-office')

@section('title','Log History')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Log History</h3>
            <small class="text-muted">Lihat riwayat aktivitas sistem dan perubahan data.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle w-100" id="historyTable">
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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(function(){

        $('#historyTable').DataTable({

            destroy: true,

            processing: true,

            serverSide: false,

            ajax: {

                url: '/api/back-office/history',

                dataType: 'json',

                headers: {
                    'Accept': 'application/json'
                },

                dataSrc: function(response) {
                    if (!response || !response.data) {
                        console.error('History: unexpected response', response);
                        return [];
                    }
                    return response.data || [];
                },

                error: function(xhr, status, error) {
                    let msg = 'Gagal memuat history.';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    alert(msg);
                }

            },

            columns:[

                {

                    data:null,

                    render:function(data,type,row,meta){

                        return meta.row + 1;

                    }

                },

                { data:'created_at' },

                { data:'user' },

                { data:'module' },

                { data:'action' },

                { data:'description', render:function(d){ return d || '-'; } },

                { data:'old_data', render:function(d){

    if(!d){

        return '-';

    }

    try{

        return `<pre class="mb-0 text-start">${JSON.stringify(JSON.parse(d),null,2)}</pre>`;

    }catch(e){

        return d;

    }

}},

                { data:'new_data', render:function(d){

    if(!d){

        return '-';

    }

    try{

        return `<pre class="mb-0 text-start">${JSON.stringify(JSON.parse(d),null,2)}</pre>`;

    }catch(e){

        return d;

    }

}},

                { data:'ip_address', render:function(d){ return d || '-'; } }

            ]

        });

    });
</script>
@endpush
