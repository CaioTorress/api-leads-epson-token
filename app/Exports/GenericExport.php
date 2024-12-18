<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Collection;
class GenericExport implements FromCollection, WithHeadings
{

    public $query;

    public $column_names = [];

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return 
            is_a($this->query, Collection::class)
            ? $this->query
            : $this->query->get();
    }

    public function headings(): array
    {
        return $this->column_names;
    }
}
