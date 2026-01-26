<?php

namespace App\Enums\Reports;

enum IncomeStatement: string
{
    case REVENUE = 'revenue';
    case EXPENSE = 'expense';
    // REPORT INCLUDE
    case CONSTRUCTION_COST_DIRECT_OVERHEAD = 'construction_cost_direct_overhead';
    case CONSTRUCTION_COST_DEPRECIATION_AMORTIZATION = 'construction_cost_depreciation_amortization';
    case CONSTRUCTION_COST_OVERHEAD = 'construction_cost_overhead';
    case CONSTRUCTION_COST_MATERIALS = 'construction_cost_materials';
    case CONSTRUCTION_COST_EQUIPMENT_RENTAL = 'construction_cost_equipment_rental';
}
