@extends('layouts.app')

@section('content')
<div class="container">
  @if($tag_name != null)
  <div class="white-bg has-text-centered">
  <h1 class="title">Posts by tag '{{$tag_name}}'</h1>
  </div>
  @endif
  @if(count($posts) > 0)
  <div class="">
      @foreach($posts as $post)
        @yield('post', View::make('post_template', compact('post')))
      @endforeach
  </div>
  <div>
    {{ $posts->links('pagination.default') }}
  </div>
  @else
  <div class="white-bg has-text-centered">
    <h1 class="title">Nothing to see here yet</h1>
    <i class="fas fa-hand-peace"></i>
    <h1 class="subtitle">Come again later</h1>
  </div>
  @endif
</div>
@endsection

@section('modals')
<div class="modal" id="img-modal">
  <div class="modal-background"></div>
  <div class="modal-content column is-two-thirds-desktop is-12-mobile">
    <p class="image has-text-centered">
      <div class="has-text-centered">
        <img id="img-in-modal" width="90%" src="" alt="">
        <br>
        <a id="link-in-modal" target="_blank" href="">Download</a>
      </div>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/custom/home_page.js') }}"></script>
@endpush