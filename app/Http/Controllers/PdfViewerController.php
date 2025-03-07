<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use Illuminate\Http\Request;

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

            if ($prf && !empty($prf->attachment_url)) {
                $attachmentUrls = is_array($prf->attachment_url) ? $prf->attachment_url : json_decode($prf->attachment_url, true); // Ensure array format
                $publicFilePaths = [];

                foreach ($attachmentUrls as $attachmentUrl) {
                    $originalFilePath = "prf/$prfId/$attachmentUrl";
                    $publicFilePath = "storage/prf/$prfId/$attachmentUrl";
                    $publicDir = public_path("storage/prf/$prfId");

                    if (!file_exists($publicDir)) {
                        mkdir($publicDir, 0777, true);
                    }
                    if (!file_exists(public_path($publicFilePath))) {
                        copy(storage_path("app/$originalFilePath"), public_path($publicFilePath));
                    }

                    $publicFilePaths[] = asset($publicFilePath);
                }
                $pdfUrl = asset($publicFilePath);

                return view('pdf-viewer', [
                    'title' => 'Sigma Payment Request Attachments',
                    'publicFilePaths' => $publicFilePaths,
                ]);
            }

            throw new \Exception('Attachments Not Found');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 404);
        }
    }

}
