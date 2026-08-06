@extends('layouts.back-office')

@section('title','Data Perguruan Tinggi')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Perguruan Tinggi</h3>
            <small class="text-muted">Daftar perguruan tinggi berdasarkan data universitas dari peserta.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle w-100" id="universitasTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Perguruan Tinggi</th>
                            <th>Jumlah Pengajuan</th>
                            <th>Jumlah Peserta</th>
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

        $('#universitasTable').DataTable({

            destroy:true,

            processing:true,

            serverSide:false,

            ajax:{

                url:'/api/back-office/perguruan-tinggi',

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

                { data:'universitas' },

                { data:'pengajuan_count' },

                { data:'peserta_count' }

            ]

        });

    });

</script>
@endpush
