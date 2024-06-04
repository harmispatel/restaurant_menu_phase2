@php
    $client_settings = getClientSettings();

    $logo = isset($client_settings['shop_view_header_logo']) ? $client_settings['shop_view_header_logo'] : '';
    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
    $loader = isset($client_settings['shop_loader']) ? $client_settings['shop_loader'] : '';

    $adminSetting = getAdminSettings();

    // Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);


@endphp

@extends('client.layouts.client-layout')

@section('title', __('General Information'))

@section('content')

    <section class="general_info_main">
        <div class="sec_title">
            <h2>{{ __('General Information')}}</h2>
        </div>
        <div class="site_info">
            <form id="generalInfo" action="{{ route('design.generalInfoUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="business_name">{{ __('Business Name')}}</label>
                            <div class="input-group">
                                <input type="text" class="form-control {{ ($errors->has('business_name')) ? 'is-invalid' : '' }}" name="business_name" id="business_name" value="{{ (isset($client_settings['business_name']) && !empty($client_settings['business_name'])) ? $client_settings['business_name'] : '' }}">
                                <span class="input-group-text">#{{ isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '' }}</span>
                                @if($errors->has('business_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('business_name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="default_currency">{{ __('Currency')}}</label>
                            <select  class="form-select {{ ($errors->has('default_currency')) ? 'is-invalid' : '' }}" name="default_currency" id="default_currency">
                                <option value="">Choose Currency</option>
                                <option value="EUR" {{ (isset($client_settings['default_currency']) && ($client_settings['default_currency'] == 'EUR')) ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ (isset($client_settings['default_currency']) && ($client_settings['default_currency'] == 'USD')) ? 'selected' : '' }}>USD</option>
                                <option value="GBP" {{ (isset($client_settings['default_currency']) && ($client_settings['default_currency'] == 'GBP')) ? 'selected' : '' }}>GBP</option>
                            </select>
                            @if($errors->has('default_currency'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_currency') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="business_telephone">{{ __('Telephone')}}</label>
                            <div class="position-relative">
                                <input type="text" class="form-control icon-input" name="business_telephone" id="business_telephone" value="{{ (isset($client_settings['business_telephone']) && !empty($client_settings['business_telephone'])) ? $client_settings['business_telephone'] : '' }}">
                                <i class="fa-solid fa-phone input-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="business_subtitle">{{ __('Sub Title')}}</label>
                                <!-- <input type="text" class="form-control" name="business_subtitle" id="business_subtitle" value="{{ (isset($client_settings['business_subtitle']) && !empty($client_settings['business_subtitle'])) ? $client_settings['business_subtitle'] : '' }}"> -->
                                <div class="form-switch">
                                <input class="form-check-input" type="checkbox" name="is_sub_title" role="switch" id="is_sub_title" value="1"  {{ (isset($client_settings['is_sub_title']) && ($client_settings['is_sub_title'] == 1)) ? 'checked' : '' }} >
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="form-label" for="shop_start_time">{{ __('Working Hours') }}</label>
                            <div class="row">
                            <div class="col-6"><input type="time" class="form-control" name="shop_start_time" id="shop_start_time" value="{{ (isset($client_settings['shop_start_time']) && !empty($client_settings['shop_start_time'])) ? $client_settings['shop_start_time'] : '' }}"></div>
                            <div class="col-6"><input type="time" class="form-control" name="shop_end_time" id="shop_end_time" value="{{ (isset($client_settings['shop_end_time']) && !empty($client_settings['shop_end_time'])) ? $client_settings['shop_end_time'] : '' }}"></div>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="form-label" for="happy_start_time">{{ __('Happy time') }}</label>
                            <div class="row">
                            <div class="col-6"><input type="time" class="form-control" name="happy_start_time" id="happy_start_time" value="{{ (isset($client_settings['happy_start_time']) && !empty($client_settings['happy_start_time'])) ? $client_settings['happy_start_time'] : '' }}"></div>
                            <div class="col-6"><input type="time" class="form-control" name="happy_end_time" id="happy_end_time" value="{{ (isset($client_settings['happy_end_time']) && !empty($client_settings['happy_end_time'])) ? $client_settings['happy_end_time'] : '' }}"></div>
                            </div>

                        </div>
                    </div> -->
                </div>

                <div class="social_media_part">
                    <div class="social_media_title">
                        <h2>{{ __('Social Plateforms')}}</h2>
                        <p>{{ __('Fill in your digital assets and they will appear at the bottom of your menu. Boost your online community!')}}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="instagram_link">{{ __('Instagram')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" id="instagram_link" name="instagram_link" value="{{ (isset($client_settings['instagram_link']) && !empty($client_settings['instagram_link'])) ? $client_settings['instagram_link'] : '' }}">
                                    <i class="fa-brands fa-instagram input-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="twitter_link">{{ __('Twitter')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" name="twitter_link" id="twitter_link" value="{{ (isset($client_settings['twitter_link']) && !empty($client_settings['twitter_link'])) ? $client_settings['twitter_link'] : '' }}">
                                    <i class="fa-brands fa-twitter input-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="facebook_link">{{ __('Facebook')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" name="facebook_link" id="facebook_link" value="{{ (isset($client_settings['facebook_link']) && !empty($client_settings['facebook_link'])) ? $client_settings['facebook_link'] : '' }}">
                                    <i class="fa-brands fa-facebook-f input-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="foursquare_link">{{ __('Foursquare')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" name="foursquare_link" id="foursquare_link" value="{{ (isset($client_settings['foursquare_link']) && !empty($client_settings['foursquare_link'])) ? $client_settings['foursquare_link'] : '' }}">
                                    <i class="fa-brands fa-foursquare input-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="tripadvisor_link">{{ __('Tripadvisor')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" name="tripadvisor_link" id="tripadvisor_link" value="{{ (isset($client_settings['tripadvisor_link']) && !empty($client_settings['tripadvisor_link'])) ? $client_settings['tripadvisor_link'] : '' }}">
                                    <i class="fa-solid fa-mask input-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="pinterest_link">{{ __('Pinterest')}}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control icon-input" name="pinterest_link" id="pinterest_link" value="{{ (isset($client_settings['pinterest_link']) && !empty($client_settings['pinterest_link'])) ? $client_settings['pinterest_link'] : '' }}">
                                    <i class="fa-brands fa-pinterest input-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="map_url">{{ __('Map')}}</label>
                            <div class="position-relative">
                                <input type="text" class="form-control icon-input" name="map_url" id="map_url" value="{{ (isset($client_settings['map_url']) && !empty($client_settings['map_url'])) ? $client_settings['map_url'] : '' }}">
                                <i class="fa-solid fa-map input-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="website_url">{{ __('Website')}}</label>
                            <div class="position-relative">
                                <input type="text" class="form-control icon-input" name="website_url" id="website_url" value="{{ (isset($client_settings['website_url']) && !empty($client_settings['website_url'])) ? $client_settings['website_url'] : '' }}">
                                <i class="fa-solid fa-globe input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social_media_part">
                    <div class="socia_media_title">
                        <h2>{{ __('Logo')}}</h2>
                        <p>{{ __('Logo will appear on the top of your menu')}}</p>
                    </div>
                    <div class="add_logo_sec_body_inr position-relative">
                        <label for="shop_view_header_logo" class="position-relative" style="cursor: pointer;">
                            @if(!empty($logo) && file_exists('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo))
                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo) }}" width="200px"/>
                                <a href="{{ route('design.logo.delete') }}" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>
                            @else
                                <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                            @endif
                            <div class="logo-loader" style="display: none;">
                                <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                            </div>
                        </label>
                        <input type="file" id="shop_view_header_logo" name="shop_view_header_logo" style="display: none;" />
                    </div>
                </div>
                @if(isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                    <div class="social_media_part">
                        <div class="socia_media_title d-flex  justify-content-between">
                            <h2>{{ __('Loader')}}</h2>
                            <div class="form-switch">
                                <input class="form-check-input" type="checkbox" name="is_loader" role="switch" id="is_loader" value="1"  {{ (isset($client_settings['is_loader']) && ($client_settings['is_loader'] == 1)) ? 'checked' : '' }} >
                            </div>
                            {{-- <p>{{ __('Logo will appear on the top of your menu')}}</p> --}}
                        </div>
                        <div class="add_logo_sec_body_inr position-relative">
                            <label for="shop_loader" class="position-relative" style="cursor: pointer;">
                                @if(!empty($loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$loader))
                                    <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/loader/'.$loader) }}" width="200px"/>
                                    <a href="{{ route('design.loader.delete') }}" class="btn btn-sm btn-danger" style="position: absolute; top: -35px; right: 0px;"><i class="bi bi-trash"></i></a>
                                @else
                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" width="200px"/>
                                @endif
                                <div class="logo-loader" style="display: none;">
                                    <img src="{{ asset('public/client_images/loader/loader1.gif') }}">
                                </div>
                            </label>
                            <input type="file" id="shop_loader" name="shop_loader" style="display: none;" />
                            @if($errors->has('shop_loader'))
                            <div class="invalid-feedback">
                                {{ $errors->first('shop_loader') }}
                            </div>
                        @endif
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-3">
                        <button class="btn btn-success">{{ __('Update')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection


{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        // Toastr Settings
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            timeOut: 4000
        }

        // Success Message
        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

    </script>

@endsection

