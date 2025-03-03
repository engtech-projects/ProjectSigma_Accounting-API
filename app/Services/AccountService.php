<?php

namespace App\Services;

use App\Enums\AccountVatType;
use App\Http\Resources\AccountCollection;
use App\Models\Account;

class AccountService
{
    public static function getPaginated(array $validatedData)
    {
        $queryAccountRequest = Account::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            $query->where('account_name', 'like', '%' . $validatedData['key'] . '%');
        })
            ->paginate(config('services.pagination.limit'));

        return (AccountCollection::collection($queryAccountRequest))->response()->getData(true);
    }

    public static function getVatAccount()
    {
        $accountVat = Account::where('account_name', AccountVatType::ACCOUNT_INPUT_VAT->value)->first();

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
