<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FormServices;

class FormController extends Controller
{

	public function changeStatus(int $id, string $status)
	{
		return FormServices::changeStatus($id, $status);
	}

	public function approved(int $id)
	{
		return FormServices::approved($id);
	}

	public function rejected(int $id)
	{
		return FormServices::rejected($id);
	}

	public function pending(int $id)
	{
		return FormServices::pending($id);
	}

	public function issued(int $id)
	{
		return FormServices::issued($id);
	}
}
