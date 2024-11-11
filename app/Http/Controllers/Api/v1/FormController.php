<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Enums\FormStatus;

class FormController extends Controller
{

	public function changeStatus(int $id, FormStatus $status)
	{
		$form = Form::find($id);

		if (!$form) {
			return response()->json(['error' => 'Form not found'], 404);
		}
		// Attempt to update status
		if ($form->updateStatus($status)) {
			return response()->json(['message' => 'Form status updated', 'form' => $form], 200);
		} else {
			return response()->json(['error' => 'Transition not allowed', 'form' => $form], 405);
		}
	}

	public function approved(int $id)
	{
		return $this->changeStatus($id, FormStatus::Approved);
	}

	public function rejected(int $id)
	{
		return $this->changeStatus($id, FormStatus::Rejected);
	}

	public function pending(int $id)
	{
		return $this->changeStatus($id, FormStatus::Pending);
	}

	public function issued(int $id)
	{
		return $this->changeStatus($id, FormStatus::Issued);
	}
}