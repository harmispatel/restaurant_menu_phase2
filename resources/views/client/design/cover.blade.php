@php
    $client_settings = getClientSettings();
    $intro_status = isset($client_settings['intro_icon_status']) ? $client_settings['intro_icon_status'] : '';
    $intro_duration = isset($client_settings['intro_icon_duration']) ? $client_settings['intro_icon_duration'] : '';
    $shop_intro_icon = isset($client_settings['shop_intro_icon']) ? $client_settings['shop_intro_icon'] : '';
    $shop_intro_icon_1 = isset($client_settings['shop_intro_icon_1']) ? $client_settings['shop_intro_icon_1'] : '';
    $shop_intro_icon_2 = isset($client_settings['shop_intro_icon_2']) ? $client_settings['shop_intro_icon_2'] : '';
    $shop_intro_icon_3 = isset($client_settings['shop_intro_icon_3']) ? $client_settings['shop_intro_icon_3'] : '';
    $shop_intro_icon_4 = isset($client_settings['shop_intro_icon_4']) ? $client_settings['shop_intro_icon_4'] : '';
    $shop_intro_icon_link_1 = isset($client_settings['shop_intro_icon_link_1']) ? $client_settings['shop_intro_icon_link_1'] : '';
    $shop_intro_icon_link_2 = isset($client_settings['shop_intro_icon_link_2']) ? $client_settings['shop_intro_icon_link_2'] : '';
    $shop_intro_icon_link_3 = isset($client_settings['shop_intro_icon_link_3']) ? $client_settings['shop_intro_icon_link_3'] : '';
    $shop_intro_icon_link_4 = isset($client_settings['shop_intro_icon_link_4']) ? $client_settings['shop_intro_icon_link_4'] : '';
    $shop_intro_icon_is_cube = (isset($client_settings['shop_intro_icon_is_cube']) && $client_settings['shop_intro_icon_is_cube'] == 1) ? $client_settings['shop_intro_icon_is_cube'] : 0;

    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

    // Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Cover'))

@section('content')

    <section class="logo_sec">
        <div class="row">
            <div class="col-md-12">
                <div class="add_logo_sec">
                    <div class="add_logo_sec_header d-flex align-items-center justify-content-end">
                        <div class="d-flex me-2">
                            <h2>{{ __('Cover')}}</h2>
                            <label class="switch ms-2">
                                <input type="checkbox" id="intro_icon_status" name="intro_icon_status" {{ ($intro_status == 1) ? 'checked' : '' }}>
                                <span class="slider round">
                                    <i class="fa-solid fa-circle-check check_icon"></i>
                                    <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                </span>
                            </label>
                        </div>
                        @if(isset($package_permissions['cover_cube']) && !empty($package_permissions['cover_cube']) && $package_permissions['cover_cube'] == 1)

                        <div class="d-flex">
                            <h2>{{ __('Cube') }}</h2>
                            <label class="switch ms-2">
                                <input type="checkbox" id="image_type" name="image_type" {{ ($shop_intro_icon_is_cube == 1) ? 'checked' : '' }}>
                                <span class="slider round">
                                    <i class="fa-solid fa-circle-check check_icon"></i>
                                    <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                </span>
                            </label>
                        </div>

                        @endif

                    </div>
                    <div class="add_logo_sec_body">
                        <form id="introIconForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                            @csrf
                            <code> Intro will appear before menu for the specified duration! Image/Video File Size < 2MB, Recommended Dimensions: 500 X 900px.</code>
                            <div class="row mt-3 cube-images" style="display: {{ ($shop_intro_icon_is_cube == 0) ? 'none' : 'flex' }}">
                                <div class="col-md-3 text-center intro-ic-1">
                                    <label for="shop_intro_icon_1" class="position-relative" style="cursor: pointer;">
                                        @if(!empty($shop_intro_icon_1) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_1))
                                            @php
                                                $file_1_ext = pathinfo($shop_intro_icon_1, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($file_1_ext == 'mp4' || $file_1_ext == 'mov')
                                                <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_1) }}" width="200px" autoplay muted loop>
                                                </video>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_1') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @else
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_1) }}" width="200px"/>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_1') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @endif
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                        <div class="intro_loader_1 logo-loader" style="display: none;">
                                            <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                        </div>
                                    </label>
                                    <input type="file" id="shop_intro_icon_1" name="shop_intro_icon_1" style="display: none;" class="shop_intro_icon" intro="1" />
                                    <div class="form-group mt-2">
                                        <label class="form-label">{{ __('Image 1 Link')}}</label>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="shop_intro_icon_link_1" id="shop_intro_icon_link_1" value="{{ $shop_intro_icon_link_1 }}">
                                            <span class="input-group-text text-success" style="cursor:pointer" onClick="saveIntroLink('shop_intro_icon_link_1')"><i class="fa-solid fa-check-circle"></i></span>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center intro-ic-2">
                                    <label for="shop_intro_icon_2" class="position-relative" style="cursor: pointer;">
                                        @if(!empty($shop_intro_icon_2) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_2))
                                            @php
                                                $file_2_ext = pathinfo($shop_intro_icon_2, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($file_2_ext == 'mp4' || $file_2_ext == 'mov')
                                                <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_2) }}" width="200px" autoplay muted loop>
                                                </video>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_2') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @else
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_2) }}" width="200px"/>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_2') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @endif
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                        <div class="intro_loader_2 logo-loader" style="display: none;">
                                            <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                        </div>
                                    </label>
                                    <input type="file" id="shop_intro_icon_2" name="shop_intro_icon_2" style="display: none;" class="shop_intro_icon" intro="2" />
                                    <div class="form-group mt-2">
                                        <label class="form-label">{{ __('Image 2 Link')}}</label>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="shop_intro_icon_link_2" id="shop_intro_icon_link_2" value="{{ $shop_intro_icon_link_2 }}">
                                            <span class="input-group-text text-success" style="cursor:pointer" onClick="saveIntroLink('shop_intro_icon_link_2')"><i class="fa-solid fa-check-circle"></i></span>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center intro-ic-3">
                                    <label for="shop_intro_icon_3" class="position-relative" style="cursor: pointer;">
                                        @if(!empty($shop_intro_icon_3) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_3))
                                            @php
                                                $file_3_ext = pathinfo($shop_intro_icon_3, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($file_3_ext == 'mp4' || $file_3_ext == 'mov')
                                                <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_3) }}" width="200px" autoplay muted loop>
                                                </video>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_3') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @else
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_3) }}" width="200px"/>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_3') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @endif
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                        <div class="intro_loader_3 logo-loader" style="display: none;">
                                            <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                        </div>
                                    </label>
                                    <input type="file" id="shop_intro_icon_3" name="shop_intro_icon_3" style="display: none;" class="shop_intro_icon" intro="3" />
                                    <div class="form-group mt-2">
                                        <label class="form-label">{{ __('Image 3 Link')}}</label>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="shop_intro_icon_link_3" id="shop_intro_icon_link_3" value="{{ $shop_intro_icon_link_3 }}">
                                            <span class="input-group-text text-success" style="cursor:pointer" onClick="saveIntroLink('shop_intro_icon_link_3')"><i class="fa-solid fa-check-circle"></i></span>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center intro-ic-4">
                                    <label for="shop_intro_icon_4" class="position-relative" style="cursor: pointer;">
                                        @if(!empty($shop_intro_icon_4) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_4))
                                            @php
                                                $file_4_ext = pathinfo($shop_intro_icon_4, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($file_4_ext == 'mp4' || $file_4_ext == 'mov')
                                                <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_4) }}" width="200px" autoplay muted loop>
                                                </video>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_4') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @else
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon_4) }}" width="200px"/>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon_4') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @endif
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                        <div class="intro_loader_4 logo-loader" style="display: none;">
                                            <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                        </div>
                                    </label>
                                    <input type="file" id="shop_intro_icon_4" name="shop_intro_icon_4" style="display: none;" class="shop_intro_icon" intro="4" />
                                    <div class="form-group mt-2">
                                        <label class="form-label">{{ __('Image 4 Link')}}</label>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="shop_intro_icon_link_4" id="shop_intro_icon_link_4" value="{{ $shop_intro_icon_link_4 }}">
                                            <span class="input-group-text text-success" style="cursor:pointer" onClick="saveIntroLink('shop_intro_icon_link_4')"><i class="fa-solid fa-check-circle"></i></span>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add_logo_sec_body_inr mt-3 single-image" style="display: {{ ($shop_intro_icon_is_cube == 0) ? 'flex' : 'none' }}">
                                <div>
                                    <label for="shop_intro_icon_0" class="position-relative" style="cursor: pointer;">
                                        @if(!empty($shop_intro_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon))
                                            @php
                                                $file_ext = pathinfo($shop_intro_icon, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($file_ext == 'mp4' || $file_ext == 'mov')
                                                <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon) }}" width="200px" autoplay muted loop>
                                                </video>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @else
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon) }}" width="200px"/>
                                                <a href="{{ route('design.cover.delete', 'shop_intro_icon') }}" class="btn btn-sm btn-danger" style="position: absolute; top: 0; right: -35px;"><i class="bi bi-trash"></i></a>
                                            @endif
                                        @else
                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                        @endif
                                        <div class="intro_loader_0 logo-loader" style="display: none;">
                                            <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                        </div>
                                    </label>
                                    <input type="file" id="shop_intro_icon_0" name="shop_intro_icon" style="display: none;" class="shop_intro_icon" intro="0" />
                                    <div class="form-group mt-2">
                                        <label class="form-label">{{ __('Intro duration in seconds')}}</label>
                                        <input type="number" class="form-control" name="intro_icon_duration" id="intro_icon_duration" value="{{ $intro_duration }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        // Change Intro Status
        $('#intro_icon_status').on('change',function()
        {
            // Clear all Toastr Messages
            toastr.clear();

            var check_status = $(this).is(':checked');
            if(check_status == true)
            {
                var status = 1;
            }
            else
            {
                var status = 0;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('design.intro.status') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "status" : status,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                }
            });

        });



        $('#image_type').on('change', function(){
            var check_status = $(this).is(':checked');
            if(check_status == true){
                $('.cube-images').show();
                $('.single-image').hide();
                var status = 1;
            }else{
                $('.cube-images').hide();
                $('.single-image').show();
                var status = 0;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('design.intro.cube') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "status" : status,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1){
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }else{
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                }
            });
        });


        // Save Intro Link
        function saveIntroLink(key){
            var link = $('#'+key).val();

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('design.intro.link') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "link" : link,
                    "key" : key,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1){
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                }
            });
        }


        // Change Intro Icon Duration
        $('#intro_icon_duration').on('change',function()
        {
            var duration = $(this).val();

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('design.intro.duration') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "duration" : duration,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                }
            });

        });


        // Upload Intro Icon
        $(".shop_intro_icon").on('change',function()
        {
            var myFormData = new FormData(document.getElementById("introIconForm"));
            var intro_id = $(this).attr('intro');

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('design.intro.icon') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                beforeSend: function(){
                    $(".intro_loader_"+intro_id).show();
                },
                processData: false,
                success: function (response)
                {
                    if(response.success == 1){
                        toastr.success(response.message);
                        $(".intro_loader_"+intro_id).hide();
                        setTimeout(() => {
                            location.reload();
                        }, 1300);
                    }else{
                        $('#introIconForm').trigger('reset');
                        toastr.error(response.message);
                    }
                },
                error: function(response){
                    // All Validation Errors
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';
                    if (validationErrors != ''){
                        // Image Error
                        var introIcon = (validationErrors.shop_intro_icon) ? validationErrors.shop_intro_icon : '';
                        var introIcon1 = (validationErrors.shop_intro_icon_1) ? validationErrors.shop_intro_icon_1 : '';
                        var introIcon1 = (validationErrors.shop_intro_icon_1) ? validationErrors.shop_intro_icon_1 : '';
                        var introIcon2 = (validationErrors.shop_intro_icon_2) ? validationErrors.shop_intro_icon_2 : '';
                        var introIcon3 = (validationErrors.shop_intro_icon_3) ? validationErrors.shop_intro_icon_3 : '';
                        var introIcon4 = (validationErrors.shop_intro_icon_4) ? validationErrors.shop_intro_icon_4 : '';

                        $(".intro_loader_"+intro_id).hide();
                        $("#shop_intro_icon_"+intro_id).val('');
                        if (introIcon != '') {
                            toastr.error(introIcon);
                        }
                        if (introIcon1 != '') {
                            toastr.error(introIcon1);
                        }
                        if (introIcon2 != '') {
                            toastr.error(introIcon2);
                        }
                        if (introIcon3 != '') {
                            toastr.error(introIcon3);
                        }
                        if (introIcon4 != '') {
                            toastr.error(introIcon4);
                        }
                    }
                }
            });

        });

    </script>

@endsection
