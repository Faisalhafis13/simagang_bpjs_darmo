@extends('layouts.back-office')
@section('title','Monitoring Logbook (Mentor)')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4 class="mb-0">
                Monitoring Logbook Peserta
            </h4>
        </div>


        <div class="card-body">


            <div class="mb-3">

                <label class="form-label">
                    Pilih Peserta
                </label>


                <select 
                    id="peserta"
                    class="form-select">

                    <option value="">
                        -- Pilih Peserta --
                    </option>

                </select>

            </div>



            <hr>


            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Aktivitas
                            </th>

                            <th>
                                Hasil
                            </th>

                            <th>
                                Catatan
                            </th>

                        </tr>

                    </thead>


                    <tbody id="logbookData">

                        <tr>

                            <td 
                            colspan="4"
                            class="text-center">

                                Pilih peserta terlebih dahulu

                            </td>

                        </tr>

                    </tbody>


                </table>

            </div>


        </div>

    </div>


</div>



@endsection



@push('js')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function(){

        loadPeserta();


        document
        .getElementById('peserta')
        .addEventListener(
            'change',
            function(){

                let user_id = this.value;


                if(user_id){

                    loadLogbook(user_id);

                }

            }
        );


    }
);



function loadPeserta()
{

    fetch(
        "{{ route('mentor.logbook.peserta') }}"
    )

    .then(response => response.json())

    .then(data => {


        let select =
        document.getElementById('peserta');


        data.forEach(item => {


            select.innerHTML += `

            <option value="${item.id}">
                ${item.name}
            </option>

            `;


        });


    });

}





function loadLogbook(user_id)
{

    fetch(
        "{{ route('mentor.logbook.data') }}?user_id="+user_id
    )

    .then(response => response.json())

    .then(data => {


        let tbody =
        document.getElementById('logbookData');


        tbody.innerHTML = '';



        if(data.length == 0){


            tbody.innerHTML = `

            <tr>

                <td colspan="4"
                class="text-center">

                    Belum ada logbook

                </td>

            </tr>

            `;

            return;

        }




        data.forEach(item => {


            tbody.innerHTML += `

            <tr>

                <td>
                    ${item.tanggal.substring(0,10)}
                </td>


                <td>
                    ${item.aktivitas}
                </td>


                <td>
                    ${item.hasil}
                </td>


                <td>
                    ${item.catatan ?? '-'}
                </td>


            </tr>

            `;


        });


    });

}

</script>

@endpush