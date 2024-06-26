@php

    $admin_settings = getAdminSettings();
    $light_img = isset($admin_settings['default_light_theme_image']) ? $admin_settings['default_light_theme_image'] : '';
    $dark_img = isset($admin_settings['default_dark_theme_image']) ? $admin_settings['default_dark_theme_image'] : '';
    $shop_settings = getClientSettings();
    $active_theme = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    // Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
    
    $themeSettings = themeSettings($active_theme);
    $theme_preview_image = $themeSettings['theme_preview_image'] ?? "";

@endphp

@extends('client.layouts.client-layout')

@section('title', __('Themes'))

@section('content')

    <!--Theme Image Modal -->
    <div class="modal fade" id="ThemeImageModal" tabindex="-1" aria-labelledby="ThemeImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="ThemeImageModalLabel">{{ __('Upload Theme Image')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="upload_form" action="{{ route('upload.theme.image') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="theme_preview_image" name="theme_preview_image">
                        <input type="hidden" id="upload_image_id" name="theme_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="UploadThemeImage()" class="btn btn-success m-0">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <section class="theme_section">
        <div class="sec_title">
            <h2>{{ __('Themes')}}</h2>
            <p> {{ __('Select a theme and preview your menu to check the result. Click on ‘Add theme’ and edit all available features.')}}
            </p>
        </div>
        <div class="row">

            @if(count($themes) > 0)
                @foreach ($themes as $theme)
                    @php
                        $theme_setting = themeSettings($theme->id);
                        $theme_preview_image = isset($theme_setting['theme_preview_image']) ? $theme_setting['theme_preview_image'] : '';
                    @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="item_box">
                            <div class="item_img add_category add_theme">
                                @if($theme->is_default == 1)
                                    @if($theme->name == 'Default Light Theme')
                                        @if(!empty($light_img))
                                            <img src="{{ $light_img }}" class="w-100">
                                        @endif
                                    @else
                                        @if(!empty($dark_img))
                                            <img src="{{ $dark_img }}" class="w-100">
                                        @endif
                                    @endif
                                @else
                                    @if(!empty($theme_preview_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/theme_preview_image/'.$theme_preview_image))
                                        <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/theme_preview_image/'.$theme_preview_image) }}" class="w-100">
                                    @endif
                                @endif

                                {{-- @if($theme->is_default == 0)
                                    <a class="add_category_bt">
                                        <i class="fa-solid fa-bolt icon_none"></i>
                                    </a>
                                @endif --}}
                                <div class="edit_item_bt">
                                    @if($theme->is_default == 0)
                                        @if(isset($package_permissions['add_edit_clone_theme']) && !empty($package_permissions['add_edit_clone_theme']) && $package_permissions['add_edit_clone_theme'] == 1)
                                            <a href="{{ route('design.theme-preview',$theme->id) }}" class="btn edit_item">{{ __('Edit')}}</a>
                                        @endif
                                    @endif

                                    @if(isset($package_permissions['add_edit_clone_theme']) && !empty($package_permissions['add_edit_clone_theme']) && $package_permissions['add_edit_clone_theme'] == 1)
                                        <a href="{{ route('theme.clone',$theme->id) }}" class="btn edit_category">{{ __('Clone')}}</a>
                                    @endif

                                    @if($theme->is_default == 0)
                                        @if (!empty($theme_preview_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/theme_preview_image/'.$theme_preview_image))
                                            <a href="{{ route('delete.theme.image', $theme->id) }}" class="btn remove_theme_image">{{ __('Remove Theme Image') }}</a>
                                        @else
                                            <a onclick="openUploadThemeImageModal({{$theme->id}})" class="btn upload_theme_image" id="upload_button">{{ __('Upload Theme Image') }}</a>
                                        @endif
                                    @endif

                                </div>
                                @if($theme->is_default == 0)
                                    @if($active_theme != $theme->id)
                                        <a href="{{ route('theme.delete',$theme->id) }}" class="delet_bt">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="item_info">
                                <div class="item_name">
                                    <h3>{{ $theme->name }}</h3>
                                    <label class="switch">
                                        <input type="checkbox" name="is_default" id="is_default" {{ ($active_theme == $theme->id) ? 'checked disabled' : '' }} onchange="changeActiveTheme({{ $theme->id }})">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                </div>
                                <h2>{{ __('Theme')}}</h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif


            @if(isset($package_permissions['add_edit_clone_theme']) && !empty($package_permissions['add_edit_clone_theme']) && $package_permissions['add_edit_clone_theme'] == 1)
                <div class="col-md-6 col-lg-3">
                    <div class="item_box">
                        <div class="item_img add_category add_theme">
                            <a href="#" class="add_category_bt">
                                <i class="fa-solid fa-image icon_none"></i>
                            </a>
                            <div class="edit_item_bt">
                                <a href="{{ route('design.theme-create') }}" class="btn edit_item">{{ __('Add New Theme')}}</a>
                            </div>
                        </div>
                        <div class="item_info">
                            <div class="item_name">
                                <h3>{{ __('Add theme')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </section>

@endsection

{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        // Success Toastr Message
        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        // Error Toastr Message
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif


        // Function for Change Active Theme
        function changeActiveTheme(themeID)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('theme.change') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "theme_id" : themeID,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }


        function openUploadThemeImageModal(themeID){
            $('#upload_image_id').val(themeID);
            $('#ThemeImageModal').modal('show');
        }

        
        function UploadThemeImage() {
            const formData = new FormData(document.getElementById('upload_form'));

            $.ajax({
                type: "POST",
                url: "{{ route('upload.theme.image') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success == 1) {
                        toastr.success(response.message);
                        $('#ThemeImageModal').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(response){
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';
                    if (validationErrors != '')
                    {
                        // image Error
                        var imageError = (validationErrors.theme_preview_image) ? validationErrors.theme_preview_image : '';
                        if (imageError != '')
                        {
                            $('#upload_form #theme_preview_image').addClass('is-invalid');
                            toastr.error(imageError);
                        }
                    }
                }
            });
        }

    </script>

@endsection
