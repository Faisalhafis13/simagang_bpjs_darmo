@extends('layouts.public')

@section('title', 'Lihat Hasil')

@section('public-content')

<section class="hasil-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">

            <div class="hasil-card">

                {{-- Icon --}}
                <div class="hasil-icon mb-4">
                    <i class="bi bi-search"></i>
                </div>

                {{-- Header --}}
                <div class="text-center mb-4">
                    <span class="badge-system mb-3">
                        Status Pengajuan
                    </span>

                    <h2 class="fw-bold mb-3">
                        Lihat Hasil Pengajuan
                    </h2>

                    <p class="text-muted mb-0">
                        Masukkan kode pengajuan yang telah diberikan
                        untuk melihat status pengajuan magang Anda.
                    </p>
                </div>

                {{-- Form --}}
                <form id="formHasil">

                    @csrf

                    <div class="mb-3">
                        <label
                            for="kode_pengajuan"
                            class="form-label fw-semibold"
                        >
                            Kode Pengajuan
                        </label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text">
                                <i class="bi bi-upc-scan"></i>
                            </span>

                            <input
                                type="text"
                                name="kode_pengajuan"
                                id="kode_pengajuan"
                                class="form-control"
                                placeholder="Contoh: MAGANG-AB12CD34"
                                autocomplete="off"
                                required
                            >
                        </div>

                        <small class="text-muted d-block mt-2">
                            Gunakan kode pengajuan yang Anda terima
                            setelah mengirim formulir.
                        </small>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100 py-3 mt-3"
                    >
                        <i class="bi bi-search me-2"></i>
                        Cek Status Pengajuan
                    </button>

                </form>

                {{-- Result --}}
                <div
                    id="hasilPengajuan"
                    class="mt-4"
                    style="display:none;"
                >
                </div>

            </div>

        </div>
    </div>
</div>

</section>

@endsection
