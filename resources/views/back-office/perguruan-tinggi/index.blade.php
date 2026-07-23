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
                            <th>Status Pengajuan</th>
                            <th>Contoh Peserta</th>
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
    async function loadPerguruanTinggiData() {
        const response = await fetch('/api/back-office/perguruan-tinggi');
        const result = await response.json();
        const body = document.querySelector('#universitasTable tbody');

        body.innerHTML = '';

        if (result.status !== 'success' || !Array.isArray(result.data) || result.data.length === 0) {
            body.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data perguruan tinggi.</td>
                </tr>
            `;
            return;
        }

        result.data.forEach((item, index) => {
            body.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.universitas}</td>
                    <td>${item.pengajuan_count}</td>
                    <td>${item.peserta_count}</td>
                    <td>${item.status}</td>
                    <td>${item.peserta_preview || '-'}</td>
                </tr>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', loadPerguruanTinggiData);
</script>
@endpush
