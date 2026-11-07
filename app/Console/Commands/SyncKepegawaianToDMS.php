<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatKepegawaianLayanan;
use App\Models\SimandatDocument;

class SyncKepegawaianToDMS extends Command
{
    protected $signature = 'dms:sync-kepegawaian';
    protected $description = 'Sync approved Kepegawaian services to DMS';

    public function handle()
    {
        $layanans = SimandatKepegawaianLayanan::where('status_layanan', 'SELESAI')->get();
        $count = 0;
        foreach ($layanans as $layanan) {
            // Find existing
            $doc = SimandatDocument::where('document_number', $layanan->nomor_sk_surat ?? $layanan->nomor_agenda)->first();
            if (!$doc) {
                $doc = new SimandatDocument();
            }

            $doc->document_number = $layanan->nomor_sk_surat ?? $layanan->nomor_agenda;
            $doc->title = $layanan->jenis_layanan . ' - ' . $layanan->nama_pemohon;
            $doc->category = 'Surat Keputusan (SK)'; // default
            if (str_contains(strtolower($layanan->jenis_layanan), 'surat') || str_contains(strtolower($layanan->jenis_layanan), 'naskah')) {
                $doc->category = 'Surat Keputusan (SK)';
            }
            
            $doc->division = 'Sekretariat';
            $doc->description = 'Hasil layanan dari Admin TU: ' . $layanan->nomor_agenda;
            $doc->document_date = $layanan->tanggal_selesai ?? $layanan->updated_at;
            $doc->year = date('Y', strtotime($doc->document_date));
            $doc->confidentiality = 'PUBLIK';
            $doc->status = 'AKTIF';
            $doc->current_version = 'v1.0';
            $doc->file_type = 'PDF';
            $doc->file_size = 1024; // dummy 1MB
            $doc->uploader_name = 'Sistem Admin TU';
            $doc->save();
            $count++;
        }

        $this->info("Synced {$count} records to DMS.");
    }
}
