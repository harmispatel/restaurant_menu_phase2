<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\CategoryProductTags;
use App\Models\Languages;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagsController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $data['tags'] = Tags::where('shop_id',$shop_id)->get();
        return view('client.tags.tags',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|unique:tags,name',
        ]);

        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        // Language Settings
        $language_settings = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

        // Language Details
        $language_detail = Languages::where('id',$primary_lang_id)->first();
        $lang_code = isset($language_detail->code) ? $language_detail->code : '';

        $tag_name_key = $lang_code."_name";

        $max_tag_order_key = Tags::max('order');
        $tag_order = (isset($max_tag_order_key) && !empty($max_tag_order_key)) ? ($max_tag_order_key + 1) : 1;

        // try
        // {
            $tag = new Tags();
            $tag->name = $request->tag_name;
            $tag->shop_id = $shop_id;
            $tag->order = $tag_order;
            $tag->$tag_name_key = $request->tag_name;
            $tag->save();

            return response()->json([
                'success' => 1,
                'message' => "Tag has been Inserted SuccessFully...",
            ]);
        // }
        // catch (\Throwable $th)
        // {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => "Internal Server Error!",
        //     ]);
        // }

    }

    // Sorting Tags.
    public function sorting(Request $request)
    {
        $sort_array = $request->sortArr;

        foreach ($sort_array as $key => $value)
        {
    		$key = $key+1;
    		Tags::where('id',$value)->update(['order'=>$key]);
    	}

        return response()->json([
            'success' => 1,
            'message' => "Tags has been Sorted SuccessFully....",
        ]);

    }

    // Show the form for editing the specified resource.
    public function edit(Request $request)
    {
        try
        {
            $id = $request->id;
            $tag = Tags::where('id',$id)->first();

            return response()->json([
                'success' => 1,
                'message' => "Tag Details has been Retrived Successfully..",
                'tag'=> $tag,
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }


    // Function for edit Tag Language Wise
    public function editTag(Request $request)
    {
        $tag_id = $request->id;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            // Tag Details
            $tag = Tags::where('id',$tag_id)->first();

            // Get Language Settings
            $language_settings = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

            // Primary Language Details
            $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
            $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
            $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';

            // Primary Language Tag Details
            $primary_tag_name = isset($tag[$primary_lang_code."_name"]) ? $tag[$primary_lang_code."_name"] : '';
            $primary_input_lang_code = "'$primary_lang_code'";

            // Additional Languages
            $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();

            if(count($additional_languages) > 0)
            {
                $html = '';

                // Dynamic Lang Navbar
                $html .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
                    // For Primary Language
                    $html .= '<li class="nav-item" role="presentation">';
                        $html .= '<button title="'.$primary_lang_name.'" class="nav-link active" id="'.$primary_lang_code.'-tab" data-bs-toggle="tab" data-bs-target="#'.$primary_lang_code.'" type="button" role="tab" aria-controls="'.$primary_lang_code.'" aria-selected="true">'.strtoupper($primary_lang_code).'</button>';
                    $html .= '</li>';

                    // For Additional Language
                    foreach($additional_languages as $value)
                    {
                        // Additional Language Details
                        $add_lang_detail = Languages::where('id',$value->language_id)->first();
                        $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                        $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';
                        $add_input_lang_code = "'$add_lang_code'";

                        $html .= '<li class="nav-item" role="presentation">';
                            $html .= '<button title="'.$add_lang_name.'" class="nav-link" id="'.$add_lang_code.'-tab" data-bs-toggle="tab" data-bs-target="#'.$add_lang_code.'" type="button" role="tab" aria-controls="'.$add_lang_code.'" aria-selected="true">'.strtoupper($add_lang_code).'</button>';
                        $html .= '</li>';
                    }
                $html .= '</ul>';

                // Navbar Div
                $html .= '<div class="tab-content" id="myTabContent">';
                    // For Primary Language
                    $html .= '<div class="tab-pane fade show active mt-3" id="'.$primary_lang_code.'" role="tabpanel" aria-labelledby="'.$primary_lang_code.'-tab">';
                        $html .= '<form id="'.$primary_lang_code.'_tag_form" enctype="multipart/form-data">';
                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="tag_id" id="tag_id" value="'.$tag['id'].'">';
                            $html .= '<div class="row">';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label" for="tag_name">Name</label>';
                                    $html .= '<input type="text" name="tag_name" id="tag_name" class="form-control" value="'.$primary_tag_name.'">';
                                $html .= '</div>';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<a class="btn btn btn-success" onclick="updateTag('.$primary_input_lang_code.')">Update</a>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</form>';
                    $html .= '</div>';

                    // For Additional Language
                    foreach($additional_languages as $value)
                    {
                        // Additional Language Details
                        $add_lang_detail = Languages::where('id',$value->language_id)->first();
                        $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                        $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';
                        $add_input_lang_code = "'$add_lang_code'";

                        // Additional Language Tag Details
                        $add_tag_name = isset($tag[$add_lang_code."_name"]) ? $tag[$add_lang_code."_name"] : '';

                        $html .= '<div class="tab-pane fade mt-3" id="'.$add_lang_code.'" role="tabpanel" aria-labelledby="'.$add_lang_code.'-tab">';
                            $html .= '<form id="'.$add_lang_code.'_tag_form" enctype="multipart/form-data">';
                                $html .= csrf_field();
                                $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$add_lang_code.'">';
                                $html .= '<input type="hidden" name="tag_id" id="tag_id" value="'.$tag['id'].'">';
                                $html .= '<div class="row">';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label" for="tag_name">Name</label>';
                                    $html .= '<input type="text" name="tag_name" id="tag_name" class="form-control" value="'.$add_tag_name.'">';
                                $html .= '</div>';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<a class="btn btn btn-success" onclick="updateTag('.$add_input_lang_code.')">Update</a>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</form>';
                        $html .= '</div>';
                    }

                $html .= '</div>';

            }
            else
            {
                $html = '';

                $html .= '<form id="'.$primary_lang_code.'_tag_form" enctype="multipart/form-data">';
                    $html .= csrf_field();
                    $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$primary_lang_code.'">';
                    $html .= '<input type="hidden" name="tag_id" id="tag_id" value="'.$tag['id'].'">';
                    $html .= '<div class="row">';
                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label class="form-label" for="tag_name">Name</label>';
                            $html .= '<input type="text" name="tag_name" id="tag_name" class="form-control" value="'.$primary_tag_name.'">';
                        $html .= '</div>';
                        $html .= '<div class="form-group mb-3">';
                            $html .= '<a class="btn btn btn-success" onclick="updateTag('.$primary_input_lang_code.')">Update</a>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</form>';
            }

            return response()->json([
                'success' => 1,
                'message' => "Tag Details has been Retrived Successfully..",
                'data'=> $html,
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }



    // Function for edit Tag Language Wise
    public function updateTag(Request $request)
    {

        $request->validate([
            'tag_name' => 'required',
        ]);

        $tag_id = $request->tag_id;
        $lang_code = $request->lang_code;
        $tag_name = $request->tag_name;

        try
        {

            $name_key = $lang_code."_name";

            $tag = Tags::find($tag_id);

            if($tag)
            {
                // $tag->name = $tag_name;
                $tag->$name_key = $tag_name;
                $tag->update();
            }

            return response()->json([
                'success' => 1,
                'message' => "Tag has been Updated SuccessFully....",
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }



    // Update the specified resource in storage.
    public function update(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|unique:tags,name,'.$request->tag_id,
        ]);

        try
        {
            $tag = Tags::find($request->tag_id);
            $tag->name = strtolower($request->tag_name);
            $tag->update();

            return response()->json([
                'success' => 1,
                'message' => "Tag has been Updated SuccessFully....",
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }

    // Remove the specified resource from storage.
    public function destroy(Request $request)
    {
        try
        {
            $id = $request->id;

            // Delete Product Tags
            CategoryProductTags::where('tag_id',$id)->delete();

            // Delete Tag
            Tags::where('id',$id)->delete();

            return response()->json([
                'success' => 1,
                'message' => "Item has been Deleted SuccessFully....",
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }
}
