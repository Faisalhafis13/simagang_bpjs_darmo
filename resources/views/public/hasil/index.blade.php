@extends('layouts.public')

@section('title','Lihat Hasil')

@section('public-content')

<section class="py-5 mt-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow border-0 rounded-4">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h2 class="fw-bold">
                                Lihat Hasil Pengajuan
                            </h2>

                            <p class="text-muted">
                                Masukkan kode pengajuan yang telah diberikan.
                            </p>

                        </div>

                        <form id="formHasil">

                            @csrf

                            <div class="input-group">

                                <input
                                    type="text"
                                    name="kode_pengajuan"
                                    class="form-control"
                                    placeholder="Contoh : MAGANG-AB12CD34"
                                    required>

                                <button
                                    class="btn btn-primary">

                                    Cek Status

                                </button>

                            </div>

                        </form>

                        <div
                            id="hasilPengajuan"
                            class="mt-5"
                            style="display:none;">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection