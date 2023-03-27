<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CategoriesandItemsExport implements WithMultipleSheets
{
    protected $data;
    protected $shop_id;

    public function __construct($data,$shop_id)
    {
        $this->data = $data;
        $this->shop_id = $shop_id;
    }

    public function sheets(): array
    {
        $all_data = $this->data;
        $sheets = [];

        foreach($all_data['categories'] as $category)
        {
            $sheets[] = new SingleExport($category,$all_data['languages']);
        }

        return $sheets;
    }

}
