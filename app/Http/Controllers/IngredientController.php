<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Display all Ingredients
    public function index()
    {
        $data['ingredients'] = Ingredient::get();
        return view('admin.ingredients.ingredients',$data);
    }



    // Create new Ingredients
    public function insert()
    {
        return view('admin.ingredients.new_ingredients');
    }



    // Store Newly Created Ingredient
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required|mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG|dimensions:width=80,height=80',
        ]);

        $ingredient = new Ingredient();
        $ingredient->name = $request->name;
        $ingredient->status = isset($request->status) ? $request->status : 0;

        // Insert Ingredient Icon if is Exists
        if($request->hasFile('icon'))
        {
            $imgname = "ingredient_".time().".". $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move(public_path('admin_uploads/ingredients/'), $imgname);
            $ingredient->icon = $imgname;
        }

        $ingredient->save();

        return redirect()->route('ingredients')->with('success','Ingredient has been Inserted SuccessFully....');

    }



    // Edit Specific Ingredient
    public function edit($id)
    {
        $data['ingredient'] = Ingredient::where('id',$id)->first();
        return view('admin.ingredients.edit_ingredients',$data);
    }



    // Change Status of Ingredients
    public function changeStatus(Request $request)
    {
        // Ingredient ID & Status
        $ingredient_id = $request->id;
        $status = $request->status;

        try
        {
            $ingredient = Ingredient::find($ingredient_id);
            $ingredient->status = $status;
            $ingredient->update();

            return response()->json([
                'success' => 1,
            ]);

        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
            ]);
        }
    }



    // Update Specific Ingredients
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG|dimensions:width=80,height=80',
        ]);

        $ingredient = Ingredient::find($request->ingredient_id);
        $ingredient->name = $request->name;
        $ingredient->status = isset($request->status) ? $request->status : 0;

        // Insert Ingredient Icon if is Exists
        if($request->hasFile('icon'))
        {
            // Delete old Icon
            $old_icon = isset($ingredient->icon) ? $ingredient->icon : "";
            if(!empty($old_icon) && file_exists('public/admin_uploads/ingredients/'.$old_icon))
            {
                unlink('public/admin_uploads/ingredients/'.$old_icon);
            }

            $imgname = "ingredient_".time().".". $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move(public_path('admin_uploads/ingredients/'), $imgname);
            $ingredient->icon = $imgname;
        }

        $ingredient->update();

        return redirect()->route('ingredients')->with('success','Ingredient has been Updated SuccessFully....');
    }



    // Destroy (Delete) Ingredient
    public function destroy($id)
    {
        // Get Ingredient Details
        $ingredient = Ingredient::where('id',$id)->first();
        $ingredient_icon = isset($ingredient->icon) ? $ingredient->icon : '';
        if(!empty($ingredient_icon) && file_exists('public/admin_uploads/ingredients/'.$ingredient_icon))
        {
            unlink('public/admin_uploads/ingredients/'.$ingredient_icon);
        }

        // Delete Ingredient
        Ingredient::where('id',$id)->delete();

        return redirect()->route('ingredients')->with('success','Ingredient has been Removed SuccessFully..');
    }
}
