@extends('layouts.back-office')

@section('title','Data Pengajuan')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">Data Pengajuan</h3>

            <small class="text-muted">Kelola pengajuan peserta dengan tombol Approve / Reject.</small>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <table id="tablePengajuan" class="table table-bordered table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Kode Pengajuan</th>

                            <th>Nama Ketua</th>

                            <th>Email</th>

                            <th>Nomor HP</th>

                            <th>Perguruan Tinggi</th>

                            <th>Semester</th>

                            <th>Status</th>

                            <th>Periode</th>

                            <th>Anggota</th>

                            <th>Catatan</th>

                            <th>Proposal</th>

                            <th>Surat Permohonan</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody id="pengajuanTableBody">

                        <tr>

                            <td colspan="14" class="text-center text-muted py-5">Memuat data...</td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

@push('js')
<script>
    function renderStatusBadge(status) {
        const normalized = String(status).toLowerCase();
        if (normalized === 'diterima' || normalized === 'accepted') {
            return '<span class="badge bg-success">Diterima</span>';
        }
        if (normalized === 'ditolak' || normalized === 'rejected') {
            return '<span class="badge bg-danger">Ditolak</span>';
        }
        if (normalized === 'pending' || normalized === 'menunggu') {
            return '<span class="badge bg-warning text-dark">Menunggu</span>';
        }
        return '<span class="badge bg-warning text-dark">Menunggu</span>';
    }

    let tablePengajuan;

    $(function(){

        tablePengajuan = $('#tablePengajuan').DataTable({

            destroy:true,

            processing:true,

            serverSide:false,

            ajax:{

                url:'/api/back-office/pengajuan',

                dataSrc:function(response){

                    return response.data || [];

                }

            },

            columns:[

                {

                    data:null,

                    render:function(data,type,row,meta){

                        return meta.row + 1;

                    }

                },

                { data:'kode_pengajuan' },

                { data:'nama_ketua' },

                { data:'email_ketua', render:function(data){ return data || '-'; } },

                { data:'no_hp', render:function(data){ return data || '-'; } },

                { data:'universitas' },

                { data:'semester' },

                {

                    data:'status',

                    render:function(data){

                        return renderStatusBadge(data);

                    }

                },

                {

                    data:null,

                    render:function(data){

                        return `${data.tanggal_mulai} - ${data.tanggal_selesai}`;

                    }

                },

                {

                    data:'anggota',

                    render:function(data){

                        if(!Array.isArray(data) || data.length===0) return '-';

                        return data.map(a=>a.nama_anggota).join(', ');

                    }

                },

                {

                    data:'catatan',

                    render:function(data){

                        return data || '-';

                    }

                },

                {

    data: 'proposal',
    orderable: false,
    searchable: false,

    render: function(data) {

        if (!data) {
            return `
                <span class="text-muted small">
                    <i class="bi bi-file-earmark-x me-1"></i>
                    Tidak tersedia
                </span>
            `;
        }

        const filename = String(data).split('/').pop();

        const url =
            `/file/preview/proposal/${encodeURIComponent(filename)}`;

        return `
            <a
                href="${url}"
                target="_blank"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-eye me-1"></i>
                Lihat
            </a>
        `;
    }
},

{
    data: 'surat_permohonan',
    orderable: false,
    searchable: false,

    render: function(data) {

        if (!data) {
            return `
                <span class="text-muted small">
                    <i class="bi bi-file-earmark-x me-1"></i>
                    Tidak tersedia
                </span>
            `;
        }

        const filename = String(data).split('/').pop();

        const url =
            `/file/preview/surat_permohonan/${encodeURIComponent(filename)}`;

        return `
            <a
                href="${url}"
                target="_blank"
                class="btn btn-sm btn-outline-primary"
            >
                <i class="bi bi-eye me-1"></i>
                Lihat
            </a>
        `;
    }
},

{
    data: null,
    orderable: false,
    searchable: false,

    render: function(data) {

        const status = String(data.status || '').toLowerCase();

        const sudahDiputus =
            status === 'diterima' ||
            status === 'accepted' ||
            status === 'ditolak' ||
            status === 'rejected';

        if (sudahDiputus) {

            return `
                <div class="d-flex flex-column gap-1">

                    <button
                        type="button"
                        class="btn btn-success btn-sm"
                        disabled
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Approve
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        disabled
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Reject
                    </button>

                    <small class="text-muted text-center">
                        Sudah diputuskan
                    </small>

                </div>
            `;
        }

        return `
            <div class="d-flex flex-column gap-1">

                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    onclick="updatePengajuanStatus(${data.id}, 'Diterima')"
                >
                    <i class="bi bi-check-circle me-1"></i>
                    Terima
                </button>

                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="updatePengajuanStatus(${data.id}, 'Ditolak')"
                >
                    <i class="bi bi-x-circle me-1"></i>
                    Tolak
                </button>

            </div>
        `;
    }
}
            ]

        });

    });

    function updatePengajuanStatus(id, status){

        // If approving, send update immediately without asking for a note
        if(status === 'Diterima' || status === 'accepted'){

            $.ajax({

                url:`/api/back-office/pengajuan/${id}`,

                type:'PUT',

                contentType:'application/json',

                data:JSON.stringify({ status }),

                headers:{ 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },

                success:function(){

                    tablePengajuan.ajax.reload(null,false);

                    Swal.fire({ icon:'success', title:'Berhasil', timer:1200, showConfirmButton:false });

                },

                error:function(xhr){

                    Swal.fire({ icon:'error', title:'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });

                }

            });

            return;

        }

        // For rejection, ask for an optional note
        Swal.fire({

            title: 'Konfirmasi',

            input: 'text',

            inputLabel: 'Catatan tambahan (opsional)',

            showCancelButton: true

        }).then((result)=>{

            if(!result.isConfirmed) return;

            const catatan = result.value;

            $.ajax({

                url:`/api/back-office/pengajuan/${id}`,

                type:'PUT',

                contentType:'application/json',

                data:JSON.stringify({ status, catatan }),

                headers:{ 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },

                success:function(){

                    tablePengajuan.ajax.reload(null,false);

                    Swal.fire({ icon:'success', title:'Berhasil', timer:1200, showConfirmButton:false });

                },

                error:function(xhr){

                    Swal.fire({ icon:'error', title:'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });

                }

            });

        });

    }

</script>
@endpush
