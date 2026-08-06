document.addEventListener("DOMContentLoaded", function () {

    let anggotaIndex = 0;

    window.tambahAnggota = function () {

        let wrapper = document.getElementById("anggota-wrapper");

        if (!wrapper) return;

        anggotaIndex++;

        let html = `

        <div class="anggota-item card border-0 shadow-sm rounded-3 p-3 mb-3">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <h6 class="mb-0">
                    Anggota ${anggotaIndex}
                </h6>

                <i
                    class="bi bi-trash3-fill text-danger"
                    style="cursor:pointer;font-size:20px;"
                    onclick="hapusAnggota(this)">
                </i>

            </div>

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Nama Anggota
                    </label>

                    <input
                        type="text"
                        name="anggota[${anggotaIndex}][nama]"
                        class="form-control"
                        placeholder="Masukkan nama anggota">

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        Email Anggota
                    </label>

                    <input
                        type="email"
                        name="anggota[${anggotaIndex}][email]"
                        class="form-control"
                        placeholder="Masukkan email anggota">

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        Nomor HP Anggota
                    </label>

                    <input
                        type="text"
                        name="anggota[${anggotaIndex}][no_hp]"
                        class="form-control"
                        placeholder="Masukkan nomor HP anggota">

                </div>

            </div>

        </div>

        `;

        wrapper.insertAdjacentHTML("beforeend", html);

    }

    window.hapusAnggota = function(button){

        button.closest('.anggota-item').remove();

    }

});