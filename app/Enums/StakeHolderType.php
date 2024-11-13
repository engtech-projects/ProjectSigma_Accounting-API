<?php

namespace App\Enums;

enum StakeHolderType: string
{
	case SUPPLIER = 'supplier';
	case EMPLOYEE = 'employee';
	case PROJECTS = 'projects';
	case DEPARTMENT = 'department';
    case CUSTOMER = 'customer';

	public static function getStakeHolders(): array
	{
		return array_column(self::cases(), 'value');
	}
}
