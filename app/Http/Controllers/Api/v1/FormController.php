<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;

class FormController extends Controller
{
    public function approved(int $id)
	{
		$form = Form::findOrFail($id);
		return $form->approved();
	}

	public function rejected(int $id)
	{
		$form = Form::findOrFail($id);
		return $form->rejected();
	}

	public function void(int $id)
	{
		$form = Form::findOrFail($id);
		return $form->void();
	}

	public function issued(int $id)
	{
		$form = Form::findOrFail($id);
		return $form->issued();
	}
}