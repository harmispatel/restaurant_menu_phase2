@extends('client.layouts.client-layout')

@section('title', __('Item Reviews'))

@section('content')

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Item Reviews')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('Item Reviews')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <section class="section dashboard">
        <div class="row">

            {{-- Reviews Card --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="ingredientsTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Id')}}</th>
                                        <th>{{ __('Category')}}</th>
                                        <th>{{ __('Item')}}</th>
                                        <th style="width: 18%">{{ __('Rating')}}</th>
                                        <th style="width: 30%">{{ __('Comment')}}</th>
                                        <th style="width: 15%">{{ __('Time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($item_reviews as $review)
                                        <tr>
                                            <td>{{ $review->id }}</td>
                                            <td>{{ (isset($review->item->category['en_name'])) ? $review->item->category['en_name'] : '' }}</td>
                                            <td>{{ (isset($review->item['en_name'])) ? $review->item['en_name'] : '' }}</td>
                                            <td>
                                                <div class="rated">
                                                    @for($i=1; $i <= $review->rating; $i++)
                                                        <label class="star-rating-complete" title="text">{{$i}} stars</label>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>{{ $review->comment }}</td>
                                            <td>{{ $review->created_at->diffForHumans(); }}</td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6">{{ __('Reviews Not Found!')}}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
    </script>
@endsection
