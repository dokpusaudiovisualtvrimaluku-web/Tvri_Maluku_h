<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sop;
use App\Models\NewsroomItem;
use App\Models\HybridBroadcast;
use App\Models\DigitalArchive;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Add SOPs
        Sop::create([
            'sop_number' => 'SOP/2026/01',
            'title' => 'SOP Live Streaming Medsos',
            'category' => 'Live Streaming',
            'version' => '2.0',
            'effective_date' => '2026-08-01',
            'document_path' => 'dummy_sop1.pdf'
        ]);
        Sop::create([
            'sop_number' => 'SOP/2026/02',
            'title' => 'Panduan Resolusi TikTok & IG',
            'category' => 'Produksi',
            'version' => '1.0',
            'effective_date' => '2026-08-05',
            'document_path' => 'dummy_sop2.pdf'
        ]);

        // Add Newsroom Items
        NewsroomItem::create([
            'title' => 'Liputan Banjir Ambon',
            'status' => 'Proses Editing',
            'reporter_id' => 1,
            'duration' => 150, // seconds
            'location' => 'Ambon',
            'file_path' => 'Liputan_Banjir_Ambon.mp4'
        ]);
        NewsroomItem::create([
            'title' => 'Wawancara Gubernur',
            'status' => 'Siap Tayang',
            'reporter_id' => 1,
            'duration' => 600, // seconds
            'location' => 'Kantor Gubernur',
            'file_path' => 'Wawancara_Gubernur.mov'
        ]);
        NewsroomItem::create([
            'title' => 'VO Reporter Desa',
            'status' => 'Draft',
            'reporter_id' => 1,
            'duration' => 75, // seconds
            'location' => 'Desa Tulehu',
            'file_path' => 'VO_Reporter_Desa.wav'
        ]);

        // Add Hybrid Broadcasts
        HybridBroadcast::create([
            'platform' => 'Instagram Reels',
            'title' => 'Pantauan Udara Banjir',
            'caption' => 'Pantauan Udara Banjir di kawasan Batu Merah #BanjirAmbon',
            'hashtags' => '#BanjirAmbon',
            'status' => 'Pending',
            'file_path' => 'Banjir_Reels.mp4'
        ]);
        HybridBroadcast::create([
            'platform' => 'TikTok',
            'title' => 'Wawancara Eksklusif',
            'caption' => 'Wawancara Eksklusif Gubernur #TVRIMaluku',
            'hashtags' => '#TVRIMaluku',
            'status' => 'Published',
            'file_path' => 'Wawancara_TikTok.mp4'
        ]);
        
    }
}
