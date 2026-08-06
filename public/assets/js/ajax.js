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

    function validatePengajuanForm() {

        let errors = [];

        const namaKetua = $.trim($('input[name="nama_ketua"]').val());
        const universitas = $.trim($('input[name="universitas"]').val());
        const semester = $.trim($('input[name="semester"]').val());
        const noHp = $.trim($('input[name="no_hp"]').val());
        const emailKetua = $.trim($('input[name="email_ketua"]').val());
        const tanggalMulai = $.trim($('input[name="tanggal_mulai"]').val());
        const tanggalSelesai = $.trim($('input[name="tanggal_selesai"]').val());
        const proposal = $('input[name="proposal"]').val();
        const surat = $('input[name="surat_permohonan"]').val();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!namaKetua)
            errors.push('Nama ketua wajib diisi.');

        if (!universitas)
            errors.push('Universitas wajib diisi.');

        if (!semester)
            errors.push('Semester wajib diisi.');

        if (!noHp)
            errors.push('Nomor HP ketua wajib diisi.');

        if (!emailKetua) {

            errors.push('Email ketua wajib diisi.');

        } else if (!emailRegex.test(emailKetua)) {

            errors.push('Email ketua tidak valid.');

        }

        if (!tanggalMulai)
            errors.push('Tanggal mulai wajib diisi.');

        if (!tanggalSelesai)
            errors.push('Tanggal selesai wajib diisi.');

        if (
            tanggalMulai &&
            tanggalSelesai &&
            new Date(tanggalSelesai) < new Date(tanggalMulai)
        ) {

            errors.push('Tanggal selesai harus sama dengan atau setelah tanggal mulai.');

        }

        if (!proposal)
            errors.push('Proposal wajib diunggah.');

        if (!surat)
            errors.push('Surat permohonan wajib diunggah.');

        $('#anggota-wrapper .anggota-item').each(function (index) {

            const itemIndex = index + 1;

            const namaAnggota = $.trim($(this).find('input[name*="[nama]"]').val());

            const emailAnggota = $.trim($(this).find('input[name*="[email]"]').val());

            const noHpAnggota = $.trim($(this).find('input[name*="[no_hp]"]').val());

            if (!namaAnggota) {
                errors.push(`Nama anggota ${itemIndex} wajib diisi.`);
            }

            if (!emailAnggota) {
                errors.push(`Email anggota ${itemIndex} wajib diisi.`);
            } else if (!emailRegex.test(emailAnggota)) {
                errors.push(`Email anggota ${itemIndex} tidak valid.`);
            }

            if (!noHpAnggota) {
                errors.push(`Nomor HP anggota ${itemIndex} wajib diisi.`);
            }

        });

        return errors;

    }

    $(document).on('submit', '#formPengajuan', function (e) {

        e.preventDefault();

        const validationErrors = validatePengajuanForm();

        if (validationErrors.length > 0) {

            Swal.fire({

                icon: 'error',

                title: 'Validasi Gagal',

                html: validationErrors.join('<br>')

            });

            return;

        }

        let formData = new FormData(this);

        // Debug
        for (let pair of formData.entries()) {
            console.log(pair[0], pair[1]);
        }

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

                    if (result.isConfirmed) {

                        navigator.clipboard.writeText(response.kode_pengajuan);

                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text: 'Kode berhasil disalin.'

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
            let loginNote = '';

            const status = String(data.status || '').toLowerCase();

            if(status === 'menunggu' || status === 'pending'){

                badge = '<span class="badge bg-warning text-dark">Menunggu</span>';

            }else if(status === 'diterima' || status === 'accepted'){

                badge = '<span class="badge bg-success">Diterima</span>';
loginNote = `
    <div class="alert alert-info mb-4">
        <strong>Informasi Login Peserta</strong>

        <ul class="mb-0 mt-2">
            <li>Ketua dan seluruh anggota menggunakan <strong>email masing-masing</strong> yang didaftarkan saat pengajuan.</li>
            <li>Password awal seluruh peserta adalah <strong>${data.kode_pengajuan}</strong>.</li>
            <li>Pada login pertama, setiap peserta wajib mengganti password sebelum dapat menggunakan website.</li>
        </ul>
    </div>
`;
            }else if(status === 'ditolak' || status === 'rejected'){

                badge = '<span class="badge bg-danger">Ditolak</span>';

            }else{

                badge = '<span class="badge bg-secondary">Tidak diketahui</span>';

            }

            let anggota = '';

            const anggotaList = Array.isArray(data.anggota) ? data.anggota : [];

            if(anggotaList.length === 0){

                anggota = '<li>Tidak ada anggota</li>';

            } else {

                anggotaList.forEach(function(item){

                    anggota += `<li>${item.nama_anggota || '-'}</li>`;

                });

            }

            $('#hasilPengajuan').html(`

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        ${loginNote}

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