<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Enums\FormStatus;

class FormController extends Controller
{

	public function updateStatus(int $id, FormStatus $status)
	{
		$form = Form::find($id);

		if (!$form) {
			return response()->json(['error' => 'Form not found'], 404);
		}
		// Attempt to update status
		if ($form->updateStatus($status)) {
			return response()->json(['message' => 'Form status updated', 'form' => $form], 200);
		}
	}

	public function approved(int $id)
	{
		return $this->updateStatus($id, FormStatus::Approved);
	}

	public function rejected(int $id)
	{
		return $this->updateStatus($id, FormStatus::Rejected);
	}

	public function pending(int $id)
	{
		return $this->updateStatus($id, FormStatus::Pending);
	}

	public function issued(int $id)
	{
		return $this->updateStatus($id, FormStatus::Issued);
	}

    // public function approved(int $id)
	// {
	// 	$form = Form::findOrFail($id);
	// 	return $form->approved();
	// }

	// public function rejected(int $id)
	// {
	// 	$form = Form::findOrFail($id);
	// 	return $form->rejected();
	// }

	// public function void(int $id)
	// {
	// 	$form = Form::findOrFail($id);
	// 	return $form->void();
	// }

	// public function issued(int $id)
	// {
	// 	$form = Form::findOrFail($id);
	// 	return $form->issued();
	// }
}