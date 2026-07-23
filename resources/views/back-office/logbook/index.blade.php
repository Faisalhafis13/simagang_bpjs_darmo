@extends('layouts.back-office')

@section('title','Monitoring Logbook')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Monitoring Logbook</h3>
            <small class="text-muted">Pantau logbook kegiatan peserta berdasarkan setiap anggota.</small>
        </div>
        <button class="btn btn-primary" id="btnTambahLogbook">
            <i class="bi bi-plus-circle"></i> Tambah Logbook
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle w-100" id="logbookTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Peserta</th>
                            <th>Universitas</th>
                            <th>Aktivitas</th>
                            <th>Hasil</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLogbook" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <form id="formLogbook">
                @csrf
                <input type="hidden" id="logbookId">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Logbook</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peserta</label>
                            <select class="form-select" id="anggotaMagang" name="anggota_magang_id">
                                <option value="">Pilih peserta</option>
                                @foreach($peserta as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_anggota }} - {{ $item->email ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Aktivitas</label>
                            <textarea class="form-control" id="aktivitas" name="aktivitas" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hasil</label>
                            <textarea class="form-control" id="hasil" name="hasil" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    const modalLogbook = new bootstrap.Modal(document.getElementById('modalLogbook'));
    const logbookTableBody = document.querySelector('#logbookTable tbody');

    async function loadLogbookData() {
        const response = await fetch('/api/back-office/logbook');
        const result = await response.json();

        logbookTableBody.innerHTML = '';

        if (result.status !== 'success' || !Array.isArray(result.data) || result.data.length === 0) {
            logbookTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada logbook.</td>
                </tr>
            `;
            return;
        }

        result.data.forEach((entry, index) => {
            logbookTableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${entry.tanggal}</td>
                    <td>${entry.nama_peserta}</td>
                    <td>${entry.universitas || '-'}</td>
                    <td>${entry.aktivitas}</td>
                    <td>${entry.hasil}</td>
                    <td>${entry.catatan || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${entry.id}">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('btnTambahLogbook').addEventListener('click', () => {
        document.getElementById('formLogbook').reset();
        document.getElementById('logbookId').value = '';
        modalLogbook.show();
    });

    document.getElementById('formLogbook').addEventListener('submit', async function (event) {
        event.preventDefault();

        const payload = {
            _token: document.querySelector('meta[name="csrf-token"]').content,
            anggota_magang_id: document.getElementById('anggotaMagang').value,
            tanggal: document.getElementById('tanggal').value,
            aktivitas: document.getElementById('aktivitas').value,
            hasil: document.getElementById('hasil').value,
            catatan: document.getElementById('catatan').value,
        };

        const response = await fetch('/api/back-office/logbook', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const result = await response.json();

        if (response.ok) {
            modalLogbook.hide();
            loadLogbookData();
            alert(result.message || 'Logbook tersimpan.');
            return;
        }

        alert(result.message || 'Terjadi kesalahan.');
    });

    document.addEventListener('click', async ({ target }) => {
        if (target.matches('.btn-delete')) {
            const id = target.dataset.id;
            if (!confirm('Hapus logbook ini?')) return;

            const response = await fetch(`/api/back-office/logbook/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ _token: document.querySelector('meta[name="csrf-token"]').content }),
            });
            const result = await response.json();

            if (response.ok) {
                loadLogbookData();
                alert(result.message || 'Logbook dihapus.');
                return;
            }

            alert(result.message || 'Terjadi kesalahan.');
        }
    });

    loadLogbookData();
</script>
@endpush
