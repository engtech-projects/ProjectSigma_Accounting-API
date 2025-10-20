<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Models\PaymentRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AttachmentViewerController extends Controller
{
    public function __invoke(Request $request)
    {
        $attachments  = [];
        $validatedRequest = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:prf,cash_receipt',
        ]);
        $attachmentId = $validatedRequest['id'];
        try {
            if ($validatedRequest['type'] === PaymentRequestType::PRF->value) {
                $attachment = PaymentRequest::where('id', $attachmentId)->first();
                $attachments = $attachment ? $attachment->attachment_url : [];
            } else {
                $attachment = Voucher::where('id', $attachmentId)->first();
                $attachments = $attachment ? $attachment->attach_file : [];
            }
            if ($attachments && ! empty($attachments)) {
                $attachmentUrls = is_array($attachments) ? $attachments : json_decode($attachments, true); // Ensure array format
                $publicFilePaths = [];
                $type = $validatedRequest['type'];
                foreach ($attachmentUrls as $attachmentUrl) {
                    $originalFilePath = "$type/$attachmentId/$attachmentUrl";
                    $publicFilePath = "storage/$type/$attachmentId/$attachmentUrl";
                    $publicDir = public_path("storage/$type/$attachmentId");
                    if (! file_exists($publicDir)) {
                        mkdir($publicDir, 0777, true);
                    }
                    if (! file_exists(public_path($publicFilePath))) {
                        copy(storage_path("app/$originalFilePath"), public_path($publicFilePath));
                    }
                    $publicFilePaths[] = asset($publicFilePath);
                }
                return view('document-viewer', [
                    'title' => 'Sigma Attachments',
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
