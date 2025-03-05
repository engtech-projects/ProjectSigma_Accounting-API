<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Storage;

class PdfViewerController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedRequest = $request->validate([
            'id' => 'required|integer|exists:payment_request,id',
        ]);

        $prfId = $validatedRequest['id'];

        try {
            $prf = PaymentRequest::where('id', $prfId)->first();

            if ($prf) {
                $fileExtension = pathinfo($prf->attachment_url, PATHINFO_EXTENSION);
                $fileType = in_array($fileExtension, ['jpeg', 'png', 'jpg']) ? 'image' : 'pdf';
                $originalFilePath = "prf/$prfId/{$prf->attachment_url}";
                $publicFilePath = "storage/prf/$prfId/{$prf->attachment_url}";
                $publicDir = public_path("storage/prf/$prfId");
                if (!file_exists($publicDir)) {
                    mkdir($publicDir, 0777, true);
                }
                if (!file_exists(public_path($publicFilePath))) {
                    copy(storage_path("app/$originalFilePath"), public_path($publicFilePath));
                }
                $pdfUrl = asset($publicFilePath);

                return view('pdf-viewer', [
                    'pdfPath' => $pdfUrl,
                    'fileType' => $fileType
                ]);
            }

            throw new \Exception('PDF Not Found');
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }

}

}
