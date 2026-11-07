<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DisabilitasAnak;
use App\Models\DisabilitasDewasa;
use App\Models\MarqueeAnnouncement;
use App\Models\Organization;
use App\Models\Tag;
use App\Models\Urusan;
use App\Models\TvriVisitorStat;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index()
    {
        $currentOpd = Organization::where('id', 'tvri-maluku')
            ->orWhere('id', 'dinas-pendidikan-kebudayaan')
            ->first() ?? Organization::first();

        if (! $currentOpd) {
            $currentOpd = Organization::create([
                'id' => 'tvri-maluku',
                'name' => 'TVRI Maluku',
                'description' => 'TVRI Maluku menyelenggarakan kegiatan penyiaran publik melalui produksi, pengelolaan, dan penyebarluasan informasi, pendidikan, hiburan yang sehat, serta pelestarian nilai-nilai budaya dan kearifan lokal masyarakat Maluku. TVRI Maluku berperan dalam menyajikan informasi yang edukatif, informatif, dan berimbang, sekaligus mendukung pengembangan seni, budaya, dan potensi daerah Maluku melalui berbagai program siaran.',
                'phone' => '(0911) 341851',
                'address' => 'Jl. Perumtel Gunung Nona Ambon Kec. Nusaniwe, Kota Ambon, Maluku 97124',
                'website' => 'https://ppid.tvrimaluku.id',
                'email' => 'humas@tvri.go.id',
                'logo' => '/images/logo-tvri.png',
                'dataset_count' => 0,
                'sort_order' => 1,
            ]);
        }

        $todayKey = 'tvri_visited_' . date('Y-m-d');
        $isUnique = !session()->has($todayKey);
        if ($isUnique) {
            session()->put($todayKey, true);
        }
        $visitorStats = TvriVisitorStat::recordVisit($isUnique);

        $payload = [
            'VISITOR_STATS' => $visitorStats,
            'CURRENT_OPD' => [
                'id' => $currentOpd->id ?? 'tvri-maluku',
                'name' => $currentOpd->name ?? 'TVRI Maluku',
                'description' => $currentOpd->description ?? '',
                'phone' => $currentOpd->phone ?? '(0911) 341851',
                'address' => $currentOpd->address ?? 'Jl. Perumtel Gunung Nona Ambon Kec. Nusaniwe, Kota Ambon, Maluku 97124',
                'website' => $currentOpd->website ?? 'https://ppid.tvrimaluku.id',
                'email' => $currentOpd->email ?? 'humas@tvri.go.id',
                'logo' => $currentOpd->logo ?? '/images/logo-tvri.png',
                'datasetCount' => $currentOpd->dataset_count ?? 0,
            ],
            'ALL_OPDS' => Organization::orderBy('sort_order')
                ->get(['id', 'name', 'dataset_count'])
                ->map(fn ($o) => ['id' => $o->id, 'name' => $o->name, 'count' => $o->dataset_count])
                ->all(),
            'URUSAN_LIST' => Urusan::orderBy('sort_order')
                ->get(['id', 'name'])
                ->map(fn ($u) => ['id' => $u->id, 'name' => $u->name])
                ->all(),
            'ALL_TAGS' => Tag::orderBy('sort_order')->pluck('name')->all(),
            'DATASETS' => Dataset::with(['organization', 'urusan'])->orderBy('id')
                ->get()
                ->map(fn ($d) => array_filter([
                    'id' => $d->id,
                    'slug' => $d->slug,
                    'title' => $d->title,
                    'organization' => $d->organization?->name ?? 'TVRI Maluku',
                    'organizationId' => $d->organization_id ?? 'tvri-maluku',
                    'urusan' => $d->urusan?->name ?? 'TVRI Maluku',
                    'urusanId' => $d->urusan_id ?? 1,
                    'date' => $d->date,
                    'views' => $d->views,
                    'downloads' => $d->downloads,
                    'description' => $d->description,
                    'tags' => $d->tags,
                    'columns' => $d->columns,
                    'rows' => $d->rows,
                    'chartConfig' => $d->chart_config
                        ? [
                            'type' => $d->chart_config['type'],
                            'labelKey' => $d->chart_config['labelKey'],
                            'valueKeys' => $d->chart_config['valueKeys'],
                            'colors' => $d->chart_config['colors'] ?? null,
                        ]
                        : null,
                ], fn ($v) => $v !== null))
                ->all(),
            'DISABILITAS_DEWASA' => [
                'disabilitas' => DisabilitasDewasa::orderBy('sort_order')->pluck('disabilitas')->all(),
                'pria' => DisabilitasDewasa::orderBy('sort_order')->pluck('pria')->all(),
                'wanita' => DisabilitasDewasa::orderBy('sort_order')->pluck('wanita')->all(),
            ],
            'DISABILITAS_ANAK' => $this->disabilitasAnakPayload(),
            'MARQUEE_ANNOUNCEMENTS' => MarqueeAnnouncement::orderBy('sort_order')->pluck('announcement')->all(),
        ];

        return view('portal-pages.home', [
            'simandat' => $payload,
            'bundleJs' => $this->findBundleFile('*.js'),
            'bundleCss' => $this->findBundleFile('*.css'),
        ]);
    }

    private function disabilitasAnakPayload(): array
    {
        $series = DisabilitasAnak::orderBy('sort_order')->get();

        return [
            'kelompokUsia' => [
                '0 - 4 Tahun',
                '5 - 9 Tahun',
                '10 - 14 Tahun',
                '15 - 17 Tahun (Usia SMA/SMK)',
            ],
            'jenisDisabilitas' => $series->pluck('name')->all(),
            'series' => $series
                ->map(fn ($s) => [
                    'name' => $s->name,
                    'data' => [$s->usia_0_4, $s->usia_5_9, $s->usia_10_14, $s->usia_15_17],
                    'color' => $s->color,
                ])
                ->all(),
        ];
    }

    private function findBundleFile(string $pattern): ?string
    {
        $matches = File::glob(public_path('react/assets/'.$pattern));

        return $matches ? basename($matches[0]) : null;
    }
}
