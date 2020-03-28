@extends('layouts.app')
@section('content')

<div class="container">
    <div class="container">
        <div class="">
          @foreach($posts as $post)
            @yield('post', View::make('post_template', compact('post')))
          @endforeach
        </div>
      </div>
      <div>
        {{ $posts->links('pagination.default') }}
    </div>
</div>

@endsection