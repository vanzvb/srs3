<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class TransmittalExport implements FromQuery, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;
    protected $hoaId;

    // Constructor to accept filter parameters
    public function __construct($startDate, $endDate, $hoaId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->hoaId = $hoaId;
    }

    /**
     * Return the query for the export with applied filters.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Removing specific column
        // $data = DB::table('v_srs3_transmittal_v1')
        //     ->select('SRS#', 'Account Name', 'Plate #', 'Vehicle Type', 'hoa', 'sellingPrice', 'created_at')  // Specify columns you want
        //     ->whereDate('created_at', '>=', $startDate)
        //     ->whereDate('created_at', '<=', $endDate)
        //     ->get();

        $query = DB::table('v_srs3_transmittal_v1'); // your view name

        // Apply filters to the query
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->hoaId) {
            $query->where('hoa', $this->hoaId);
        }

        $query->orderBy('created_at');
        // $query->orderBy('created_at', 'desc')->orderBy('account_id');

        return $query;
    }

    /**
     * Return the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'SRS#',
            'Account ID',
            'Account Name',
            'Plate #',
            'Vehicle Type',
            'HOA',
            'Selling Price',
            'Created At'
        ];
    }

    /**
     * Return the title for the export.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Transmittal Report';
    }
}
