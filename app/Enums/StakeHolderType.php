<?php

namespace App\Enums;

enum StakeHolderType: string
{
	case Supplier = 'supplier';
	case Employee = 'employee';
	case Projects = 'projects';
}
