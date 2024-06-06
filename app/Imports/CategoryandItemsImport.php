<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\CategoryProductTags;
use App\Models\ItemPrice;
use App\Models\Items;
use App\Models\Tags;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CategoryandItemsImport implements ToCollection
{

    protected $shop_id;

    public function __construct($shop_id)
    {
        $this->shop_id = $shop_id;
    }

    public function collection(Collection $rows)
    {
        if (count($rows) > 0) {
            $cat_setting_arr = isset($rows[1]) ? $rows[1]->toArray() : [];

            // Schedule_type & Array
            $schedule_type = 'time';
            $schedule_array = '{"sunday":{"name":"Sun","enabled":false,"dayInWeek":0,"timesSchedules":[{"startTime":"","endTime":""}]},"monday":{"name":"Mon","enabled":false,"dayInWeek":1,"timesSchedules":[{"startTime":"","endTime":""}]},"tuesday":{"name":"Tue","enabled":false,"dayInWeek":2,"timesSchedules":[{"startTime":"","endTime":""}]},"wednesday":{"name":"Wed","enabled":false,"dayInWeek":3,"timesSchedules":[{"startTime":"","endTime":""}]},"thursday":{"name":"Thu","enabled":false,"dayInWeek":4,"timesSchedules":[{"startTime":"","endTime":""}]},"friday":{"name":"Fri","enabled":false,"dayInWeek":5,"timesSchedules":[{"startTime":"","endTime":""}]},"saturday":{"name":"Sat","enabled":false,"dayInWeek":6,"timesSchedules":[{"startTime":"","endTime":""}]}}';

            // Category Type
            $category_type = (isset($cat_setting_arr[0])) ? $cat_setting_arr[0] : '';

            // Is Parent Category
            $is_parent_category = (isset($cat_setting_arr[1])) ? $cat_setting_arr[1] : 0;

            // Parent Category Name
            $parent_cat_name = (isset($cat_setting_arr[2])) ? $cat_setting_arr[2] : '';

            // Parent Cat Details
            $parent_cat_details = Category::where('en_name', $parent_cat_name)->where('shop_id', $this->shop_id)->first();
            $parent_cat_id = (isset($parent_cat_details['id'])) ? $parent_cat_details['id'] : '';

            // Link URL
            $link_url = (isset($cat_setting_arr[3])) ? $cat_setting_arr[3] : '';

            $lang_array = isset($rows[2]) ? $rows[2]->toArray() : [];
            $langs = (isset($lang_array) && count($lang_array) > 0) ? array_filter($lang_array) : [];

            if (count($langs) > 0) {
                try {
                    if ($category_type == 'product_category') {
                        // Import Products & Categorties
                        unset($rows[0], $rows[1], $rows[2], $rows[3], $rows[4]);

                        if (count($rows) > 0) {
                            $item_lang_arr = [];
                            foreach ($rows as $val1) {
                                if (isset($val1[1]) && !empty($val1[1]) && !in_array($val1[1], $item_lang_arr)) {
                                    $item_lang_arr[] = $val1[1];
                                }
                            }

                            $item_lang_arr = array_filter($item_lang_arr);

                            if (count($langs) > 0) {
                                $product_ids = [];
                                $categories_ids = [];
                                $tag_ids = [];
                                $price_ids_arr = [];

                                foreach ($langs as $key => $item_lang) {
                                    $item_lang = trim($item_lang);
                                    if ($key <= 0) {
                                        $def_key = 0;
                                        $def_tag_key = 0;
                                        $def_price_key = 0;
                                        foreach ($rows as $item) {
                                            $max_item_order_key = Items::where('shop_id', $this->shop_id)->max('order_key');
                                            $item_order = (isset($max_item_order_key) && !empty($max_item_order_key)) ? ($max_item_order_key + 1) : 1;

                                            $item_price_arr = [];

                                            if (trim($item[1]) == $item_lang) {
                                                $item_name_key = trim($item[1]) . "_name";
                                                $item_description_key = trim($item[1]) . "_description";
                                                $item_price_label_key = trim($item[1]) . "_label";

                                                $published = isset($item[29]) ? $item[29] : 1;
                                                $item_type = (isset($item[28]) && !empty($item[28])) ? $item[28] : 1;
                                                $tags = isset($item[26]) ? explode(',', $item[26]) : [];
                                                $item_image = isset($item[25]) ? $item[25] : '';

                                                if ($item_type == 1) {
                                                    $item_price_arr['price'] = [
                                                        (isset($item[6])) ? $item[6] : '',
                                                        (isset($item[8])) ? $item[8] : '',
                                                        (isset($item[10])) ? $item[10] : '',
                                                        (isset($item[12])) ? $item[12] : '',
                                                        (isset($item[14])) ? $item[14] : '',
                                                        (isset($item[16])) ? $item[16] : '',
                                                        (isset($item[18])) ? $item[18] : '',
                                                        (isset($item[20])) ? $item[20] : '',
                                                        (isset($item[22])) ? $item[22] : '',
                                                        (isset($item[24])) ? $item[24] : '',
                                                    ];

                                                    $item_price_arr['label'] = [
                                                        (isset($item[5])) ? $item[5] : '',
                                                        (isset($item[7])) ? $item[7] : '',
                                                        (isset($item[9])) ? $item[9] : '',
                                                        (isset($item[11])) ? $item[11] : '',
                                                        (isset($item[13])) ? $item[13] : '',
                                                        (isset($item[15])) ? $item[15] : '',
                                                        (isset($item[17])) ? $item[17] : '',
                                                        (isset($item[19])) ? $item[19] : '',
                                                        (isset($item[21])) ? $item[21] : '',
                                                        (isset($item[23])) ? $item[23] : '',
                                                    ];

                                                    $price_array['price'] = isset($item_price_arr['price']) ? array_filter($item_price_arr['price']) : [];
                                                    $price_array['label'] = isset($item_price_arr['label']) ? $item_price_arr['label'] : [];

                                                    if (count($price_array['price']) > 0) {
                                                        $price = $price_array;
                                                    } else {
                                                        $price = [];
                                                    }
                                                } else {
                                                    $price = [];
                                                }

                                                $categories_arr = (isset($item[2]) && !empty($item[2])) ? explode(',', $item[2]) : [];

                                                $cat_ids = [];
                                                if (count($categories_arr) > 0) {
                                                    foreach ($categories_arr as $category_val) {

                                                        // Import Category
                                                        $max_category_order_key = Category::where('shop_id', $this->shop_id)->max('order_key');
                                                        $category_order = (isset($max_category_order_key) && !empty($max_category_order_key)) ? ($max_category_order_key + 1) : 1;

                                                        $cat_name_key = trim($item[1]) . "_name";
                                                        $cat_exist = Category::where('shop_id', $this->shop_id)->where($cat_name_key, $category_val)->first();
                                                        $cat_exist_id = (isset($cat_exist->id)) ? $cat_exist->id : '';

                                                        if (!empty($cat_exist_id)) {
                                                            $category = Category::find($cat_exist_id);
                                                            $category->$cat_name_key = $category_val;
                                                            $category->update();
                                                            $cat_ids[] = $category->id;
                                                        } else {
                                                            $category = new Category();
                                                            $category->shop_id = $this->shop_id;
                                                            $category->$cat_name_key = $category_val;
                                                            $category->order_key = $category_order;
                                                            $category->category_type = $category_type;
                                                            $category->parent_category = $is_parent_category;
                                                            $category->schedule_type = $schedule_type;
                                                            $category->schedule_value = $schedule_array;

                                                            if (!empty($parent_cat_id) && $is_parent_category == 0) {
                                                                $category->parent_id = $parent_cat_id;
                                                            }

                                                            $category->published = 1;
                                                            $category->save();
                                                            $cat_ids[] = $category->id;
                                                        }
                                                    }
                                                    $categories_ids[$def_key] = $cat_ids;
                                                }

                                                $new_item = new Items();
                                                $new_item->shop_id = $this->shop_id;
                                                $new_item->order_key = $item_order;
                                                $new_item->type = $item_type;

                                                $new_item->name = $item[3];
                                                $new_item->$item_name_key = $item[3];
                                                $new_item->description = $item[4];
                                                $new_item->$item_description_key = $item[4];

                                                $new_item->published = $published;
                                                $new_item->image = $item_image;
                                                $new_item->save();

                                                $new_item->categories()->sync($cat_ids);

                                                // Insert Item Price
                                                if (count($price) > 0) {
                                                    $price_arr = $price['price'];
                                                    $label_arr = $price['label'];

                                                    if (count($price_arr) > 0) {
                                                        foreach ($price_arr as $key => $price_val) {
                                                            $label_val = isset($label_arr[$key]) ? $label_arr[$key] : '';
                                                            $new_price = new ItemPrice();
                                                            $new_price->item_id = $new_item->id;
                                                            $new_price->shop_id = $this->shop_id;
                                                            $new_price->price = $price_val;
                                                            $new_price->label = $label_val;
                                                            $new_price->$item_price_label_key = $label_val;
                                                            $new_price->save();

                                                            $price_ids_arr[$def_price_key] = $new_price->id;
                                                            $def_price_key++;
                                                        }
                                                    }
                                                }

                                                // Insert Tags & Update Tags
                                                if (count($tags) > 0) {
                                                    foreach ($tags as $key => $tag) {
                                                        $tag = trim($tag);
                                                        $tag_name_key = trim($item[1]) . "_name";
                                                        // $findTag = Tags::where('shop_id',$this->shop_id)->where('name',$tag)->where($tag_name_key,$tag)->first();
                                                        $findTag = Tags::where('shop_id', $this->shop_id)->where($tag_name_key, $tag)->first();
                                                        $tag_id = (isset($findTag->id) && !empty($findTag->id)) ? $findTag->id : '';

                                                        if (!empty($tag_id) || $tag_id != '') {
                                                            $edit_tag = Tags::find($tag_id);
                                                            $edit_tag->$item_name_key = $tag;
                                                            $edit_tag->update();

                                                            if ($edit_tag->id) {
                                                                if (count($cat_ids) > 0) {
                                                                    foreach ($cat_ids as $cat_id)
                                                                        $cat_pro_tag = new CategoryProductTags();
                                                                    $cat_pro_tag->tag_id = $edit_tag->id;
                                                                    $cat_pro_tag->category_id = $cat_id;
                                                                    $cat_pro_tag->item_id = $new_item->id;
                                                                    $cat_pro_tag->save();
                                                                }
                                                            }
                                                        } else {
                                                            $tag_max_order = Tags::where('shop_id', $this->shop_id)->max('order');
                                                            $tag_order = (isset($tag_max_order) && !empty($tag_max_order)) ? ($tag_max_order + 1) : 1;

                                                            $new_tag = new Tags();
                                                            $new_tag->shop_id = $this->shop_id;
                                                            $new_tag->name = $tag;
                                                            $new_tag->$item_name_key = $tag;
                                                            $new_tag->order = $tag_order;
                                                            $new_tag->save();

                                                            $tag_ids[$def_tag_key] = $new_tag->id;

                                                            if ($new_tag->id) {
                                                                if (count($cat_ids) > 0) {
                                                                    foreach ($cat_ids as $cat_id) {
                                                                        $cat_pro_tag = new CategoryProductTags();
                                                                        $cat_pro_tag->tag_id = $new_tag->id;
                                                                        $cat_pro_tag->category_id = $cat_id;
                                                                        $cat_pro_tag->item_id = $new_item->id;
                                                                        $cat_pro_tag->save();
                                                                    }
                                                                }
                                                            }
                                                            $def_tag_key++;
                                                        }
                                                    }
                                                }

                                                $product_ids[$def_key] = $new_item->id;
                                                $def_key++;
                                            }
                                        }
                                    } else {
                                        $def_key = 0;
                                        $def_tag_key = 0;
                                        $def_price_key = 0;

                                        foreach ($rows as $item) {
                                            if (trim($item[1]) == $item_lang) {
                                                $categories = (isset($categories_ids[$def_key])) ? $categories_ids[$def_key] : [];
                                                $edit_categories = (isset($item[2]) && !empty($item[2])) ? explode(',', $item[2]) : [];

                                                // Update Category
                                                if (count($categories) > 0 && count($edit_categories) > 0 && (count($categories) == count($edit_categories))) {
                                                    foreach ($categories as $key => $cat_id) {
                                                        $cat_name_key = trim($item[1]) . "_name";
                                                        $cat_name = (isset($edit_categories[$key])) ? $edit_categories[$key] : '';
                                                        $category = Category::find($cat_id);
                                                        $category->$cat_name_key = $cat_name;
                                                        $category->update();
                                                    }
                                                }

                                                $ins_item_id = $product_ids[$def_key];

                                                $item_name_key = trim($item[1]) . "_name";
                                                $item_description_key = trim($item[1]) . "_description";
                                                $item_price_label_key = trim($item[1]) . "_label";
                                                $lang_tags = isset($item[26]) ? explode(',', $item[26]) : [];

                                                $edit_item =  Items::find($ins_item_id);

                                                $edit_item->name = $item[3];
                                                $edit_item->$item_name_key = $item[3];
                                                $edit_item->description = $item[4];
                                                $edit_item->$item_description_key = $item[4];

                                                $edit_item->update();

                                                if ($edit_item) {

                                                    if ($edit_item->type == 1) {
                                                        $item_price_arr['price'] = [
                                                            (isset($item[6])) ? $item[6] : '',
                                                            (isset($item[8])) ? $item[8] : '',
                                                            (isset($item[10])) ? $item[10] : '',
                                                            (isset($item[12])) ? $item[12] : '',
                                                            (isset($item[14])) ? $item[14] : '',
                                                            (isset($item[16])) ? $item[16] : '',
                                                            (isset($item[18])) ? $item[18] : '',
                                                            (isset($item[20])) ? $item[20] : '',
                                                            (isset($item[22])) ? $item[22] : '',
                                                            (isset($item[24])) ? $item[24] : '',
                                                        ];

                                                        $item_price_arr['label'] = [
                                                            (isset($item[5])) ? $item[5] : '',
                                                            (isset($item[7])) ? $item[7] : '',
                                                            (isset($item[9])) ? $item[9] : '',
                                                            (isset($item[11])) ? $item[11] : '',
                                                            (isset($item[13])) ? $item[13] : '',
                                                            (isset($item[15])) ? $item[15] : '',
                                                            (isset($item[17])) ? $item[17] : '',
                                                            (isset($item[19])) ? $item[19] : '',
                                                            (isset($item[21])) ? $item[21] : '',
                                                            (isset($item[23])) ? $item[23] : '',
                                                        ];

                                                        $price_array['price'] = isset($item_price_arr['price']) ? array_filter($item_price_arr['price']) : [];
                                                        $price_array['label'] = isset($item_price_arr['label']) ? $item_price_arr['label'] : [];

                                                        if (count($price_array['price']) > 0) {
                                                            $price = $price_array;
                                                        } else {
                                                            $price = [];
                                                        }
                                                    } else {
                                                        $price = [];
                                                    }

                                                    // Update Price
                                                    if (count($price) > 0) {
                                                        $price_arr = $price['price'];
                                                        $label_arr = $price['label'];

                                                        if (count($price_arr) > 0) {
                                                            foreach ($price_arr as $key => $price_val) {
                                                                $label_val = isset($label_arr[$key]) ? $label_arr[$key] : '';
                                                                $price_id = isset($price_ids_arr[$def_price_key]) ? $price_ids_arr[$def_price_key] : '';

                                                                if (!empty($price_id) || $price_id != '') {
                                                                    $upd_price = ItemPrice::find($price_id);
                                                                    $upd_price->price = $price_val;
                                                                    $upd_price->label = $label_val;
                                                                    $upd_price->$item_price_label_key = $label_val;
                                                                    $upd_price->update();
                                                                }

                                                                $def_price_key++;
                                                            }
                                                        }
                                                    }

                                                    // Insert & Update Tags
                                                    if (count($lang_tags) > 0) {
                                                        foreach ($lang_tags as $key => $tag) {
                                                            $tag = trim($tag);
                                                            $ins_tag_id = isset($tag_ids[$def_tag_key]) ? $tag_ids[$def_tag_key] : '';

                                                            if (!empty($ins_tag_id)) {
                                                                $tag_name_key = trim($item[1]) . "_name";
                                                                $findTag = Tags::where('shop_id', $this->shop_id)->where($tag_name_key, $tag)->where('id', '!=', $ins_tag_id)->first();
                                                                $tag_id = (isset($findTag->id) && !empty($findTag->id)) ? $findTag->id : '';

                                                                if (empty($tag_id)) {
                                                                    $edit_tag = Tags::find($ins_tag_id);
                                                                    $edit_tag->$item_name_key = $tag;
                                                                    $edit_tag->update();
                                                                    $def_tag_key++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }


                                                $def_key++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {

                        // Import Other Category
                        $max_category_order_key = Category::where('shop_id', $this->shop_id)->max('order_key');
                        $category_order = (isset($max_category_order_key) && !empty($max_category_order_key)) ? ($max_category_order_key + 1) : 1;

                        $category = new Category();
                        $category->shop_id = $this->shop_id;
                        $category->order_key = $category_order;
                        $category->category_type = $category_type;
                        $category->parent_category = ($category_type == 'parent_category') ? 1 : 0;
                        $category->schedule_type = $schedule_type;
                        $category->schedule_value = $schedule_array;
                        if ($category_type == 'link') {
                            $category->link_url = $link_url;
                        }
                        foreach ($langs as $key => $lang) {
                            $lang = trim($lang);
                            $name_key = $lang . "_name";
                            $lang_category_name = isset($rows[3][$key]) ? $rows[3][$key] : '';
                            $category->$name_key = $lang_category_name;
                        }
                        $category->published = 1;
                        $category->save();
                    }

                    return redirect()->route('admin.import.export')->with('success', "Data has been Imported SuccessFully");
                } catch (\Throwable $th) {
                    return redirect()->route('admin.import.data')->with('error', 'Oops Something Went Wrong!');
                }
            } else {
                return redirect()->route('admin.import.data')->with('error', 'Oops Something Went Wrong!');
            }
        } else {
            return redirect()->route('admin.import.data')->with('error', 'Oops Something Went Wrong!');
        }
    }
}
