document.addEventListener("DOMContentLoaded", () => {
    console.log("SIMAGANG BPJS");
});
document.addEventListener("DOMContentLoaded", function () {

    const swiper = new Swiper(".gallerySwiper", {

        loop: true,

        speed: 700,

        spaceBetween: 25,

        slidesPerView: 1,

        grabCursor: true,

        centeredSlides: false,

        autoplay: {

            delay: 3000,

            disableOnInteraction: false,

        },

        navigation: {

            nextEl: ".swiper-button-next",

            prevEl: ".swiper-button-prev",

        },

        pagination: {

            el: ".swiper-pagination",

            clickable: true,

        },

        breakpoints: {

            768: {

                slidesPerView: 2,

            },

            1200: {

                slidesPerView: 3,

            }

        }

    });

});

document.addEventListener("DOMContentLoaded", function () {

window.tambahAnggota = function () {

    let wrapper = document.getElementById("anggota-wrapper");

    if (!wrapper) return;

    let jumlah = wrapper.querySelectorAll(".anggota-item").length + 1;

    let html = `

        <div class="anggota-item card border-0 shadow-sm rounded-3 p-3 mb-3">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <h6 class="mb-0">
                    Anggota ${jumlah}
                </h6>

                <i
                    class="bi bi-trash3-fill text-danger"
                    style="cursor:pointer;font-size:20px;"
                    onclick="hapusAnggota(this)">
                </i>

            </div>

            <input
                type="text"
                name="anggota[]"
                class="form-control"
                placeholder="Masukkan nama anggota">

        </div>

    `;

    wrapper.insertAdjacentHTML("beforeend", html);

}
    window.hapusAnggota = function(button){

        button.closest('.anggota-item').remove();

    }

});