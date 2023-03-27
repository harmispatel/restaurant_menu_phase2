<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\Category;
use App\Models\Items;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    // Get all Categories
    public function index()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $data['categories'] = Category::where('shop_id',$shop_id)->orderBy('order_key')->get();
        return view('client.categories.categories',$data);
    }



    // Function for Store New Category
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
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

        $category_name_key = $lang_code."_name";
        $category_description_key = $lang_code."_description";

        $name = $request->name;
        $description = $request->description;
        $published = isset($request->published) ? $request->published : 0;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $max_category_order_key = Category::max('order_key');
        $category_order = (isset($max_category_order_key) && !empty($max_category_order_key)) ? ($max_category_order_key + 1) : 1;

       try
       {
            $category = new Category();
            $category->name = $name;
            $category->description = $description;

            $category->$category_name_key = $name;
            $category->$category_description_key = $description;

            $category->published = $published;
            $category->shop_id = $shop_id;
            $category->order_key = $category_order;

            // Insert Category Image if is Exists
            if(isset($request->og_image) && !empty($request->og_image) && $request->hasFile('image'))
            {
                $og_image = $request->og_image;
                $image_arr = explode(";base64,", $og_image);
                $image_base64 = base64_decode($image_arr[1]);

                $imgname = "category_".time().".". $request->file('image')->getClientOriginalExtension();
                $img_path = public_path('client_uploads/shops/'.$shop_slug.'/categories/'.$imgname);
                file_put_contents($img_path,$image_base64);
                // $request->file('image')->move(public_path('client_uploads/shops/'.$shop_slug.'/categories'), $imgname);
                $category->image = $imgname;
            }

            $category->save();

            return response()->json([
                'success' => 1,
                'message' => "Category has been Inserted SuccessFully....",
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



    // Function for Delete Category
    public function destroy(Request $request)
    {
        try
        {
            $id = $request->id;
            $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

            // Category Items Count
            $items_count = Items::where('category_id',$id)->count();

            if($items_count > 0)
            {
                return response()->json([
                    'success' => 0,
                    'message' => "You cannot delete a category as long as there are any items in the category!",
                ]);
            }
            else
            {
                $category = Category::where('id',$id)->first();
                $category_image = isset($category->image) ? $category->image : '';

                // Delete Category Image
                if(!empty($category->image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category_image))
                {
                    unlink('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category_image);
                }

                // Delete Category
                Category::where('id',$id)->delete();

                return response()->json([
                    'success' => 1,
                    'message' => "Category has been Deleted SuccessFully....",
                ]);
            }

        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }



    // Function for Edit Category
    public function edit(Request $request)
    {
        $category_id = $request->id;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        try
        {
            // Category Details
            $category = Category::where('id',$category_id)->first();
            $default_image = asset('public/client_images/not-found/no_image_1.jpg');
            $category_image = (isset($category['image']) && !empty($category['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category['image']) : '';
            $category_status = (isset($category['published']) && $category['published'] == 1) ? 'checked' : '';
            $delete_cat_image_url = route('categories.delete.image',$category_id);

            // Get Language Settings
            $language_settings = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

            // Primary Language Details
            $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
            $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
            $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';

            // Primary Language Category Details
            $primary_cat_name = isset($category[$primary_lang_code."_name"]) ? $category[$primary_lang_code."_name"] : '';
            $primary_cat_desc = isset($category[$primary_lang_code."_description"]) ? $category[$primary_lang_code."_description"] : '';
            $primary_input_lang_code = "'$primary_lang_code'";
            $primary_form_name = "'$primary_lang_code"."_category_form'";

            // Additional Languages
            $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();

            if(count($additional_languages) > 0)
            {
                $html = '';

                // Dynamic Lang Navbar
                $html .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
                    // For Primary Language
                    $html .= '<li class="nav-item" role="presentation">';
                        $html .= '<button title="'.$primary_lang_name.'" class="nav-link active" id="'.$primary_lang_code.'-tab" onclick="setTextEditor('.$primary_input_lang_code.')" data-bs-toggle="tab" data-bs-target="#'.$primary_lang_code.'" type="button" role="tab" aria-controls="'.$primary_lang_code.'" aria-selected="true">'.strtoupper($primary_lang_code).'</button>';
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
                            $html .= '<button title="'.$add_lang_name.'" class="nav-link" id="'.$add_lang_code.'-tab" data-bs-toggle="tab" onclick="setTextEditor('.$add_input_lang_code.')" data-bs-target="#'.$add_lang_code.'" type="button" role="tab" aria-controls="'.$add_lang_code.'" aria-selected="true">'.strtoupper($add_lang_code).'</button>';
                        $html .= '</li>';
                    }
                $html .= '</ul>';


                // Navbar Div
                $html .= '<div class="tab-content" id="myTabContent">';
                    // For Primary Language
                    $html .= '<div class="tab-pane fade show active mt-3" id="'.$primary_lang_code.'" role="tabpanel" aria-labelledby="'.$primary_lang_code.'-tab">';
                        $html .= '<form id="'.$primary_lang_code.'_category_form" enctype="multipart/form-data">';
                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="category_id" id="category_id" value="'.$category['id'].'">';
                            $html .= '<div class="row">';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label" for="category_name">'.__('Name').'</label>';
                                    $html .= '<input type="text" name="category_name" id="category_name" class="form-control" value="'.$primary_cat_name.'">';
                                $html .= '</div>';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label" for="category_description">'.__('Desription').'</label>';
                                    $html .= '<textarea name="category_description" id="category_description_'.$primary_lang_code.'" class="form-control category_description" rows="3">'.$primary_cat_desc.'</textarea>';
                                $html .= '</div>';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label">'.__('Image').'</label>';
                                    $html .= '<input type="file" name="category_image" id="'.$primary_lang_code.'_category_image" class="form-control category_image" onchange="imageCropper('.$primary_input_lang_code.',this)" style="display:none">';
                                    $html .= '<input type="hidden" name="og_image" id="og_image" class="og_image">';

                                    if(!empty($category_image))
                                    {
                                        $html .= '<div class="row mt-2" id="edit-img">';
                                            $html .= '<div class="col-md-3">';
                                                $html .= '<div class="mt-3 position-relative" id="categoryImage">';
                                                    $html .= '<label style="cursor:pointer" for="'.$primary_lang_code.'_category_image"><img src="'.$category_image.'" class="w-100"></label>';
                                                    $html .= '<a href="'.$delete_cat_image_url.'" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                        $html .= '</div>';

                                        $html .= '<div class="row mt-2" id="rep-image" style="display:none;">';
                                            $html .= '<div class="col-md-3" id="img-label">';
                                                $html .= '<label for="'.$primary_lang_code.'_category_image" style="cursor: pointer">';
                                                    $html .= '<img src="" class="w-100" id="crp-img-prw">';
                                                $html .= '</label>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                    }
                                    else
                                    {
                                        $html .= '<div class="row mt-3" id="categoryImage">';
                                            $html .= '<div class="col-md-3" id="img-label">';
                                                $html .= '<label style="cursor:pointer;" for="'.$primary_lang_code.'_category_image"><img src="'.$default_image.'" class="w-100" id="crp-img-prw"></label>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                    }
                                    $html .= '</br><code>Upload Image in (200*200) Dimensions</code>';
                                $html .= '</div>';

                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<div class="row">';
                                        $html .= '<div class="col-md-8 img-crop-sec mb-2" style="display: none">';
                                            $html .= '<img src="" alt="" id="resize-image" class="w-100 resize-image">';
                                            $html .= '<div class="mt-3">';
                                                $html .= '<a class="btn btn-sm btn-success" onclick="saveCropper('.$primary_form_name.')">Save</a>';
                                                $html .= '<a class="btn btn-sm btn-danger mx-2" onclick="resetCropper()">Reset</a>';
                                                $html .= '<a class="btn btn-sm btn-secondary" onclick="cancelCropper('.$primary_form_name.')">Cancel</a>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-4 img-crop-sec" style="display: none;">';
                                            $html .= '<div class="preview" style="width: 200px; height:200px; overflow: hidden;margin: 0 auto;"></div>';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';

                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<label class="form-label me-3" for="published">'.__('Published').'</label>';
                                    $html .= '<label class="switch">';
                                        $html .= '<input type="checkbox" id="published" name="published" value="1" '.$category_status.'>';
                                        $html .= '<span class="slider round">';
                                            $html .= '<i class="fa-solid fa-circle-check check_icon"></i>';
                                            $html .= '<i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>';
                                        $html .= '</span>';
                                    $html .= '</label>';
                                $html .= '</div>';
                                $html .= '<div class="form-group mb-3">';
                                    $html .= '<a class="btn btn btn-success" onclick="updateCategory('.$primary_input_lang_code.')">'.__('Update').'</a>';
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
                        $add_form_name = "'$add_lang_code"."_category_form'";

                        // Additional Language Category Details
                        $add_cat_name = isset($category[$add_lang_code."_name"]) ? $category[$add_lang_code."_name"] : '';
                        $add_cat_desc = isset($category[$add_lang_code."_description"]) ? $category[$add_lang_code."_description"] : '';

                        $html .= '<div class="tab-pane fade mt-3" id="'.$add_lang_code.'" role="tabpanel" aria-labelledby="'.$add_lang_code.'-tab">';
                            $html .= '<form id="'.$add_lang_code.'_category_form" enctype="multipart/form-data">';
                                $html .= csrf_field();
                                $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$add_lang_code.'">';
                                $html .= '<input type="hidden" name="category_id" id="category_id" value="'.$category['id'].'">';
                                $html .= '<div class="row">';
                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<label class="form-label" for="category_name">'.__('Name').'</label>';
                                        $html .= '<input type="text" name="category_name" id="category_name" class="form-control" value="'.$add_cat_name.'">';
                                    $html .= '</div>';
                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<label class="form-label" for="category_description">'.__('Desription').'</label>';
                                        $html .= '<textarea name="category_description" id="category_description_'.$add_lang_code.'" class="form-control category_description" rows="3">'.$add_cat_desc.'</textarea>';
                                    $html .= '</div>';
                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<label class="form-label">'.__('Image').'</label>';
                                        $html .= '<input style="display:none;" type="file" name="category_image" id="'.$add_lang_code.'_category_image" class="form-control category_image" onchange="imageCropper('.$add_input_lang_code.',this)">';
                                        $html .= '<input type="hidden" name="og_image" id="og_image" class="og_image">';

                                        if(!empty($category_image))
                                        {
                                            $html .= '<div class="row mt-2" id="edit-img">';
                                                $html .= '<div class="col-md-3">';
                                                    $html .= '<div class="mt-3 position-relative" id="categoryImage">';
                                                        $html .= '<label for="'.$add_lang_code.'_category_image" style="cursor:pointer"><img src="'.$category_image.'" class="w-100"></label>';
                                                        $html .= '<a href="'.$delete_cat_image_url.'" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>';
                                                    $html .= '</div>';
                                                $html .= '</div>';
                                            $html .= '</div>';

                                            $html .= '<div class="row mt-2" id="rep-image" style="display:none;">';
                                                $html .= '<div class="col-md-3" id="img-label">';
                                                    $html .= '<label for="'.$add_lang_code.'_category_image" style="cursor: pointer">';
                                                        $html .= '<img src="" class="w-100" id="crp-img-prw">';
                                                    $html .= '</label>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                        }
                                        else
                                        {
                                            $html .= '<div class="row mt-3" id="categoryImage">';
                                                $html .= '<div class="col-md-3" id="img-label">';
                                                    $html .= '<label style="cursor:pointer;" for="'.$add_lang_code.'_category_image"><img src="'.$default_image.'" class="w-100" id="crp-img-prw"></label>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                        }
                                        $html .= '</br><code>Upload Image in (200*200) Dimensions</code>';
                                    $html .= '</div>';

                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<div class="row">';
                                            $html .= '<div class="col-md-8 img-crop-sec mb-2" style="display: none">';
                                                $html .= '<img src="" alt="" id="resize-image" class="w-100 resize-image">';
                                                $html .= '<div class="mt-3">';
                                                    $html .= '<a class="btn btn-sm btn-success" onclick="saveCropper('.$add_form_name.')">Save</a>';
                                                    $html .= '<a class="btn btn-sm btn-danger mx-2" onclick="resetCropper()">Reset</a>';
                                                    $html .= '<a class="btn btn-sm btn-secondary" onclick="cancelCropper('.$add_form_name.')">Cancel</a>';
                                                $html .= '</div>';
                                            $html .= '</div>';
                                            $html .= '<div class="col-md-4 img-crop-sec" style="display: none;">';
                                                $html .= '<div class="preview" style="width: 200px; height:200px; overflow: hidden;margin: 0 auto;"></div>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                    $html .= '</div>';

                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<label class="form-label me-3" for="published">'.__('Published').'</label>';
                                        $html .= '<label class="switch">';
                                            $html .= '<input type="checkbox" id="published" name="published" value="1" '.$category_status.'>';
                                            $html .= '<span class="slider round">';
                                                $html .= '<i class="fa-solid fa-circle-check check_icon"></i>';
                                                $html .= '<i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>';
                                            $html .= '</span>';
                                        $html .= '</label>';
                                    $html .= '</div>';
                                    $html .= '<div class="form-group mb-3">';
                                        $html .= '<a class="btn btn btn-success" onclick="updateCategory('.$add_input_lang_code.')">'.__('Update').'</a>';
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

                $html .= '<form id="'.$primary_lang_code.'_category_form" enctype="multipart/form-data">';
                    $html .= csrf_field();
                    $html .= '<input type="hidden" name="lang_code" id="lang_code" value="'.$primary_lang_code.'">';
                    $html .= '<input type="hidden" name="category_id" id="category_id" value="'.$category['id'].'">';
                    $html .= '<div class="row">';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label class="form-label" for="category_name">Name</label>';
                            $html .= '<input type="text" name="category_name" id="category_name" class="form-control" value="'.$primary_cat_name.'">';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label class="form-label" for="category_description">Desription</label>';
                            $html .= '<textarea name="category_description" id="category_description_'.$primary_lang_code.'" class="form-control category_description" rows="3">'.$primary_cat_desc.'</textarea>';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label class="form-label">Image</label>';
                            $html .= '<input style="display:none;" type="file" name="category_image" id="'.$primary_lang_code.'_category_image" class="form-control category_image" onchange="imageCropper('.$primary_input_lang_code.',this)">';
                            $html .= '<input type="hidden" name="og_image" id="og_image" class="og_image">';

                            if(!empty($category_image))
                            {
                                $html .= '<div class="row mt-2" id="edit-img">';
                                    $html .= '<div class="col-md-3">';
                                        $html .= '<div class="mt-3 position-relative" id="categoryImage">';
                                            $html .= '<label for="'.$primary_lang_code.'_category_image" style="cursor:pointer"><img src="'.$category_image.'" class="w-100"></label>';
                                            $html .= '<a href="'.$delete_cat_image_url.'" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';

                                $html .= '<div class="row mt-2" id="rep-image" style="display:none;">';
                                    $html .= '<div class="col-md-3" id="img-label">';
                                        $html .= '<label for="'.$primary_lang_code.'_category_image" style="cursor: pointer">';
                                            $html .= '<img src="" class="w-100" id="crp-img-prw">';
                                        $html .= '</label>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            }
                            else
                            {
                                $html .= '<div class="row mt-3" id="categoryImage">';
                                    $html .= '<div class="col-md-3" id="img-label">';
                                        $html .= '<label style="cursor:pointer;" for="'.$primary_lang_code.'_category_image"><img src="'.$default_image.'" class="w-100" id="crp-img-prw"></label>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            }
                            $html .= '</br><code>Upload Image in (200*200) Dimensions</code>';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<div class="row">';
                                $html .= '<div class="col-md-8 img-crop-sec mb-2" style="display: none">';
                                    $html .= '<img src="" alt="" id="resize-image" class="w-100 resize-image">';
                                    $html .= '<div class="mt-3">';
                                        $html .= '<a class="btn btn-sm btn-success" onclick="saveCropper('.$primary_form_name.')">Save</a>';
                                        $html .= '<a class="btn btn-sm btn-danger mx-2" onclick="resetCropper()">Reset</a>';
                                        $html .= '<a class="btn btn-sm btn-secondary" onclick="cancelCropper('.$primary_form_name.')">Cancel</a>';
                                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-4 img-crop-sec" style="display: none;">';
                                    $html .= '<div class="preview" style="width: 200px; height:200px; overflow: hidden;margin: 0 auto;"></div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label class="form-label me-3" for="published">Published</label>';
                            $html .= '<label class="switch">';
                                $html .= '<input type="checkbox" id="published" name="published" value="1" '.$category_status.'>';
                                $html .= '<span class="slider round">';
                                    $html .= '<i class="fa-solid fa-circle-check check_icon"></i>';
                                    $html .= '<i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>';
                                $html .= '</span>';
                            $html .= '</label>';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<a class="btn btn btn-success" onclick="updateCategory('.$primary_input_lang_code.')">Update</a>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</form>';

            }

            return response()->json([
                'success' => 1,
                'message' => "Category Details has been Retrived Successfully..",
                'data'=> $html,
                'primary_code' => $primary_lang_code,
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



    // Function for Update Category
    public function update(Request $request)
    {
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $category_id = $request->category_id;
        $lang_code = $request->lang_code;
        $category_name = $request->category_name;
        $category_description = $request->category_description;
        $published = isset($request->published) ? $request->published : 0;

        $name_key = $lang_code."_name";
        $description_key = $lang_code."_description";

        $request->validate([
            'category_name'   => 'required',
        ]);

        try
        {
            $category = Category::find($category_id);

            if($category)
            {
                $category->name = $category_name;
                $category->description = $category_description;
                $category->$name_key = $category_name;
                $category->$description_key = $category_description;
                $category->published = $published;

                // Insert Category Image if is Exists
                if(isset($request->og_image) && !empty($request->og_image) && $request->hasFile('category_image'))
                {
                    $og_image = $request->og_image;
                    $image_arr = explode(";base64,", $og_image);
                    $image_base64 = base64_decode($image_arr[1]);

                    // Delete old Image
                    $category_image = isset($category->image) ? $category->image : '';
                    if(!empty($category_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category_image))
                    {
                        unlink('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category_image);
                    }

                    // Insert new Image
                    $imgname = "category_".time().".". $request->file('category_image')->getClientOriginalExtension();
                    $img_path = public_path('client_uploads/shops/'.$shop_slug.'/categories/'.$imgname);
                    file_put_contents($img_path,$image_base64);
                    $category->image = $imgname;
                }

                $category->update();
            }

            return response()->json([
                'success' => 1,
                'message' => "Category has been Updated SuccessFully....",
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


    // Function for Change Category Status
    public function status(Request $request)
    {
        try
        {
            $id = $request->id;
            $published = $request->status;

            $category = Category::find($id);
            $category->published = $published;
            $category->update();

            return response()->json([
                'success' => 1,
                'message' => "Category Status has been Changed Successfully..",
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



    // Function for Filtered Categories
    public function searchCategories(Request $request)
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
        $keyword = $request->keywords;

        if(session()->has('lang_code'))
        {
            $curr_lang_code = session()->get('lang_code');
        }
        else
        {
            $curr_lang_code = 'en';
        }

        try
        {
            $name_key = $curr_lang_code."_name";
            $categories = Category::where($name_key,'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->get();
            $html = '';

            if(count($categories) > 0)
            {
                foreach($categories as $category)
                {
                    $newStatus = ($category->published == 1) ? 0 : 1;
                    $checked = ($category->published == 1) ? 'checked' : '';

                    if(!empty($category->image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image))
                    {
                        $image = asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image);
                    }
                    else
                    {
                        $image = asset('public/client_images/not-found/no_image_1.jpg');
                    }

                    $html .= '<div class="col-md-3">';
                        $html .= '<div class="item_box">';
                            $html .= '<div class="item_img">';
                                $html .= '<a><img src="'.$image.'" class="w-100"></a>';
                                $html .= '<div class="edit_item_bt">';
                                    $html .= '<button type="button" class="btn edit_item">'.__('ADD OR EDIT ITEMS').'</button>';
                                    $html .= '<button class="btn edit_category" onclick="editCategory('.$category->id.')">'.__('EDIT CATEGORY').'</button>';
                                $html .= '</div>';
                                $html .= '<a class="delet_bt" onclick="deleteCategory('.$category->id.')" style="cursor: pointer;"><i class="fa-solid fa-trash"></i></a>';
                                $html .= '<a class="cat_edit_bt" onclick="editCategory('.$category->id.')"><i class="fa-solid fa-edit"></i></a>';

                                $item_redirect_path = route('items',$category->id);

                                $html .= '<a class="item_add_bt" href="'.$item_redirect_path.'"><i class="fa-solid fa-add"></i></a>';
                            $html .= '</div>';
                            $html .= '<div class="item_info">';
                                $html .= '<div class="item_name">';
                                    $html .= '<h3>'.$category->en_name.'</h3>';
                                    $html .= '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" role="switch" id="status" onclick="changeStatus('.$category->id.','.$newStatus.')" value="1" '.$checked.'></div>';
                                $html .= '</div>';
                                $html .= '<h2>'.__('Product Category').'</h2>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';


                }
            }

            $html .= '<div class="col-md-3">';
                $html .= '<div class="item_box">';
                    $html .= '<div class="item_img add_category">';
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#addCategoryModal" class="add_category_bt" id="NewCategoryBtn"><i class="fa-solid fa-plus"></i></a>';
                    $html .= '</div>';
                    $html .= '<div class="item_info text-center"><h2>'.__('Product Category').'</h2></div>';
                $html .= '</div>';
            $html .= '</div>';

            return response()->json([
                'success' => 1,
                'message' => "Categories has been retrived Successfully...",
                'data'    => $html,
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



    // Function Delete Category Image
    public function deleteCategoryImage($id)
    {
        $category = Category::find($id);
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        if($category)
        {
            $cat_image = isset($category['image']) ? $category['image'] : '';

            if(!empty($cat_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$cat_image))
            {
                unlink('public/client_uploads/shops/'.$shop_slug.'/categories/'.$cat_image);
            }

            $category->image = "";
            $category->update();
        }

        return redirect()->route('categories')->with('success',"Category Image has been Removed SuccessFully...");

    }



    // Function for Sorting Category.
    public function sorting(Request $request)
    {
        $sort_array = $request->sortArr;

        foreach ($sort_array as $key => $value)
        {
    		$key = $key+1;
    		Category::where('id',$value)->update(['order_key'=>$key]);
    	}

        return response()->json([
            'success' => 1,
            'message' => "Category has been Sorted SuccessFully....",
        ]);

    }
}
