<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TokohRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $rules = [
            'nik' => 'required|string|size:16|unique:tokohs,nik' . ($this->tokoh ? ',' . $this->tokoh->id : ''),
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama_id' => 'required|exists:agamas,id',
            'suku_id' => 'required|exists:sukus,id',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:10',
            'provinsi_id' => 'required|exists:provinsis,id',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'no_hp' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'profesi_id' => 'required|exists:profesis,id',
            'pendidikan_id' => 'required|exists:pendidikans,id',
            'pekerjaan' => 'nullable|string|max:255',
            'organisasi_id' => 'nullable|exists:organisasis,id',
            'jabatan' => 'nullable|string|max:255',
            'kategori_tokoh_id' => 'required|exists:kategori_tokohs,id',
            'status_aktif' => 'boolean',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
            // File uploads
            'dokumen_ktp' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'dokumen_kk' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];
        
        return $rules;
    }
}