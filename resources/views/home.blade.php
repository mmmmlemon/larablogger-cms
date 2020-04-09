@extends('layouts.app')

@section('content')
<div class="container">
  <div class="">
    @foreach($posts as $post)
      @yield('post', View::make('post_template', compact('post')))
    @endforeach
  </div>
  <div>
    {{ $posts->links('pagination.default') }}
</div>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/home_page.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script>
   const players = Plyr.setup('.video-player');
</script>
@endpush