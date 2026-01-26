<?php

namespace App\Http\Resources\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatementOfCashFlowReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Beginning Cash Balance" => [],
            "Operating Activities" => [
                "Cash Receipts From:" => [
                    "Project Collection" => [],
                    "Advance Payment" => [],
                    "Progress Billing" => [],
                    "Retention" => [],
                ],
                "Cash Paid For:" => [
                    "Supplies" => [],
                    "Equipment Rental" => [],
                    "Communication" => [],
                    "Subcontractors" => [],
                    "Taxes, Licenses and Registrations" => [],
                    "Testing of Materials" => [],
                    "Other Operating Activities" => [],
                ],
                "Net Cash Flow Provided by Operating Activities" => [],
            ],
            "Investing Activities" => [
                "Cash Receipts From:" => [
                    "Redemption of Time Deposits" => [],
                    "Interest Revenues" => [],
                ],
                "Cash Paid For:" => [
                    "Investment to Treasury Bills" => [],
                    "Purchase of Property, Plant and Equipment" => [],
                ],
                "Net Cash Flow Provided by Investing Activities" => [],
            ],
            "Financing Activities" => [
                "Cash Receipts From:" => [
                    "Releases of Bank Loans" => [],
                ],
                "Cash Paid For:" => [
                    "Repayments of Bank Loan Payable" => [],
                    "Repayments of Finance Lease Payable" => [],
                    "Interest Expense" => [],
                ],
                "Net Cash Flow Provided by Financing Activities" => [],
            ],
            "Addition" => [
                "Total Increase in Cash" => [],
            ],
            "Ending Cash Balance" => [],
        ];
    }
}
