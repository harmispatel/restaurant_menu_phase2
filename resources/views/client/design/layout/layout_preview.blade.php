@extends('client.layouts.client-layout')

@section('title', __('Layout'))

@section('content')

    @php
    if ($activeLayout) {

        $id = $activeLayout->layout_id;
    }else{
        $id = '';
    }
    @endphp
    <section class="layout_section">
        <div class="sec_title">
            <h2>{{ __('Layout') }}</h2>
            <p> {{ __('Select a theme and preview your menu to check the result. Click on ‘Add theme’ and edit all available features.') }}
            </p>
        </div>
        <div class="row">
            @if (count($layouts) > 0)
                @foreach ($layouts as $layout)
                    <div class="col-md-6 col-lg-4">
                        <div class="item_box">
                            <div class="item_img add_category add_theme">
                                <img src="{{ asset('public/admin_uploads/def_layout_images/'.$layout->image) }}" class="w-100">
                            </div>
                            <div class="item_info">
                                <div class="item_name">
                                    <h3>{{ $layout->name }}</h3>
                                    <label class="switch">
                                        <input type="checkbox" name="is_default" id="is_default" {{  ($layout->id == $id) ? 'checked disabled' : ''  }} onchange="changeActiveLayout({{ $layout->id }})">
                                        <span class="slider round">
                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                        </span>
                                    </label>
                                </div>
                                <h2>{{ __('Layout') }}</h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif





        </div>
    </section>

@endsection

@section('page-js')

<script type="text/javascript">

    // Success Toastr Message
    @if (Session::has('success'))
                toastr.success('{{ Session::get('success') }}')
    @endif

    // Function for Change Active Layout
    function changeActiveLayout(layoutID)
    {

            $.ajax({
                type: "POST",
                url: "{{ route('layout.change') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "layout_id" : layoutID,
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

</script>

@endsection
