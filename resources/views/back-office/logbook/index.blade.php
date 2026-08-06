@extends('layouts.back-office')

@section('title','Monitoring Logbook')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Monitoring Logbook
            </h3>

            <small class="text-muted">
                Monitoring seluruh logbook peserta magang.
            </small>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle w-100" id="logbookTable">

                    <thead>

                        <tr>

                            <th width="5%">No</th>

                            <th>Tanggal</th>

                            <th>Nama Peserta</th>

                            <th>Mentor</th>

                            <th>Aktivitas</th>

                            <th>Hasil</th>

                            <th>Catatan</th>

                            <th width="10%">Aksi</th>

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

    $('#logbookTable').DataTable({

        destroy:true,

        processing:true,

        serverSide:false,

        ajax:{

            url:'/api/back-office/logbook',

            dataSrc:'data'

        },

        columns:[

            {

                data:null,

                render:function(data,type,row,meta){

                    return meta.row + 1;

                }

            },

            {

                data:'tanggal'

            },

            {

                data:'nama_peserta'

            },

            {

                data:'mentor',

                render:function(data){

                    return data ?? '-';

                }

            },

            {

                data:'aktivitas'

            },

            {

                data:'hasil'

            },

            {

                data:'catatan',

                render:function(data){

                    return data ?? '-';

                }

            },

            {

                data:null,

                orderable:false,

                searchable:false,

                render:function(data){

                    return `
                        <button
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="${data.id}">
                            Hapus
                        </button>
                    `;

                }

            }

        ]

    });

});

$(document).on('click','.btn-delete',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Hapus Logbook?',

        text:'Data yang dihapus tidak dapat dikembalikan.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Ya, Hapus',

        cancelButtonText:'Batal'

    }).then((result)=>{

        if(!result.isConfirmed) return;

        $.ajax({

            url:'/api/back-office/logbook/'+id,

            type:'DELETE',

            data:{
                _token:$('meta[name="csrf-token"]').attr('content')
            },

            success:function(res){

                Swal.fire({

                    icon:'success',

                    title:'Berhasil',

                    text:res.message

                });

                $('#logbookTable').DataTable().ajax.reload();

            },

            error:function(){

                Swal.fire({

                    icon:'error',

                    title:'Gagal',

                    text:'Terjadi kesalahan.'

                });

            }

        });

    });

});

</script>

@endpush