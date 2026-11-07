<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\SimandatDocument;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    /**
     * Public Verification Portal for QR-coded Documents.
     */
    public function verify($token)
    {
        $document = SimandatDocument::with(['folder', 'school', 'culture', 'uploader'])
            ->where('qr_token', $token)
            ->first();

        $isValid = ($document !== null);

        return view('dokumen.verify', compact('document', 'isValid', 'token'));
    }
}
