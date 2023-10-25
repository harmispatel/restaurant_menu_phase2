<?php

namespace Database\Seeders;

use App\Models\CodePage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CodePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codepages = [
            [
                'key'   =>  0,
                'value' =>  437,
                'name'  =>  'PC437 | USA, Standard Europe',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  2,
                'value' =>  850,
                'name'  =>  'PC850 | Multilingual',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  3,
                'value' =>  860,
                'name'  =>  'PC860 | Portuguese',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  4,
                'value' =>  863,
                'name'  =>  'PC863 | Canadian-French',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  5,
                'value' =>  865,
                'name'  =>  'PC865 | Nordic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  11,
                'value' =>  851,
                'name'  =>  'PC851 | Greek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  12,
                'value' =>  853,
                'name'  =>  'PC853 | Turkish',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  13,
                'value' =>  857,
                'name'  =>  'PC857 | Turkish',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  14,
                'value' =>  737,
                'name'  =>  'PC737 | Greek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  15,
                'value' =>  28597,
                'name'  =>  'PC28597 | Greek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  17,
                'value' =>  866,
                'name'  =>  'PC866 | Cyrillic #2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  18,
                'value' =>  852,
                'name'  =>  'PC852 | Latin 2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  19,
                'value' =>  858,
                'name'  =>  'PC858 | Euro',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  32,
                'value' =>  720,
                'name'  =>  'PC720 | Arabic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  33,
                'value' =>  775,
                'name'  =>  'PC775 | Baltic Rim',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  34,
                'value' =>  855,
                'name'  =>  'PC855 | Cyrillic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  35,
                'value' =>  861,
                'name'  =>  'PC861 | Icelandic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  36,
                'value' =>  862,
                'name'  =>  'PC862 | Hebrew',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  37,
                'value' =>  864,
                'name'  =>  'PC864 | Arabic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  38,
                'value' =>  869,
                'name'  =>  'PC869 | Greek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  41,
                'value' =>  1098,
                'name'  =>  'PC1098 | Farsi',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  42,
                'value' =>  1118,
                'name'  =>  'PC1118 | Lithuanian',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  43,
                'value' =>  1119,
                'name'  =>  'PC1119 | Lithuanian',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  44,
                'value' =>  1125,
                'name'  =>  'PC1125 | Ukrainian',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  45,
                'value' =>  1250,
                'name'  =>  'PC1250 | Latin 2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  46,
                'value' =>  1251,
                'name'  =>  'PC1251 | Cyrillic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  47,
                'value' =>  1253,
                'name'  =>  'PC1253 | Greek',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  48,
                'value' =>  1254,
                'name'  =>  'PC1254 | Turkish',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  49,
                'value' =>  1255,
                'name'  =>  'PC1255 | Hebrew',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  50,
                'value' =>  1256,
                'name'  =>  'PC1256 | Arabic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  51,
                'value' =>  1257,
                'name'  =>  'PC1257 | Baltic Rim',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  52,
                'value' =>  1258,
                'name'  =>  'PC1258 | Vietnamese',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'key'   =>  53,
                'value' =>  1048,
                'name'  =>  'PC1048 | Kazakhstan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        CodePage::insert($codepages);
    }
}
