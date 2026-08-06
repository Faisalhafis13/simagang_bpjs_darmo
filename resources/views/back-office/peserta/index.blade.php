@extends('layouts.back-office')

@section('title','Data Peserta')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">Data Peserta</h3>

            <small class="text-muted">
                Daftar peserta yang terdaftar dari setiap pengajuan.
            </small>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <table id="tablePeserta" class="table table-bordered table-hover align-middle w-100">

                    <thead class="table-light">

                        <tr>

                            <th width="5%" class="text-center">No</th>

                            <th width="15%" class="text-center">Kode Pengajuan</th>
                                                        <th width="18%" class="text-center">Universitas</th>


                            <th>Nama Peserta</th>

                            <th>Email</th>

                            <th>No HP</th>


                            <th width="12%" class="text-center">Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td colspan="7" class="text-center">
                                Memuat data...
                            </td>

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

$(function(){

    $('#tablePeserta').DataTable({

        destroy:true,

        processing:true,

        serverSide:false,

        searching:true,

        ordering:false,

        ajax:{

            url:'/api/back-office/peserta',

            dataSrc:'data'

        },

        columns:[

            {
                data:null,
                defaultContent:''
            },

            {
                data:'kode_pengajuan',
                defaultContent:'-'
            },
                        {
                data:'universitas',
                render:function(data){
                    return data ?? '-';
                }
            },


            {
                data:'nama_peserta'
            },

            {
                data:'email',
                render:function(data){
                    return data ?? '-';
                }
            },

            {
                data:'no_hp',
                render:function(data){
                    return data ?? '-';
                }
            },


            {
                data:'status',
                render:function(data){
                    const normalized = String(data || '').toLowerCase();

                    if(normalized === 'diterima' || normalized === 'accepted'){
                        return '<span class="badge bg-success">Diterima</span>';
                    }

                    if(normalized === 'ditolak' || normalized === 'rejected'){
                        return '<span class="badge bg-danger">Ditolak</span>';
                    }

                    return '<span class="badge bg-warning text-dark">Menunggu</span>';
                }
            }

        ],

drawCallback: function () {

    let api = this.api();
    let rows = api.rows({ page: 'current' }).nodes();
    let data = api.rows({ page: 'current' }).data().toArray();

    let lastKode = '';
    let nomor = 1;

    data.forEach(function (item, index) {

        let td = $(rows).eq(index).children('td');

        if (item.kode_pengajuan !== lastKode) {

            let jumlah = data.filter(x => x.kode_pengajuan === item.kode_pengajuan).length;

            // No
            td.eq(0)
                .attr('rowspan', jumlah)
                .css({
                    verticalAlign: 'middle',
                    textAlign: 'center',
                    fontWeight: 'bold'
                })
                .text(nomor++);

            // Kode Pengajuan
            td.eq(1)
                .attr('rowspan', jumlah)
                .css({
                    verticalAlign: 'middle',
                    textAlign: 'center'
                });

            // Universitas
            td.eq(2)
                .attr('rowspan', jumlah)
                .css({
                    verticalAlign: 'middle',
                    textAlign: 'center'
                });

            // Status
            td.eq(6)
                .attr('rowspan', jumlah)
                .css({
                    verticalAlign: 'middle',
                    textAlign: 'center'
                });

            lastKode = item.kode_pengajuan;

        } else {

            // hapus dari index terbesar ke terkecil
            td.eq(6).remove(); // Status
            td.eq(2).remove(); // Universitas
            td.eq(1).remove(); // Kode Pengajuan
            td.eq(0).remove(); // No

        }

    });

}
    });

});

</script>

@endpush