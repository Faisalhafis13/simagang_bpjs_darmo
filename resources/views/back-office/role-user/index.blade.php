@extends('layouts.back-office')

@section('title','Role User')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                Role User

            </h3>

            <small class="text-muted">

                Manajemen User dan Hak Akses

            </small>

        </div>

        <button
            class="btn btn-primary"
            id="btnTambah">

            <i class="bi bi-plus-circle"></i>

            Tambah User

        </button>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">
<table
    id="tableUser"
    class="table table-bordered table-hover align-middle w-100">

    <thead>

        <tr>

            <th>No</th>

            <th>Nama</th>

            <th>Email</th>

            <th>Role</th>

            <th>Aksi</th>

        </tr>

    </thead>

</table>       </div>

    </div>

</div>



<!-- ===========================
MODAL
=========================== -->

<div
class="modal fade"
id="modalUser"
tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content border-0 rounded-4">

<form id="formUser">

@csrf

<input
type="hidden"
id="id">

<div class="modal-header">

<h5 class="modal-title">

Data User

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
type="button">

</button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Nama

</label>

<input
type="text"
class="form-control"
id="name"
name="name">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
class="form-control"
id="email"
name="email">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
class="form-control"
id="password"
name="password">

<small class="text-muted">

Kosongkan jika tidak ingin diubah.

</small>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Role

</label>

<select
class="form-select"
id="role_id"
name="role_id">

<option value="">

Pilih Role

</option>

@foreach($roles as $role)

<option value="{{ $role->id }}">

{{ $role->name }}

</option>

@endforeach

</select>

</div>

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Batal

</button>

<button
type="submit"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

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

const modal = new bootstrap.Modal(
    document.getElementById('modalUser')
);

let table;

$(function(){

    loadData();

});

function loadData(){

    table = $('#tableUser').DataTable({

        destroy:true,

        processing:true,

        ajax:{
            url:'/back-office/role-user/data',
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

            {

                data:'name'

            },

            {

                data:'email'

            },

            {

                data:'role.name'

            },

            {

                data:null,

                orderable:false,

                searchable:false,

                render:function(data){

                    return `

                    <button
                        class="btn btn-warning btn-sm edit"
                        data-id="${data.id}">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button
                        class="btn btn-danger btn-sm delete"
                        data-id="${data.id}">

                        <i class="bi bi-trash"></i>

                    </button>

                    `;

                }

            }

        ]

    });

}

$('#btnTambah').click(function(){

    $('#formUser')[0].reset();

    $('#id').val('');

    modal.show();

});

$('#formUser').submit(function(e){

    e.preventDefault();

    let id = $('#id').val();

    let url = '/api/back-office/role-user';

    let method = 'POST';

    if(id){

        url += '/' + id;

        method = 'PUT';

    }

    $.ajax({

        url:url,

        type:method,

        data:{

            _token:$('meta[name=csrf-token]').attr('content'),

            name:$('#name').val(),

            email:$('#email').val(),

            password:$('#password').val(),

            role_id:$('#role_id').val()

        },

        success:function(){

            modal.hide();

            table.ajax.reload(null,false);

            Swal.fire({

                icon:'success',

                title:'Berhasil',

                text:'Data berhasil disimpan',

                timer:1500,

                showConfirmButton:false

            });

        },

        error:function(xhr){

            Swal.fire({

                icon:'error',

                title:'Oops...',

                text:xhr.responseJSON.message

            });

        }

    });

});

$(document).on('click','.edit',function(){

    let id=$(this).data('id');

    $.get('/back-office/role-user/data/'+id,function(response){

        const data = response.data || response;

        $('#id').val(data.id);

        $('#name').val(data.name);

        $('#email').val(data.email);

        $('#role_id').val(data.role_id);

        $('#password').val('');

        modal.show();

    });

});

$(document).on('click','.delete',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Hapus data?',

        text:'Data tidak dapat dikembalikan.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Ya'

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:'/api/back-office/role-user/'+id,

                type:'DELETE',

                data:{

                    _token:$('meta[name=csrf-token]').attr('content')

                },

                success:function(){

                    table.ajax.reload(null,false);

                    Swal.fire({

                        icon:'success',

                        title:'Berhasil',

                        timer:1200,

                        showConfirmButton:false

                    });

                }

            });

        }

    });

});
</script>

@endpush