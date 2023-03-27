@php
    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

@endphp

@extends('client.layouts.client-layout')

@section('title', __('Banner'))

@section('content')

    <section class="logo_sec">
        <div class="row">
            <div class="col-md-12">
                <div class="add_logo_sec">
                    <div class="add_logo_sec_header">
                        <h2>{{ __('Banner')}}</h2>
                        <code>{{ __('Banner Dimensions (1920*300)') }}</code>
                    </div>

                    @php
                        $primary_code = isset($primary_language_detail['code']) ? $primary_language_detail['code'] : '';
                        $primary_name = isset($primary_language_detail['name']) ? $primary_language_detail['name'] : '';

                        $primary_banner_id = $primary_code."_banner_img";
                        $primary_form_id = $primary_code."_banner_form";

                        $primary_title_key = $primary_code."_title";
                        $primary_banner_key = $primary_code."_image";

                        $primary_banner_text = isset($banner_setting[$primary_title_key]) ? $banner_setting[$primary_title_key] : '';

                        $primary_banner_image = isset($banner_setting[$primary_banner_key]) ? $banner_setting[$primary_banner_key] : '';
                    @endphp

                    @if(count($additional_languages) > 0)
                        <div class="add_logo_sec_body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">

                                {{-- For Primary Language --}}
                                <li class="nav-item" role="presentation">
                                    <button title="{{ $primary_name }}" class="nav-link active" id="{{ $primary_code }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $primary_code }}" type="button" role="tab" aria-controls="{{ $primary_code }}" aria-selected="true" onclick="setTextEditor('{{ $primary_code }}')">{{ strtoupper($primary_code) }}</button>
                                </li>

                                {{-- For Additional Language --}}
                                @foreach ($additional_languages as $value)
                                    @php
                                        // Additional Language Details
                                        $add_lang_detail = App\Models\Languages::where('id',$value->language_id)->first();
                                        $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                                        $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';
                                    @endphp

                                    <li class="nav-item" role="presentation">
                                        <button title="{{ $add_lang_name }}" data-bs-toggle="tab"  class="nav-link" id="{{ $add_lang_code }}-tab" data-bs-target="#{{ $add_lang_code }}" type="butto
                                        " role="tab" aria-controls="{{ $add_lang_code }}" aria-selected="false" onclick="setTextEditor('{{ $add_lang_code }}')">{{ strtoupper($add_lang_code) }}</button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="myTabContent">

                                {{-- Primary Tab --}}
                                <div class="tab-pane fade show active mt-3" id="{{ $primary_code }}" role="tabpanel" aria-labelledby="{{ $primary_code }}-tab">
                                    <form method="POST" id="{{ $primary_form_id }}" action="{{ route('design.banner.update') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                                        <input type="hidden" name="lang_code" id="lang_code" value="{{ $primary_code }}">

                                        <div class="add_logo_sec_body">
                                            <label class="form-label">{{ __('Banner will appear on the top of your menu')}}</label>
                                            <div class="add_logo_sec_body_inr">
                                                <label class="position-relative" for="{{ $primary_banner_id }}" style="cursor: pointer;">
                                                    @if(!empty($primary_banner_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$primary_banner_image))
                                                        <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/banners/'.$primary_banner_image) }}" width="200px">
                                                        <a href="{{ route('design.banner.delete',$primary_code) }}" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>
                                                    @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                                    @endif
                                                </label>
                                                <input type="file" name="banner" onchange="uploadImage('{{ $primary_form_id }}')" id="{{ $primary_banner_id }}" style="display: none;" />
                                            </div>
                                        </div>

                                        <div class="banner_text">
                                            <label class="form-label">{{ __('Banner Text')}}</label>
                                            <textarea class="form-control" name="banner_text" id="banner_text">{{ $primary_banner_text }}</textarea>
                                        </div>

                                        <div class="add_logo_sec_body">
                                            <button class="btn btn-success">{{ __('Update')}}</button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Additional Tabs --}}
                                @foreach($additional_languages as $value)
                                    @php
                                        // Additional Language Details
                                        $add_lang_detail = App\Models\Languages::where('id',$value->language_id)->first();
                                        $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                                        $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';
                                        $add_input_lang_code = "'$add_lang_code'";

                                        $add_banner_id = $add_lang_code."_banner_img";
                                        $add_form_id = $add_lang_code."_banner_form";

                                        $add_title_key = $add_lang_code."_title";
                                        $add_banner_key = $add_lang_code."_image";

                                        $add_banner_text = isset($banner_setting[$add_title_key]) ? $banner_setting[$add_title_key] : '';

                                        $add_banner_image = isset($banner_setting[$add_banner_key]) ? $banner_setting[$add_banner_key] : '';

                                    @endphp

                                    <div class="tab-pane fade show mt-3" id="{{ $add_lang_code }}" role="tabpanel" aria-labelledby="{{ $add_lang_code }}-tab">
                                        <form method="POST" id="{{ $add_form_id }}" action="{{ route('design.banner.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                                            <input type="hidden" name="lang_code" id="lang_code" value="{{ $add_lang_code }}">

                                            <div class="add_logo_sec_body">
                                                <label class="form-label">{{ __('Banner will appear on the top of your menu')}}</label>
                                                <div class="add_logo_sec_body_inr">
                                                    <label class="position-relative" for="{{ $add_banner_id }}" style="cursor: pointer;">
                                                        @if(!empty($add_banner_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$add_banner_image))
                                                            <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/banners/'.$add_banner_image) }}" width="200px">
                                                            <a href="{{ route('design.banner.delete',$add_lang_code) }}" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>
                                                        @else
                                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                                        @endif
                                                    </label>
                                                    <input type="file" name="banner" onchange="uploadImage('{{ $add_form_id }}')" id="{{ $add_banner_id }}" style="display: none;" />
                                                </div>
                                            </div>

                                            <div class="banner_text">
                                                <label class="form-label">{{ __('Banner Text')}}</label>
                                                <textarea class="form-control" name="banner_text" id="banner_text">{{ $add_banner_text }}</textarea>
                                            </div>

                                            <div class="add_logo_sec_body">
                                                <button class="btn btn-success">{{ __('Update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else

                        <form method="POST" id="{{ $primary_form_id }}" action="{{ route('design.banner.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                            <input type="hidden" name="lang_code" id="lang_code" value="{{ $primary_code }}">

                            <div class="add_logo_sec_body">
                                <label class="form-label">{{ __('Banner will appear on the top of your menu')}}</label>
                                <div class="add_logo_sec_body_inr">
                                    <label class="position-relative" for="{{ $primary_banner_id }}" style="cursor: pointer;">
                                        @if(!empty($primary_banner_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$primary_banner_image))
                                            <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/banners/'.$primary_banner_image) }}" width="200px">
                                            <a href="{{ route('design.banner.delete',$primary_code) }}" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                    </label>
                                    <input type="file" name="banner" onchange="uploadImage('{{ $primary_form_id }}')" id="{{ $primary_banner_id }}" style="display: none;" />
                                </div>
                            </div>

                            <div class="banner_text">
                                <label class="form-label">{{ __('Banner Text')}}</label>
                                <textarea class="form-control" name="banner_text" id="banner_text">{{ $primary_banner_text }}</textarea>
                            </div>

                            <div class="add_logo_sec_body">
                                <button class="btn btn-success">{{ __('Update')}}</button>
                            </div>

                        </form>
                    @endif
                    {{-- <div class="banner_text">
                        <label class="form-label">Banner Text</label>
                        <textarea name="" id="" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="add_logo_sec_body">
                        <label class="form-label">Banner will appear on the top of your menu</label>
                        <div class="add_logo_sec_body_inr">
                            <input type="image" src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                            <input type="file" id="my_file" style="display: none;" />
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

@endsection

{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        $(document).ready(function()
        {
            @if (Session::has('success'))
                toastr.success('{{ Session::get('success') }}')
            @endif

            // Text Editor
            CKEDITOR.ClassicEditor.create(document.getElementById("banner_text"),
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
            });
        });

        // Submit Form After Select Image
        function uploadImage(formID)
        {
            $('#'+formID).submit();
        }


        function setTextEditor(tabID)
        {
            var text_id = "#"+tabID+" #banner_text";
            var textarea = $(text_id)[0];

            $('.ck-editor').remove();

            // Text Editor
            CKEDITOR.ClassicEditor.create(textarea,
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
            });
        }

    </script>

@endsection
