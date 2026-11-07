<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvriVisitorStat extends Model
{
    use HasFactory;

    protected $table = 'tvri_visitor_stats';

    protected $fillable = [
        'portal_key',
        'hari_ini_kunjungan',
        'hari_ini_unik',
        'bulan_ini_kunjungan',
        'bulan_ini_unik',
        'total_kunjungan',
        'total_unik',
        'last_visit_date',
        'updated_by',
    ];

    protected $casts = [
        'hari_ini_kunjungan' => 'integer',
        'hari_ini_unik' => 'integer',
        'bulan_ini_kunjungan' => 'integer',
        'bulan_ini_unik' => 'integer',
        'total_kunjungan' => 'integer',
        'total_unik' => 'integer',
        'last_visit_date' => 'date',
    ];

    /**
     * Singleton instance retriever
     */
    public static function getOrCreate(): self
    {
        return self::firstOrCreate(
            ['portal_key' => 'tvri_maluku_hub'],
            [
                'hari_ini_kunjungan' => 248,
                'hari_ini_unik' => 236,
                'bulan_ini_kunjungan' => 5420,
                'bulan_ini_unik' => 4890,
                'total_kunjungan' => 168940,
                'total_unik' => 142300,
                'last_visit_date' => now()->toDateString(),
                'updated_by' => 'Super Admin',
            ]
        );
    }

    /**
     * Reset visitor stats to 0
     */
    public static function resetStats(): self
    {
        $stat = self::getOrCreate();
        $stat->update([
            'hari_ini_kunjungan' => 0,
            'hari_ini_unik' => 0,
            'bulan_ini_kunjungan' => 0,
            'bulan_ini_unik' => 0,
            'total_kunjungan' => 0,
            'total_unik' => 0,
            'last_visit_date' => now()->toDateString(),
            'updated_by' => auth()->user()?->name ?? 'Super Admin',
        ]);
        return $stat;
    }

    /**
     * Restore default TVRI baseline stats
     */
    public static function applyBaselineStats(): self
    {
        $stat = self::getOrCreate();
        $stat->update([
            'hari_ini_kunjungan' => 248,
            'hari_ini_unik' => 236,
            'bulan_ini_kunjungan' => 5420,
            'bulan_ini_unik' => 4890,
            'total_kunjungan' => 168940,
            'total_unik' => 142300,
            'last_visit_date' => now()->toDateString(),
            'updated_by' => auth()->user()?->name ?? 'Super Admin',
        ]);
        return $stat;
    }

    /**
     * Record real-time visitor
     */
    public static function recordVisit(bool $isUnique = false): array
    {
        $stat = self::getOrCreate();
        $today = now()->toDateString();

        // Increment visit counters
        $stat->hari_ini_kunjungan += 1;
        $stat->bulan_ini_kunjungan += 1;
        $stat->total_kunjungan += 1;

        if ($isUnique) {
            $stat->hari_ini_unik += 1;
            $stat->bulan_ini_unik += 1;
            $stat->total_unik += 1;
        }

        $stat->last_visit_date = $today;
        $stat->save();

        return $stat->toFormattedArray();
    }

    /**
     * Format numbers with Indonesian separators
     */
    public function toFormattedArray(): array
    {
        return [
            'hari_ini_kunjungan' => number_format($this->hari_ini_kunjungan, 0, '.', ','),
            'hari_ini_unik' => number_format($this->hari_ini_unik, 0, '.', ','),
            'bulan_ini_kunjungan' => number_format($this->bulan_ini_kunjungan, 0, '.', ','),
            'bulan_ini_unik' => number_format($this->bulan_ini_unik, 0, '.', ','),
            'total_kunjungan' => number_format($this->total_kunjungan, 0, '.', ','),
            'total_unik' => number_format($this->total_unik, 0, '.', ','),
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s'),
            'raw' => [
                'hari_ini_kunjungan' => $this->hari_ini_kunjungan,
                'hari_ini_unik' => $this->hari_ini_unik,
                'bulan_ini_kunjungan' => $this->bulan_ini_kunjungan,
                'bulan_ini_unik' => $this->bulan_ini_unik,
                'total_kunjungan' => $this->total_kunjungan,
                'total_unik' => $this->total_unik,
            ],
        ];
    }
}
