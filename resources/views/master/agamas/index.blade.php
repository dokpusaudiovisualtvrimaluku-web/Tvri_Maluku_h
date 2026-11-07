@extends('layouts.app')
@section('title', 'Data Agama')
@section('page-title', 'Manajemen Agama')
@section('breadcrumbs')
    <li>Master Data</li>
    <li>Agama</li>
@endsection

@section('content')
<div class="card bg-base-100 shadow-xl border border-base-200">
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <h2 class="card-title">Daftar Agama</h2>
            <button class="btn btn-primary" onclick="addModal.showModal()">
                <i class="fa-solid fa-plus"></i> Tambah Data
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Agama</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<dialog id="addModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="modalTitle">Tambah Agama</h3>
        <form id="dataForm" class="mt-4">
            <input type="hidden" id="data_id" name="id">
            <div class="form-control w-full mb-4">
                <label class="label"><span class="label-text">Nama Agama</span></label>
                <input type="text" id="name" name="name" class="input input-bordered w-full" required />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="addModal.close(); $('#dataForm')[0].reset(); $('#data_id').val('');">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('master.agamas.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#dataForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#data_id').val();
        var url = id ? "/master/agamas/" + id : "{{ route('master.agamas.store') }}";
        var type = id ? "PUT" : "POST";
        
        $.ajax({
            url: url,
            type: type,
            data: $(this).serialize(),
            success: function(res) {
                document.getElementById('addModal').close();
                $('#dataForm')[0].reset();
                $('#data_id').val('');
                table.draw();
                Swal.fire('Sukses!', res.success, 'success');
            },
            error: function(err) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        });
    });

    $('body').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#data_id').val(id);
        $('#name').val(name);
        $('#modalTitle').text('Edit Agama');
        document.getElementById('addModal').showModal();
    });

    $('body').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Yakin hapus data?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "/master/agamas/" + id,
                    success: function(res) {
                        table.draw();
                        Swal.fire('Terhapus!', res.success, 'success');
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush