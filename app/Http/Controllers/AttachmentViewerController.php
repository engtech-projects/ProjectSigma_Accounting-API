<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Models\PaymentRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Str;

class AttachmentViewerController extends Controller
{
    public function showDocumentViewer($cacheKey)
    {
        $attachments = [];
        if (!Cache::has($cacheKey)) {
            return view('errors.document-not-found', [
                'message' => 'Document not found or cache key expired.'
            ]);
        }
        $cacheData = Cache::get($cacheKey);
        $type = $cacheData['type'];
        $attachmentId = $cacheData['id'];
        if ($type === PaymentRequestType::PRF->value) {
            $attachment = PaymentRequest::where('id', $attachmentId)->first();
            $attachments = $attachment ? $attachment->attachment_url : [];
        } else {
            $attachment = Voucher::where('id', $attachmentId)->first();
            $attachments = $attachment ? $attachment->attach_file : [];
        }
        if ($attachments && ! empty($attachments)) {
            $attachmentUrls = is_array($attachments) ? $attachments : json_decode($attachments, true); // Ensure array format
            $publicFilePaths = [];
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
        return view('errors.document-not-found', [
            'message' => 'Document not found or cache key expired.'
        ]);
    }

    public function getDocumentViewerLink(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'id' => 'required',
        ]);
        $uniqueKey = Str::random(15);
        Cache::put($uniqueKey, [
            'type' => $request->type,
            'id' => $request->id,
        ], now()->addMinutes(10));
        $webViewerUrl = route('web.document.viewer', ['cacheKey' => $uniqueKey]);
        return response()->json([
            'success' => true,
            'message' => 'Document viewer link generated successfully.',
            'url' => $webViewerUrl,
        ], 200);
    }
}
