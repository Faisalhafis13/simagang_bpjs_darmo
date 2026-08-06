@extends('layouts.back-office')

@section('title','Logbook Saya')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Logbook Saya</h3>
            <small class="text-muted">
                Kelola logbook kegiatan magang Anda.
            </small>
        </div>

        <button class="btn btn-primary" id="btnTambah">
            <i class="bi bi-plus-circle"></i>
            Tambah Logbook
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle w-100" id="tableLogbook">

                    <thead class="table-light">

                    <tr>

                        <th width="5%">No</th>

                        <th width="12%">Tanggal</th>

                        <th>Aktivitas</th>

                        <th>Hasil</th>

                        <th>Catatan</th>

                        <th width="15%">Aksi</th>

                    </tr>

                    </thead>

                </table>

            </div>

        </div>
    </div>

</div>


<div class="modal fade" id="modalLogbook">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form id="formLogbook">

                @csrf

                <input type="hidden" id="id">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Logbook
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Tanggal</label>

                        <input
                            type="date"
                            class="form-control"
                            id="tanggal"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label>Aktivitas</label>

                        <textarea
                            id="aktivitas"
                            class="form-control"
                            rows="4"
                            required
                        ></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Hasil</label>

                        <textarea
                            id="hasil"
                            class="form-control"
                            rows="4"
                            required
                        ></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Catatan</label>

                        <textarea
                            id="catatan"
                            class="form-control"
                            rows="3"
                        ></textarea>

                    </div>

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
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

const modal = new bootstrap.Modal(document.getElementById('modalLogbook'));

let table;

$(function(){

    table = $('#tableLogbook').DataTable({

        processing:true,

        serverSide:false,

        ajax:{

url:'/peserta/logbook/data',
            dataSrc:'data'

        },

        columns:[

            {

                data:null,

                render:function(data,type,row,meta){

                    return meta.row+1;

                }

            },

            {data:'tanggal'},

            {data:'aktivitas'},

            {data:'hasil'},

            {

                data:'catatan',

                render:function(data){

                    return data ?? '-';

                }

            },

            {

                data:null,

                render:function(data){

                    return `

                    <button
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="${data.id}">
                        Edit
                    </button>

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


$('#btnTambah').click(function(){

    $('#formLogbook')[0].reset();

    $('#id').val('');

    modal.show();

});


$('#formLogbook').submit(function(e){

    e.preventDefault();

    let id = $('#id').val();

    let url = id
        ? '/peserta/logbook/'+id
        : '/peserta/logbook';

    let method = id
        ? 'PUT'
        : 'POST';

    $.ajax({

        url:url,

        method:method,

        data:{

            _token:$('meta[name="csrf-token"]').attr('content'),

            tanggal:$('#tanggal').val(),

            aktivitas:$('#aktivitas').val(),

            hasil:$('#hasil').val(),

            catatan:$('#catatan').val()

        },

        success:function(){

            modal.hide();

            table.ajax.reload();

        }

    });

});


$(document).on('click','.btn-edit',function(){

    let id=$(this).data('id');

    $.get('/peserta/logbook/'+id,function(res){

        $('#id').val(res.data.id);

        $('#tanggal').val(res.data.tanggal);

        $('#aktivitas').val(res.data.aktivitas);

        $('#hasil').val(res.data.hasil);

        $('#catatan').val(res.data.catatan);

        modal.show();

    });

});


$(document).on('click','.btn-delete',function(){

    if(!confirm('Hapus logbook ini?')) return;

    let id=$(this).data('id');

    $.ajax({

        url:'/peserta/logbook/'+id,

        method:'DELETE',

        data:{
            _token:$('meta[name="csrf-token"]').attr('content')
        },

        success:function(){

            table.ajax.reload();

        }

    });

});

</script>

@endpush