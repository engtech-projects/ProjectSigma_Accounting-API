<?php

namespace App\Services;

use App\Enums\AccountVatType;
use App\Models\Account;

class AccountService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Account::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (isset($filters['account_type_id'])) {
            $query->where('account_type_id', $filters['account_type_id']);
        }

        return $query->paginate(config('services.pagination.limit'));
    }
    public static function getVatAccount()
    {
        $accountVat = Account::where('account_name', AccountVatType::ACCOUNT_INPUT_VAT ->value)->first();
        return [
            'id' => $accountVat->id,
            'information' => $accountVat->information,
        ];
    }
    public static function getWithHoldingTaxAccount($id)
    {
        $accountVat = Account::find($id)->first();
        return [
            'id' => $accountVat->id,
            'information' => $accountVat,
        ];
    }
}
