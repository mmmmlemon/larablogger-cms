@extends('layouts.app')

@section('content')
@if(config('isMobile') == false)
@yield("search_and_view", View::make('search/search_and_view', compact('view_type','val')))
@endif
<div class="container">
    <div class="white-bg">
    <h1 class="subtitle">Search results: {{count($results)}}</h1>
        @if(count($results) == 0)
        <div class="has-text-centered">
            <h1 class="title">Nothing found</h1>
            <i class="fas fa-search"></i>
            <h1 class="subtitle">Try to search something different</h1>
        </div>
        @else
            @foreach($results as $result)
            <div class='white-bg search_full_results_block'>
                <h1 class="subtitle post_title"><a href="/post/{{$result->id}}">{{$result->post_title}}</a></h1>
                <div class="post_content">{!!$result->post_content!!}</div>
                <p><a href="/category/${el.category}">{{$result->category}}</a> | {{$result->date}}</p></div>
            @endforeach
        @endif
    </div>    
</div>

@endsection

@push('scripts')

@endpush