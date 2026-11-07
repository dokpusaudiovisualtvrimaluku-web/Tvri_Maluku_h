@extends('layouts.app')
@section('title', 'Data Tokoh Masyarakat')
@section('page-title', 'Data Tokoh Masyarakat')
@section('breadcrumbs')
    <li>Manajemen Data</li>
    <li>Tokoh Masyarakat</li>
@endsection

@section('page-actions')
    <a href="{{ route('tokohs.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Tokoh Baru
    </a>
@endsection

@section('content')
<div class="card bg-base-100 shadow-xl border border-base-200">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Wilayah</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
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
        ajax: "{{ route('tokohs.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nik', name: 'nik'},
            {data: 'nama_lengkap', name: 'nama_lengkap'},
            {data: 'wilayah', name: 'wilayah'},
            {data: 'kategori_tokoh.name', name: 'kategori_tokoh.name', defaultContent: '-'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('body').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Yakin hapus data?',
            text: "Data yang dihapus beserta foto/dokumen tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "/tokohs/" + id,
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