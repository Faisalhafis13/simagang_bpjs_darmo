function postData(url, data, successCallback, errorCallback = null) {

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: successCallback,
        error: errorCallback
    });

}
$(document).ready(function () {

    $(document).on('submit', '#formPengajuan', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: '/api/public/pengajuan',

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            cache: false,

success: function (response) {

    Swal.fire({

        icon: 'success',

        title: 'Pengajuan Berhasil',

        html: `
            <p>Pengajuan magang berhasil dikirim.</p>

            <h4 style="color:#0F4C81;font-weight:bold;margin-top:15px;">
                ${response.kode_pengajuan}
            </h4>

            <small>
                Simpan kode ini untuk melihat hasil seleksi.
            </small>
        `,

        confirmButtonText: 'Salin Kode'

    }).then((result) => {

        if(result.isConfirmed){

            navigator.clipboard.writeText(response.kode_pengajuan);

            Swal.fire({

                icon:'success',

                title:'Berhasil',

                text:'Kode berhasil disalin.'

            });

        }

    });

    $('#formPengajuan')[0].reset();

$('#anggota-wrapper').empty();
},
            error: function (xhr) {

                if (xhr.status === 422) {

                    let pesan = '';

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        pesan += value[0] + '<br>';

                    });

                    Swal.fire({

                        icon: 'error',

                        title: 'Validasi Gagal',

                        html: pesan

                    });

                } else {

                    Swal.fire({

                        icon: 'error',

                        title: 'Terjadi Kesalahan',

                        text: 'Silakan coba beberapa saat lagi.'

                    });

                }

            }

        });

    });

});
$(document).on('submit','#formHasil',function(e){

    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({

        url:'/api/public/hasil',

        type:'POST',

        data:formData,

        processData:false,

        contentType:false,

        success:function(response){

            let data = response.data;

            let badge = '';

            if(data.status == 'menunggu'){

                badge = '<span class="badge bg-warning text-dark">Menunggu</span>';

            }else if(data.status == 'diterima'){

                badge = '<span class="badge bg-success">Diterima</span>';

            }else{

                badge = '<span class="badge bg-danger">Ditolak</span>';

            }

            let anggota = '';

            data.anggota.forEach(function(item){

                anggota += `<li>${item.nama_anggota}</li>`;

            });

            $('#hasilPengajuan').html(`

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <h4 class="mb-4">

                            Status ${badge}

                        </h4>

                        <table class="table">

                            <tr>

                                <th width="35%">
                                    Kode Pengajuan
                                </th>

                                <td>
                                    ${data.kode_pengajuan}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Ketua
                                </th>

                                <td>
                                    ${data.nama_ketua}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Universitas
                                </th>

                                <td>
                                    ${data.universitas}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Tanggal Mulai
                                </th>

                                <td>
                                    ${data.tanggal_mulai}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Tanggal Selesai
                                </th>

                                <td>
                                    ${data.tanggal_selesai}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Anggota
                                </th>

                                <td>

                                    <ul>

                                        ${anggota}

                                    </ul>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            `);

            $('#hasilPengajuan').show();

        },

        error:function(){

            Swal.fire({

                icon:'error',

                title:'Oops...',

                text:'Kode Pengajuan tidak ditemukan.'

            });

        }

    });

});