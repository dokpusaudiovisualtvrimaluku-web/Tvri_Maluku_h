@extends('layouts.app')
@section('title', 'Tambah Tokoh Masyarakat')
@section('page-title', 'Tambah Tokoh Baru')
@section('breadcrumbs')
    <li>Manajemen Data</li>
    <li><a href="{{ route('tokohs.index') }}">Tokoh Masyarakat</a></li>
    <li>Tambah</li>
@endsection

@section('content')
<form action="{{ route('tokohs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Foto & Dokumen -->
        <div class="col-span-1 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Foto Profil</h2>
                    <div class="form-control w-full mt-2">
                        <input type="file" name="foto" accept="image/*" class="file-input file-input-bordered file-input-primary w-full" />
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Dokumen Pendukung</h2>
                    <div class="form-control w-full mt-2">
                        <label class="label"><span class="label-text">Dokumen KTP (PDF/Image)</span></label>
                        <input type="file" name="dokumen_ktp" class="file-input file-input-bordered w-full" />
                    </div>
                    <div class="form-control w-full mt-2">
                        <label class="label"><span class="label-text">Dokumen KK (PDF/Image)</span></label>
                        <input type="file" name="dokumen_kk" class="file-input file-input-bordered w-full" />
                    </div>
                </div>
            </div>
            
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Kategori & Status</h2>
                    <div class="form-control w-full mt-2">
                        <label class="label"><span class="label-text">Kategori Tokoh <span class="text-error">*</span></span></label>
                        <select name="kategori_tokoh_id" class="select select-bordered w-full" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_tokoh_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control mt-4">
                        <label class="label cursor-pointer justify-start gap-4">
                            <span class="label-text">Status Aktif</span> 
                            <input type="hidden" name="status_aktif" value="0">
                            <input type="checkbox" name="status_aktif" value="1" class="toggle toggle-primary" {{ old('status_aktif', true) ? 'checked' : '' }} />
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Biodata Lengkap -->
        <div class="col-span-1 lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Biodata Diri</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="form-control">
                            <label class="label"><span class="label-text">NIK (16 Digit) <span class="text-error">*</span></span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" class="input input-bordered" required maxlength="16" />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Nama Lengkap <span class="text-error">*</span></span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="input input-bordered" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Tempat Lahir <span class="text-error">*</span></span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="input input-bordered" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Tanggal Lahir <span class="text-error">*</span></span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="input input-bordered" required />
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Jenis Kelamin <span class="text-error">*</span></span></label>
                            <select name="jenis_kelamin" class="select select-bordered" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Agama <span class="text-error">*</span></span></label>
                            <select name="agama_id" class="select select-bordered" required>
                                <option value="">Pilih Agama</option>
                                @foreach($agamas as $a)
                                    <option value="{{ $a->id }}" {{ old('agama_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wilayah -->
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Wilayah Administrasi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Provinsi <span class="text-error">*</span></span></label>
                            <select name="provinsi_id" id="provinsi_id" class="select select-bordered" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsis as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Kabupaten/Kota <span class="text-error">*</span></span></label>
                            <select name="kabupaten_id" id="kabupaten_id" class="select select-bordered" required disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Kecamatan <span class="text-error">*</span></span></label>
                            <select name="kecamatan_id" id="kecamatan_id" class="select select-bordered" required disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Desa/Kelurahan <span class="text-error">*</span></span></label>
                            <select name="desa_id" id="desa_id" class="select select-bordered" required disabled>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text">Alamat Lengkap <span class="text-error">*</span></span></label>
                            <textarea name="alamat" class="textarea textarea-bordered h-24" required>{{ old('alamat') }}</textarea>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">RT/RW</span></label>
                            <input type="text" name="rt_rw" value="{{ old('rt_rw') }}" class="input input-bordered" placeholder="001/002" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <a href="{{ route('tokohs.index') }}" class="btn">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Data</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#provinsi_id').change(function() {
        var provID = $(this).val();
        if(provID) {
            $.get('/ajax/kabupatens?provinsi_id='+provID, function(data) {
                $('#kabupaten_id').empty().append('<option value="">Pilih Kabupaten</option>').removeAttr('disabled');
                $('#kecamatan_id').empty().append('<option value="">Pilih Kecamatan</option>').attr('disabled', 'disabled');
                $('#desa_id').empty().append('<option value="">Pilih Desa</option>').attr('disabled', 'disabled');
                $.each(data, function(key, value) {
                    $('#kabupaten_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            });
        } else {
            $('#kabupaten_id, #kecamatan_id, #desa_id').empty().attr('disabled', 'disabled');
        }
    });

    $('#kabupaten_id').change(function() {
        var kabID = $(this).val();
        if(kabID) {
            $.get('/ajax/kecamatans?kabupaten_id='+kabID, function(data) {
                $('#kecamatan_id').empty().append('<option value="">Pilih Kecamatan</option>').removeAttr('disabled');
                $('#desa_id').empty().append('<option value="">Pilih Desa</option>').attr('disabled', 'disabled');
                $.each(data, function(key, value) {
                    $('#kecamatan_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            });
        } else {
            $('#kecamatan_id, #desa_id').empty().attr('disabled', 'disabled');
        }
    });

    $('#kecamatan_id').change(function() {
        var kecID = $(this).val();
        if(kecID) {
            $.get('/ajax/desas?kecamatan_id='+kecID, function(data) {
                $('#desa_id').empty().append('<option value="">Pilih Desa</option>').removeAttr('disabled');
                $.each(data, function(key, value) {
                    $('#desa_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            });
        } else {
            $('#desa_id').empty().attr('disabled', 'disabled');
        }
    });
});
</script>
@endpush