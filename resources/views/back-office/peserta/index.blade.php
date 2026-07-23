@extends('layouts.back-office')

@section('title','Data Peserta')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Data Peserta</h3>
                <p class="text-muted mb-0">Daftar peserta yang terdaftar dari setiap pengajuan.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Kode Pengajuan</th>
                        <th>Universitas</th>
                        <th>Status Pengajuan</th>
                    </tr>
                </thead>
                <tbody id="pesertaTableBody">
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection

@push('js')
<script>
    async function loadPesertaData() {
        const response = await fetch('/api/back-office/peserta');
        const result = await response.json();

        const tbody = document.getElementById('pesertaTableBody');
        tbody.innerHTML = '';

        if (result.status !== 'success' || !Array.isArray(result.data) || result.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">Tidak ada peserta.</td>
                </tr>
            `;
            return;
        }

        result.data.forEach((item, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_peserta}</td>
                    <td>${item.email ?? '-'}</td>
                    <td>${item.no_hp ?? '-'}</td>
                    <td>${item.kode_pengajuan ?? '-'}</td>
                    <td>${item.universitas ?? '-'}</td>
                    <td>${item.status ?? '-'}</td>
                </tr>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', loadPesertaData);
</script>
@endpush
