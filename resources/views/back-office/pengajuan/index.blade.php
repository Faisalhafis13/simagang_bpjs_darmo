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

                            <th>Universitas</th>

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

                            <td colspan="12" class="text-center text-muted py-5">Memuat data...</td>

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

                    data:'proposal',

                    orderable:false,

                    searchable:false,

                    render:function(data){

                        if(!data) return '<span class="text-muted">-</span>';

                        const url = (typeof data === 'string' && data.match(/^https?:\/\//)) ? data : ('/' + String(data).replace(/^\/?/, ''));

                        // extract filename from possible stored path
                        const filenameProp = String(data).split('/').pop();
                        return `<button class="btn btn-sm btn-outline-primary btn-preview" data-filename="${filenameProp}" data-title="Proposal" data-type="proposal">Lihat</button>`;

                    }

                },

                {

                    data:'surat_permohonan',

                    orderable:false,

                    searchable:false,

                    render:function(data){

                        if(!data) return '<span class="text-muted">-</span>';

                        const url = (typeof data === 'string' && data.match(/^https?:\/\//)) ? data : ('/' + String(data).replace(/^\/?/, ''));

                        const filenameSurat = String(data).split('/').pop();
                        return `<button class="btn btn-sm btn-outline-primary btn-preview" data-filename="${filenameSurat}" data-title="Surat Permohonan" data-type="surat_permohonan">Lihat</button>`;

                    }

                },

                {

                    data:null,

                    orderable:false,

                    searchable:false,

                    render:function(data){

                        return `

                            <button class="btn btn-success btn-sm me-1" onclick="updatePengajuanStatus(${data.id}, 'Diterima')">Approve</button>

                            <button class="btn btn-danger btn-sm" onclick="updatePengajuanStatus(${data.id}, 'Ditolak')">Reject</button>

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

<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="min-height:60vh;">
                <iframe id="filePreviewFrame" src="" frameborder="0" style="width:100%;height:60vh;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    const filePreviewModalEl = document.getElementById('filePreviewModal');
    const filePreviewModal = new bootstrap.Modal(filePreviewModalEl);

    $(document).on('click', '.btn-preview', function(){
        const filename = $(this).data('filename');
        const type = $(this).data('type');
        const title = $(this).data('title') || 'Preview';

        if(!filename || !type){
            Swal.fire({ icon:'error', title:'File tidak ditemukan' });
            return;
        }

        const url = `/back-office/file/preview/${type}/${encodeURIComponent(filename)}`;

        $('#filePreviewTitle').text(title);
        $('#filePreviewFrame').attr('src', url);
        filePreviewModal.show();
    });

    filePreviewModalEl.addEventListener('hidden.bs.modal', function(){
        $('#filePreviewFrame').attr('src','');
    });
</script>
@endpush
