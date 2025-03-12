<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;

class ReportSalesCategory implements FromCollection
{
    private $reports;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Sale::all();
    }

    public function __construct(array $sales)
    {
        $this->reports = $sales;
    }

    public function view(): View
    {
        return view('generate.excel.report-sales', $this->reports);
    }
}
