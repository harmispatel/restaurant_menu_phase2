@extends('client.layouts.client-layout')

@section('title', __('Contact'))

@section('content')

    <section class="contact_main">
        <div class="sec_title">
            <h2>{{ __('Contact US')}}</h2>
        </div>
            <form action="{{route('contact.send')}}" class="form" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('Title')}}</label>
                            <input class="form-control" type="text" name="title">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Message')}}</label>
                            <textarea name="message" id="" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Send')}}</button>
                </div>
        </form>
    </section>

@endsection

{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif

    </script>

@endsection


