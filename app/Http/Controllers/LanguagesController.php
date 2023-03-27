<?php

namespace App\Http\Controllers;

use App\Models\Languages;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function saveAjax(Request $request)
    {
        $lang_name = $request->name;
        $lang_code = $request->code;

        $language = new Languages();
        $language->name = $lang_name;
        $language->code = $lang_code;
        $language->status = 1;
        $language->save();

        return response()->json([
            'success' => 1,
        ]);

    }
}
