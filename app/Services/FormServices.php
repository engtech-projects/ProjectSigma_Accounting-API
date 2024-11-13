<?php

namespace App\Services;

use App\Models\Form;
use App\Enums\FormStatus;
use Illuminate\Http\JsonResponse;

class FormServices
{
    /**
     * Change the status of a form
     *
     * @param int $id Form ID
     * @param string $status New status
     * @return JsonResponse
     */
    public static function changeStatus(int $id, string $status): JsonResponse
    {
        $form = Form::find($id);
        if (!$form) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Form not found',
            ], 404);
        }
        // Attempt to update status
        if ($form->updateStatus($status)) {
            return new JsonResponse([
                'success' => true,
                'message' => 'Form status updated',
                'data' => $form,
            ], 200);
        }
        return new JsonResponse([
            'success' => false,
            'message' => 'Transition not allowed',
            'data' => $form,
        ], 405);
    }
    /**
     * Mark form as approved
     */
    public static function approved(int $id): JsonResponse
    {
        return self::changeStatus($id, FormStatus::APPROVED->value);
    }

    /**
     * Mark form as rejected
     */
    public static function rejected(int $id): JsonResponse
    {
        return self::changeStatus($id, FormStatus::REJECTED->value);
    }

    /**
     * Mark form as pending
     */
    public static function pending(int $id): JsonResponse
    {
        return self::changeStatus($id, FormStatus::PENDING->value);
    }

    /**
     * Mark form as issued
     */
    public static function issued(int $id): JsonResponse
    {
        return self::changeStatus($id, FormStatus::ISSUED->value);
    }
}
