<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class SimasController extends Controller
{
    public function index()
    {
        return view('portal-pages.simas', [
            'bundleJs' => $this->findBundleFile('*.js'),
            'bundleCss' => $this->findBundleFile('*.css'),
        ]);
    }

    private function findBundleFile(string $pattern): ?string
    {
        $matches = File::glob(public_path('simas-assets/assets/'.$pattern));

        return $matches ? basename($matches[0]) : null;
    }
}
