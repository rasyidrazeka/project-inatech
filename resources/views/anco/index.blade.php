@extends('layouts.template')
@section('title', 'Anco')
@section('content')
    <div class="card">
        <div class="card-header">Kelola Anco</div>
        <div class="card-body">
            <table class="table" id="table_anco">
                <thead>
                    <tr class="text-center">
                        <th>KODE ANCO</th>
                        <th>TANGGAL CEK</th>
                        <th>JAM PEMBERIAN PAKAN </th>
                        <th>KONDISI PAKAN</th>
                        <th>KONDISI UDANG</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade text-left" id="ancoDetailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                <div class="modal-header bg-primary" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 class="modal-title white" id="myModalLabel160">Detail Anco</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    {{-- Modal Detail --}}
                    <div id="user-detail-content" class="container">
                        <div class="row">
                            <!-- <div class="col-md-4">
                                <img id="foto" class="img-fluid rounded mb-3" src="" alt="Foto Pengguna"
                                    style="max-width: 100%; height: auto;">
                            </div> -->
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Kode Anco : </th>
                                        <td id="kd_anco"></td>
                                    </tr>
                                    <tr>
                                        <th>Fase Kolam : </th>
                                        <td id="kd_fase_kolam"></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Cek : </th>
                                        <td id="tanggal_cek"></td>
                                    </tr>
                                    <tr>
                                        <th>waktu Cek : </th>
                                        <td id="waktu_cek"></td>
                                    </tr>
                                    <tr>
                                        <th>Pemberian Pakan : </th>
                                        <td id="pemberian_pakan"></td>
                                    </tr>
                                    <tr>
                                        <th>Jam Pemberian Pakan : </th>
                                        <td id="jamPemberian_pakan"></td>
                                    </tr>
                                    <tr>
                                        <th>kondisi_pakan : </th>
                                        <td id="kondisi_pakan"></td>
                                    </tr>
                                    <tr>
                                        <th>kondisi_udang : </th>
                                        <td id="kondisi_udang"></td>
                                    </tr>
                                    <tr>
                                        <th>Catatan : </th>
                                        <td id="catatan"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <button type="button" class="btn btn-danger" id="btn-delete-anco">Hapus</button>
                    <button type="button" class="btn btn-primary" id="btn-edit-anco">Edit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        var currentAncoId;
        $(document).ready(function() {
            var dataAnco = $('#table_anco').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('anco/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "error": function(xhr, error, thrown) {
                        console.error('Error fetching data: ', thrown);
                    }
                },
                columns: [{
                        data: "kd_anco",
                        className: "", // Jika tidak ada class, hapus baris ini
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            var url = '{{ route('anco.show', ':id') }}';
                            url = url.replace(':id', row.id_anco);
                            return '<a href="javascript:void(0);" data-id="' + row.id_anco +
                                '" class="view-user-details" data-url="' + url +
                                '" data-toggle="modal" data-target="#ancoDetailModal">' + data +
                                '</a>';
                        }
                    },
                    {
                        data: "tanggal_cek",
                        className: "", // Jika tidak ada class, hapus baris ini
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "jamPemberian_pakan",
                        className: "", // Jika tidak ada class, hapus baris ini
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kondisi_pakan",
                        className: "", // Jika tidak ada class, hapus baris ini
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kondisi_udang",
                        className: "", // Jika tidak ada class, hapus baris ini
                        orderable: true,
                        searchable: true
                    },
                ],
                pagingType: "simple_numbers", // Tambahkan ini untuk menampilkan angka pagination
                dom: 'frtip', // Mengatur layout DataTables
                language: {
                    search: "" // Menghilangkan teks "Search"
                }
            });

            // Event listener untuk menampilkan detail anco
            $(document).on('click', '.view-user-details', function() {
                var url = $(this).data('url');
                currentAncoId = $(this).data('id');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.html) {
                            // Load konten detail ke modal
                            $('#user-detail-content').html(response.html);
                            $('#ancoDetailModal').modal('show');

                            // Tambahkan tombol edit secara dinamis
                            var editButton =
                                '<button type="button" class="btn btn-primary" id="btn-edit-anco">Edit</button>';
                            $('#user-detail-content').append(editButton);
                        } else {
                            alert('Gagal memuat detail anco');
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Gagal memuat detail anco');
                    }
                });
            });

            $(document).on('click', '#btn-edit-anco', function() {
                if (currentAncoId) {
                    var editUrl = '{{ route('anco.edit', ':id') }}'.replace(':id', currentAncoId);
                    window.location.href = editUrl;
                } else {
                    alert('ID anco tidak ditemukan');
                }
            });

            $(document).on('click', '#btn-delete-anco', function() {
                if (currentAncoId) {
                    if (confirm('Apakah Anda yakin ingin menghapus data anco ini?')) {
                        var deleteUrl = '{{ route('anco.destroy', ':id') }}'.replace(':id',
                            currentAncoId);

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                $('#ancoDetailModal').modal('hide');
                                // Reload DataTable
                                $('#table_anco').DataTable().ajax.reload();
                                alert('Data Anco berhasil dihapus');
                            },
                            error: function(xhr) {
                                alert('Gagal menghapus data anco: ' + xhr.responseText);
                            }
                        });
                    }
                } else {
                    alert('Data Anco tidak ditemukan');
                }
            });


            // Tambahkan tombol "Tambah" setelah kolom pencarian
            $("#table_anco_filter").append(
                '<select class="form-control" name="id_fase_tambak" id="id_fase_tambak" required style="margin-left: 30px; width: 150px;">' +
                '<option value="">- SEMUA -</option>' +
                '@foreach ($fase_kolam as $item)' +
                '<option value="{{ $item->id_fase_tambak }}">{{ $item->kd_fase_tambak }}</option>' +
                '@endforeach' +
                '</select>' +
                '<button id="btn-tambah" class="btn btn-primary ml-2">Tambah</button>');

            // Tambahkan event listener untuk tombol tambah 
            $("#btn-tambah").on('click', function() {
                window.location.href =
                    "{{ url('anco/create') }}"; // Arahkan ke halaman tambah pengguna
            });
            // Menambahkan placeholder pada kolom search
            $('input[type="search"]').attr('placeholder', 'Cari data anco...');
            $('#id_fase_tambak').on('change', function() {
                dataAnco.ajax.reload();
            })
        });
    </script>
@endpush
