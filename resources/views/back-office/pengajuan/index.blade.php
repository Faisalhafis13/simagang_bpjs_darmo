@extends('layouts.back-office')

@section('title','Data Pengajuan')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Data Pengajuan</h3>
                <p class="text-muted mb-0">Kelola pengajuan peserta dengan tombol Approve / Reject.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Pengajuan</th>
                        <th>Nama Ketua</th>
                        <th>Universitas</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Periode</th>
                        <th>Anggota</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengajuanTableBody">
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
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
        return '<span class="badge bg-warning text-dark">Menunggu</span>';
    }

    async function loadPengajuanData() {
        const response = await fetch('/api/back-office/pengajuan');
        const result = await response.json();

        const tbody = document.getElementById('pengajuanTableBody');
        tbody.innerHTML = '';

        if (result.status !== 'success' || !Array.isArray(result.data) || result.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-muted py-5">Tidak ada data pengajuan.</td>
                </tr>
            `;
            return;
        }

        result.data.forEach((item, index) => {
            const anggota = Array.isArray(item.anggota) ? item.anggota.map(a => a.nama_anggota).join(', ') : '-';
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode_pengajuan}</td>
                    <td>${item.nama_ketua}</td>
                    <td>${item.universitas}</td>
                    <td>${item.semester}</td>
                    <td>${renderStatusBadge(item.status)}</td>
                    <td>${item.tanggal_mulai} - ${item.tanggal_selesai}</td>
                    <td>${anggota || '-'}</td>
                    <td>${item.catatan ?? '-'}</td>
                    <td>
                        <button class="btn btn-success btn-sm me-1" onclick="updatePengajuanStatus(${item.id}, 'Diterima')">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="updatePengajuanStatus(${item.id}, 'Ditolak')">Reject</button>
                    </td>
                </tr>
            `;
        });
    }

    async function updatePengajuanStatus(id, status) {
        const catatan = prompt('Catatan tambahan untuk peserta (opsional)');
        const response = await fetch(`/api/back-office/pengajuan/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({
                status,
                catatan,
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            alert(result.message || 'Gagal memperbarui status pengajuan.');
            return;
        }

        alert('Status pengajuan berhasil diperbarui.');
        loadPengajuanData();
    }

    document.addEventListener('DOMContentLoaded', loadPengajuanData);
</script>
@endpush
