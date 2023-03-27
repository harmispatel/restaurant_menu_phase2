@php
    $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : "";
    $primary_lang_details = clientLanguageSettings($shop_id);

    $language = getLangDetails(isset($primary_lang_details['primary_language']) ? $primary_lang_details['primary_language'] : '');
    $language_code = isset($language['code']) ? $language['code'] : '';
    $name_key = $language_code."_name";

    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Items'))

@section('content')

    {{-- Add Modal --}}
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">{{ __('Create New Item')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm" enctype="multipart/form-data">
                        @csrf
                        {{-- <input type="hidden" name="category_id" id="category_id" value="{{ $category->id }}"> --}}
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="type" class="form-label">{{ __('Type')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="type" id="type" onchange="togglePrice('add')" class="form-control">
                                        <option value="1">{{ __('Product')}}</option>
                                        <option value="2">{{ __('Divider')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="category" class="form-label">{{ __('Category')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Choose Category</option>
                                        @if(count($categories) > 0)
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ ($cat_id == $cat->id) ? 'selected' : '' }}>{{ $cat->en_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="name" class="form-label">{{ __('Name')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Item Name">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="description" class="form-label">{{ __('Description')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <textarea class="form-control" name="description" id="description" rows="5" placeholder="Item Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="image" class="form-label">{{ __('Image')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div id="img-label">
                                                <label for="image" style="cursor: pointer">
                                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100" id="crp-img-prw">
                                                </label>
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control" style="display: none;">
                                            <input type="hidden" name="og_image" id="og_image">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <code>Upload Image in (200*200) Dimensions</code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 img-crop-sec mb-2" style="display: none">
                                <img src="" alt="" id="resize-image" class="w-100">
                                <div class="mt-3">
                                    <a class="btn btn-sm btn-success" onclick="saveCropper('addItemForm')">Save</a>
                                    <a class="btn btn-sm btn-danger" onclick="resetCropper()">Reset</a>
                                    <a class="btn btn-sm btn-secondary" onclick="cancelCropper('addItemForm')">Cancel</a>
                                </div>
                            </div>
                            <div class="col-md-4 img-crop-sec" style="display: none;">
                                <div class="preview" style="width: 200px; height:200px; overflow: hidden;margin: 0 auto;"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="ingredients" class="form-label">{{ __('Indicative Icons')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="ingredients[]" id="ingredients" class="form-control" multiple>
                                        @if(count($ingredients) > 0)
                                            @foreach ($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="tags" class="form-label">{{ __('Tags')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="tags[]" id="tags" class="form-control" multiple>
                                        @if(count($tags) > 0)
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 price_div">
                            <div class="col-md-3">
                                <label for="price" class="form-label">{{ __('Price')}}</label>
                            </div>
                            <div class="col-md-9 priceDiv" id="priceDiv">
                                <div class="row mb-3 align-items-center price price_1">
                                    <div class="col-md-5 mb-1">
                                        <input type="text" name="price[price][]" class="form-control" placeholder="Enter Price">
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <input type="text" name="price[label][]" class="form-control" placeholder="Enter Price Label">
                                    </div>
                                    <div class="col-md-1 mb-1">
                                        <a onclick="$('.price_1').remove()" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4 priceDiv price_div justify-content-end">
                            <div class="col-md-3">
                                <a onclick="addPrice('add')" class="btn addPriceBtn btn-info text-white">{{ __('Add Price')}}</a>
                            </div>
                        </div>
                        <div class="row mb-3 calories_div">
                            <div class="col-md-3">
                                <label for="calories" class="form-label">{{ __('Calories')}}</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" name="calories" class="form-control" id="calories" placeholder="Enter Calories">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mark_new">
                                <div class="form-group">
                                    <label class="switch me-2">
                                        <input type="checkbox" id="mark_new" name="is_new" value="1">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="mark_new" class="form-label">{{ __('Mark Item as New')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 mark_sign">
                                <div class="form-group">
                                    <label class="switch me-2">
                                        <input type="checkbox" id="mark_sign" name="is_sign" value="1">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="mark_sign" class="form-label">{{ __('Mark Item as Signature')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 day_special">
                                <div class="form-group">
                                    <label class="switch me-2">
                                        <input type="checkbox" id="day_special" name="day_special" value="1">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="day_special" class="form-label">{{ __('Mark Item as Day Special')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label class="switch me-2">
                                        <input type="checkbox" id="publish" name="published" value="1">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                    <label for="publish" class="form-label">{{ __('Published')}}</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn close-btn btn-secondary" data-bs-dismiss="modal">{{ __('Close')}}</button>
                    <a class="btn btn-primary" id="saveItem" onclick="saveItem()">{{ __('Save')}}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">{{ __('Edit Item')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="item_lang_div">
                </div>
            </div>
        </div>
    </div>

    {{-- EditTag Modal --}}
    <div class="modal fade" id="updateTagModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTagModalLabel">{{ __('Edit Tag')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateTagForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tag_id" id="tag_id" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="tag_name" id="tag_name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close')}}</button>
                        <a class="btn btn-primary" id="updateTag" onclick="updateTag()">{{ __('Update')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cat ID --}}
    <input type="hidden" name="cat_id" id="cat_id" value="{{ $cat_id }}">

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Items')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('categories') }}">{{ __('Categories')}}</a></li>
                        <li class="breadcrumb-item active">{{ (isset($category->en_name) && !empty($category->en_name)) ? $category->en_name : 'All' }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Items Section --}}
    <section class="section dashboard">
        <div class="row">
            {{-- Error Message Section --}}
            @if (session()->has('error'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            {{-- Success Message Section --}}
            @if (session()->has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="main_section">
                <div class="container-fluid">
                    <div class="main_section_inr">
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <select name="cat_filter" id="cat_filter" class="form-control">
                                    <option value="">Filter By Category</option>
                                    @if(count($categories) > 0)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ ($cat_id == $category->id) ? 'selected' : '' }}>{{ $category->en_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="search_box">
                                    <div class="form-group position-relative">
                                        <input type="text" id="search" class="form-control" placeholder="Search">
                                        <i class="fa-solid fa-magnifying-glass search_icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sec_title">
                            <h3>{{ __('Tags')}}</h3>
                        </div>
                        <div class="row mb-4 connectedSortableTags" id="tagsSorting">
                            {{-- Tags Section --}}
                            @if(count($cat_tags) > 0)
                                @foreach ($cat_tags as $tag)
                                    <div class="col-sm-2"  tag-id="{{ $tag->hasOneTag['id'] }}">
                                        <div class="product-tags">
                                            {{ isset($tag->hasOneTag[$name_key]) ? $tag->hasOneTag[$name_key] : '' }}
                                            <i class="fa fa-edit" onclick="editTag({{ $tag->hasOneTag['id'] }})" style="cursor: pointer"></i>
                                            <i class="fa fa-trash" onclick="deleteTag({{ $tag->hasOneTag['id'] }})" style="cursor: pointer"></i>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="sec_title">
                            <h3>{{ __('Items')}}</h3>
                        </div>
                        <div class="row connectedSortableItems" id="ItemSection">
                            {{-- Itens Section --}}
                            @if(count($items) > 0)
                                @foreach ($items as $item)
                                    <div class="col-md-3" item-id="{{ $item->id }}">
                                        <div class="item_box">
                                            <div class="item_img">
                                                <a>
                                                    @if(!empty($item->image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item->image))
                                                        <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item->image) }}" class="w-100">
                                                    @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                    @endif
                                                </a>
                                                <div class="edit_item_bt">
                                                    <button class="btn edit_category" onclick="editItem({{ $item->id }})">{{ __('EDIT ITEM')}}</button>
                                                </div>
                                                <a class="delet_bt" onclick="deleteItem({{ $item->id }})" style="cursor: pointer;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                                <a class="cat_edit_bt" onclick="editItem({{ $item->id }})">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            </div>
                                            <div class="item_info">
                                                <div class="item_name">
                                                    <h3>{{ $item->en_name }}</h3>
                                                    <div class="form-check form-switch">
                                                        @php
                                                            $newStatus = ($item->published == 1) ? 0 : 1;
                                                        @endphp
                                                        <input class="form-check-input" type="checkbox" name="status" role="switch" id="status" onclick="changeStatus({{ $item->id }},{{ $newStatus }})" value="1" {{ ($item->published == 1) ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                @if($item->type == 1)
                                                    <h2>{{ __('Item')}}</h2>
                                                @else
                                                    <h2>{{ __('Divider')}}</h2>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Add New Item Section --}}
                            <div class="col-md-3">
                                <div class="item_box">
                                    <div class="item_img add_category">
                                        <a data-bs-toggle="modal" data-bs-target="#addItemModal" class="add_category_bt" id="NewItemBtn">
                                            <i class="fa-solid fa-plus"></i>
                                        </a>
                                    </div>
                                    <div class="item_info text-center">
                                        <h2>{{ __('Add New Item')}}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        var cropper;
        var addItemEditor;
        var editItemEditor;

        // Reset AddItem Modal & Form
        $('#NewItemBtn').on('click',function()
        {
            // Reset addItemForm
            $('#addItemForm').trigger('reset');

            // Remove Validation Class
            $('#addItemForm #name').removeClass('is-invalid');
            $('#addItemForm #image').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            // Intialized Ingredients SelectBox
            $("#addItemForm #ingredients").select2({
                dropdownParent: $("#addItemModal"),
                placeholder: "Select Indicative Icons",
            });

            // Intialized Tags SelectBox
            $("#addItemForm #tags").select2({
                dropdownParent: $("#addItemModal"),
                placeholder: "Select Tags",
                tags: true,
                // tokenSeparators: [',', ' ']
            });

            $('.ck-editor').remove();
            addItemEditor = "";

            var item_textarea = $('#addItemForm #description')[0];

            // Text Editor
            CKEDITOR.ClassicEditor.create(item_textarea,
            {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                'height':500,
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType'
                ]
            }).then( editor => {
                addItemEditor = editor;
            });

            if(cropper)
            {
                cancelCropper('addItemForm');
            }

        });

        // Remove Some Fetaures when Close Add Modal
        $('#addItemModal .btn-close, #addItemModal .close-btn').on('click',function()
        {
            deleteCropper('addItemForm');
            $('.ck-editor').remove();
            addItemEditor = "";
        });

        // Remove Text Editor from Edit Item Modal
        $('#editItemModal .btn-close').on('click',function()
        {
            editItemEditor = "";
            $('.ck-editor').remove();
            if(cropper)
            {
                cropper.destroy();
            }
            $('#editItemModal #item_lang_div').html('');
        });

        // Function for add New Price
        function addPrice(key)
        {
            if(key === 'add')
            {
                var formType = "#addItemForm #priceDiv";
            }
            else
            {
                var formType = "#"+key+" #priceDiv";
            }

            var count = $(formType).children('.price').length;
            var html = "";
            count ++;

            html += '<div class="row mb-3 align-items-center price price_'+count+'">';
                html += '<div class="col-md-5 mb-1">';
                    html += '<input type="text" name="price[price][]" class="form-control" placeholder="Enter Price">';
                html += '</div>';
                html += '<div class="col-md-6 mb-1">';
                    html += '<input type="text" name="price[label][]" class="form-control" placeholder="Enter Price Label">';
                html += '</div>';
                html += '<div class="col-md-1 mb-1">';
                    html += '<a onclick="$(\'.price_'+count+'\').remove()" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                html += '</div>';
            html += '</div>';

            $(formType).append(html);
        }


        // Set TextEditor
        function setTextEditor(formID)
        {
            var my_item_textarea = $('#item_description_'+formID)[0];
            editItemEditor = "";
            $('.ck-editor').remove();

            // Text Editor
            CKEDITOR.ClassicEditor.create(my_item_textarea,
            {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                'height':500,
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType'
                ]
            }).then( editor => {
                editItemEditor = editor;
            });
        }


        // Save New Item
        function saveItem()
        {
            const myFormData = new FormData(document.getElementById('addItemForm'));
            myFormData.set('description',addItemEditor.getData());

            // Remove Validation Class
            $('#addItemForm #name').removeClass('is-invalid');
            $('#addItemForm #image').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('items.store') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#addItemForm').trigger('reset');
                        $('#addItemModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        $('#addItemForm').trigger('reset');
                        $('#addItemModal').modal('hide');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    // All Validation Errors
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';

                    if (validationErrors != '')
                    {
                        // Name Error
                        var nameError = (validationErrors.name) ? validationErrors.name : '';
                        if (nameError != '')
                        {
                            $('#addItemForm #name').addClass('is-invalid');
                            toastr.error(nameError);
                        }

                        // Image Error
                        var imageError = (validationErrors.image) ? validationErrors.image : '';
                        if (imageError != '')
                        {
                            $('#addItemForm #image').addClass('is-invalid');
                            toastr.error(imageError);
                        }
                    }
                }
            });
        }



        // Function for Delete Item
        function deleteItem(itemId)
        {
            swal({
                title: "Are you sure You want to Delete It ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDeleteItem) =>
            {
                if (willDeleteItem)
                {
                    $.ajax({
                        type: "POST",
                        url: '{{ route("items.delete") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': itemId,
                        },
                        dataType: 'JSON',
                        success: function(response)
                        {
                            if (response.success == 1)
                            {
                                toastr.success(response.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1300);
                            }
                            else
                            {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
                else
                {
                    swal("Cancelled", "", "error");
                }
            });
        }



        // Function for Change Item Status
        function changeStatus(itemId, status)
        {
            $.ajax({
                type: "POST",
                url: '{{ route("items.status") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status':status,
                    'id':itemId
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                }
            });
        }



        // Function for Get Filterd Items
        $('#search').on('keyup',function()
        {
            var keywords = $(this).val();
            var catId = $('#cat_id').val();

            $.ajax({
                type: "POST",
                url: '{{ route("items.search") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'keywords':keywords,
                    'id':catId,
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        $('#ItemSection').html('');
                        $('#ItemSection').append(response.data);
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });

        });



        // Function for Edit Item
        function editItem(itemID)
        {
            // Reset All Form
            $('#editItemModal #item_lang_div').html('');

            // Clear all Toastr Messages
            toastr.clear();

            $('.ck-editor').remove();
            editItemEditor = "";

            $.ajax({
                type: "POST",
                url: "{{ route('items.edit') }}",
                dataType: "JSON",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': itemID,
                },
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        $('#editItemModal #item_lang_div').html('');
                        $('#editItemModal #item_lang_div').append(response.data);

                        // Language Array
                        const langArr = response.language_array;

                        // Item Type
                        const itemType = response.item_type;

                        // If Item Type is Divider Then Hide Price Divs
                        if(itemType === 2)
                        {
                            $('.price_div').hide();
                            $('.calories_div').hide();
                            $('.day_special').hide();
                            $('.mark_sign').hide();
                            $('.mark_new').hide();
                        }
                        else
                        {
                            $('.price_div').show();
                            $('.calories_div').show();
                            $('.day_special').show();
                            $('.mark_sign').show();
                            $('.mark_new').show();
                        }

                        // Reinitialized all Select 2
                        if(langArr != undefined)
                        {
                            $.each(langArr, function (index, value)
                            {
                                var ingredientsEle = "#editItemModal #"+value+"_ingredients";
                                var tagsEle = "#editItemModal #"+value+"_tags";

                                // Intialized Ingredients SelectBox
                                $(ingredientsEle).select2({
                                    dropdownParent: $("#editItemModal"),
                                    placeholder: "Select Indicative Icons",
                                });

                                // Intialized Tags SelectBox
                                $(tagsEle).select2({
                                    dropdownParent: $("#editItemModal"),
                                    placeholder: "Add New Tags",
                                    tags: true,
                                });
                            });
                        }

                        $('.ck-editor').remove();
                        editItemEditor = "";

                        var my_item_textarea = $('#item_description_'+response.primary_code)[0];

                        // Text Editor
                        CKEDITOR.ClassicEditor.create(my_item_textarea,
                        {
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                    'bulletedList', 'numberedList', 'todoList', '|',
                                    'outdent', 'indent', '|',
                                    'undo', 'redo',
                                    '-',
                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                    'alignment', '|',
                                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                    'sourceEditing'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            list: {
                                properties: {
                                    styles: true,
                                    startIndex: true,
                                    reversed: true
                                }
                            },
                            'height':500,
                            fontSize: {
                                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                                supportAllValues: true
                            },
                            htmlSupport: {
                                allow: [
                                    {
                                        name: /.*/,
                                        attributes: true,
                                        classes: true,
                                        styles: true
                                    }
                                ]
                            },
                            htmlEmbed: {
                                showPreviews: true
                            },
                            link: {
                                decorators: {
                                    addTargetToExternalLinks: true,
                                    defaultProtocol: 'https://',
                                    toggleDownloadable: {
                                        mode: 'manual',
                                        label: 'Downloadable',
                                        attributes: {
                                            download: 'file'
                                        }
                                    }
                                }
                            },
                            mention: {
                                feeds: [
                                    {
                                        marker: '@',
                                        feed: [
                                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                            '@sugar', '@sweet', '@topping', '@wafer'
                                        ],
                                        minimumCharacters: 1
                                    }
                                ]
                            },
                            removePlugins: [
                                'CKBox',
                                'CKFinder',
                                'EasyImage',
                                'RealTimeCollaborativeComments',
                                'RealTimeCollaborativeTrackChanges',
                                'RealTimeCollaborativeRevisionHistory',
                                'PresenceList',
                                'Comments',
                                'TrackChanges',
                                'TrackChangesData',
                                'RevisionHistory',
                                'Pagination',
                                'WProofreader',
                                'MathType'
                            ]
                        }).then( editor => {
                            editItemEditor = editor;
                        });

                        $('#editItemModal').modal('show');
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });
        }



        // Function for Update Item
        function updateItem(langCode)
        {
            var formID = langCode+"_item_form";
            var myFormData = new FormData(document.getElementById(formID));
            myFormData.set('item_description',editItemEditor.getData());

            // Remove Validation Class
            $("#"+formID+' #item_name').removeClass('is-invalid');
            $("#"+formID+' #category').removeClass('is-invalid');
            $("#"+formID+' #item_image').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('items.update') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        // $('#editItemModal').modal('hide');
                        toastr.success(response.message);
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 1000);
                    }
                    else
                    {
                        $('#editItemModal').modal('hide');
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(response)
                {
                    // All Validation Errors
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';

                    if (validationErrors != '')
                    {
                        // Name Error
                        var nameError = (validationErrors.item_name) ? validationErrors.item_name : '';
                        if (nameError != '')
                        {
                            $("#"+formID+' #item_name').addClass('is-invalid');
                            toastr.error(nameError);
                        }

                        // Category Error
                        var categoryError = (validationErrors.category) ? validationErrors.category : '';
                        if (categoryError != '')
                        {
                            $("#"+formID+' #category').addClass('is-invalid');
                            toastr.error(categoryError);
                        }

                        // Image Error
                        var imageError = (validationErrors.item_image) ? validationErrors.item_image : '';
                        if (imageError != '')
                        {
                            $("#"+formID+' #item_image').addClass('is-invalid');
                            toastr.error(imageError);
                        }
                    }
                }
            });

        }



        // Function for Hide & Show Price
        function togglePrice(formType,dynamicID="")
        {
            if(formType === 'add')
            {
                var currVal = $('#type :selected').val();

                if(currVal == 2)
                {
                    $("#addItemForm .price_div").hide();
                    $("#addItemForm .calories_div").hide();
                    $("#addItemForm .day_special").hide();
                    $("#addItemForm .mark_sign").hide();
                    $("#addItemForm .mark_new").hide();
                }
                else
                {
                    $("#addItemForm .price_div").show();
                    $("#addItemForm .calories_div").show();
                    $("#addItemForm .day_special").show();
                    $("#addItemForm .mark_sign").show();
                    $("#addItemForm .mark_new").show();
                }
            }
            else if(formType === 'edit')
            {
                var formId = "#"+dynamicID;
                var currSelectedVal = $(formId+' #type :selected').val();

                if(currSelectedVal == 2)
                {
                    $(formId+" .price_div").hide();
                    $(formId+" .calories_div").hide();
                    $(formId+" .day_special").hide();
                    $(formId+" .mark_sign").hide();
                    $(formId+" .mark_new").hide();
                }
                else
                {
                    $(formId+" .price_div").show();
                    $(formId+" .calories_div").show();
                    $(formId+" .day_special").show();
                    $(formId+" .mark_sign").show();
                    $(formId+" .mark_new").show();
                }

            }
        }



        // Function for Delete Tag
        function deleteTag(Id)
        {
            $.ajax({
                type: "POST",
                url: '{{ route("tags.destroy") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': Id,
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });
        }



        // Function for Edit Tag
        function editTag(Id)
        {
            // Reset editItemForm
            $('#updateTagForm').trigger('reset');

            // Remove Validation Class
            $('#updateTagForm #tag_name').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('tags.edit') }}",
                dataType: "JSON",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': Id,
                },
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        // Tag Data's
                        const tag = response.tag;

                        // Add values in editItemForm
                        $('#updateTagForm #tag_name').val(tag.name);
                        $('#updateTagForm #tag_id').val(tag.id);

                        // Show Modal
                        $('#updateTagModal').modal('show');
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });
        }


        // Function for Update Tag
        function updateTag()
        {
            const myFormData = new FormData(document.getElementById('updateTagForm'));

            // Remove Validation Class
            $('#updateTagForm #tag_name').removeClass('is-invalid');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('tags.update') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#updateTagForm').trigger('reset');
                        $('#updateTagModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        $('#updateTagForm').trigger('reset');
                        $('#updateTagModal').modal('hide');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    // All Validation Errors
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';

                    if (validationErrors != '')
                    {
                        // Name Error
                        var nameError = (validationErrors.tag_name) ? validationErrors.tag_name : '';
                        if (nameError != '')
                        {
                            $('#updateTagForm #tag_name').addClass('is-invalid');
                            toastr.error(nameError);
                        }
                    }
                }
            });

        }


        // Sort Tags
        $( function()
        {
            // Sorting Tags
            $( "#tagsSorting" ).sortable({
                connectWith: ".connectedSortableTags",
                opacity: 0.5,
            }).disableSelection();

            $( ".connectedSortableTags" ).on( "sortupdate", function( event, ui )
            {
                var tagsArr = [];

                $("#tagsSorting .col-sm-2").each(function( index )
                {
                    tagsArr[index] = $(this).attr('tag-id');
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route("tags.sorting") }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'sortArr': tagsArr,
                    },
                    dataType: 'JSON',
                    success: function(response)
                    {
                        if (response.success == 1)
                        {
                            toastr.success(response.message);
                        }
                    }
                });

            });



            // Sorting Items
            $( "#ItemSection" ).sortable({
                connectWith: ".connectedSortableItems",
                opacity: 0.5,
            }).disableSelection();

            $( ".connectedSortableItems" ).on( "sortupdate", function( event, ui )
            {
                var itemsArr = [];

                $("#ItemSection .col-md-3").each(function( index )
                {
                    itemsArr[index] = $(this).attr('item-id');
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route("items.sorting") }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'sortArr': itemsArr,
                    },
                    dataType: 'JSON',
                    success: function(response)
                    {
                        if (response.success == 1)
                        {
                            toastr.success(response.message);
                        }
                    }
                });

            });

        });


        // Function for Get Items By Category ID
        $('#cat_filter').on('change',function(){
            var catID = $('#cat_filter :selected').val();
            var Url = "{{ route('items') }}";
            location.href = Url+"/"+catID;
        });


        // Remove Item Price
        function deleteItemPrice(priceID,count)
        {

            $.ajax({
                type: "POST",
                url: "{{ route('items.delete.price') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "price_id" : priceID,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('.price_'+count).remove();
                    }
                }
            });

        }


        // Image Cropper Functionality for Add Model
        $('#addItemModal #image').on('change',function()
        {
            const myFormID = this.form.id;
            const currentFile = this.files[0];

            if (currentFile)
            {
                fileSize = currentFile.size / 1024 / 1024;
                fileName = currentFile.name;
                fileType = fileName.split('.').pop().toLowerCase();

                if(fileSize > 2)
                {
                    toastr.error("File is to Big "+fileSize.toFixed(2)+"MiB. Max File size : 2 MiB.");
                    $('#'+myFormID+' #image').val('');
                    return false;
                }
                else
                {
                    if($.inArray(fileType, ['gif','png','jpg','jpeg']) == -1)
                    {
                        toastr.error("The Item Image must be a file of type: png, jpg, svg, jpeg");
                        $('#'+myFormID+' #image').val('');
                        return false;
                    }
                    else
                    {
                        if(cropper)
                        {
                            cropper.destroy();
                        }

                        $('#'+myFormID+' #resize-image').attr('src',"");
                        $('#'+myFormID+' #resize-image').attr('src',URL.createObjectURL(currentFile));
                        $('#'+myFormID+' .img-crop-sec').show();

                        const CrpImage = document.getElementById('resize-image');
                        cropper = new Cropper(CrpImage, {
                            aspectRatio: 1 / 1,
                            zoomable:false,
                            cropBoxResizable: false,
                            preview: '#'+myFormID+' .preview',
                        });
                    }
                }
            }
        });


        // Image Cropper Functionality for Edit Modal
        function imageCropper(formID,ele)
        {
            var currentFile = ele.files[0];
            var myFormID = formID+"_item_form";

            if (currentFile)
            {
                fileSize = currentFile.size / 1024 / 1024;
                fileName = currentFile.name;
                fileType = fileName.split('.').pop().toLowerCase();

                if(fileSize > 2)
                {
                    toastr.error("File is to Big "+fileSize.toFixed(2)+"MiB. Max File size : 2 MiB.");
                    $('#'+myFormID+' #'+formID+'item_image').val('');
                    return false;
                }
                else
                {
                    if($.inArray(fileType, ['gif','png','jpg','jpeg']) == -1)
                    {
                        toastr.error("The Item Image must be a file of type: png, jpg, svg, jpeg");
                        $('#'+myFormID+' #'+formID+'item_image').val('');
                        return false;
                    }
                    else
                    {
                        if(cropper)
                        {
                            cropper.destroy();
                            $('.resize-image').attr('src',"");
                            $('.img-crop-sec').hide();
                        }

                        $('#'+myFormID+' #resize-image').attr('src',"");
                        $('#'+myFormID+' #resize-image').attr('src',URL.createObjectURL(currentFile));
                        $('#'+myFormID+' .img-crop-sec').show();

                        // const CrpImage = document.getElementById('resize-image');
                        const CrpImage = $('#'+myFormID+' #resize-image')[0];

                        cropper = new Cropper(CrpImage, {
                            aspectRatio: 1 / 1,
                            zoomable:false,
                            cropBoxResizable: false,
                            preview: '#'+myFormID+' .preview',
                        });
                    }
                }
            }
        }


        // Reset Copper
        function resetCropper(){
            cropper.reset();
        }

        // Canel Cropper
        function cancelCropper(formID)
        {
            cropper.destroy();
            $('#'+formID+' #resize-image').attr('src',"");
            $('#'+formID+' .img-crop-sec').hide();
            $('#'+formID+' #image').val('');
            $('#'+formID+' #'+formID+'item_image').val('');
            $('#'+formID+' #og_image').val('');
        }

        // Save Cropper Image
        function saveCropper(formID)
        {
            var canvas = cropper.getCroppedCanvas({
                width:200,
                height:200
		    });

            canvas.toBlob(function(blob)
            {
                $('#'+formID+" #crp-img-prw").attr('src',URL.createObjectURL(blob));
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function()
                {
                    var base64data = reader.result;
                    $('#'+formID+' #og_image').val(base64data);
                };
            });

            cropper.destroy();
            $('#'+formID+' #resize-image').attr('src',"");
            $('#'+formID+' .img-crop-sec').hide();

            if(formID == 'addItemForm')
            {
                $('#'+formID+" #img-label").append('<a class="btn btn-sm btn-danger" id="del-img" style="border-radius:50%" onclick="deleteCropper(\''+formID+'\')"><i class="fa fa-trash"></i></a>');
            }
            else
            {
                $('#'+formID+' #edit-img').hide();
                $('#'+formID+" #img-label").append('<a class="btn btn-sm btn-danger" id="del-img" style="border-radius:50%" onclick="deleteCropper(\''+formID+'\')"><i class="fa fa-trash"></i></a>');
                $('#'+formID+' #rep-image').show();
            }
        }

        // Delete Cropper
        function deleteCropper(formID)
        {
            if(cropper)
            {
                cropper.destroy();
            }

            $('#'+formID+' #resize-image').attr('src',"");
            $('#'+formID+' .img-crop-sec').hide();
            $('#'+formID+' #og_image').val('');
            $('#'+formID+" #del-img").remove();

            if(formID == 'addItemForm')
            {
                $('#'+formID+' #image').val('');
                $('#'+formID+" #crp-img-prw").attr('src',"{{ asset('public/client_images/not-found/no_image_1.jpg') }}");
            }
            else
            {
                $('#'+formID+' #'+formID+'item_image').val('');
                $('#'+formID+" #crp-img-prw").attr('src',"{{ asset('public/client_images/not-found/no_image_1.jpg') }}");
                $('#'+formID+' #edit-img').show();
                $('#'+formID+' #rep-image').hide();
            }

        }

    </script>

@endsection
