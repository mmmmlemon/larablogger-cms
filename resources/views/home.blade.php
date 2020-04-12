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

@section('modals')
<div class="modal" id="img-modal">
  <div class="modal-background"></div>
  <div class="modal-content column" style="width: 50%;">
    <p class="image has-text-centered">
      <img id="img-in-modal" width="90%" src="" alt="">
      <a id="link-in-modal" target="_blank" href="">Download</a>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/home_page.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script>
   const players = Plyr.setup('.video-player');

     //вызвать модальное окно с картинкой
     $(".imagee").click(function() {
        $("#img-modal").addClass("is-active fade-in"); 
        $("#img-in-modal").attr("src", $(this).attr("src"));
        $("#link-in-modal").attr("href", $(this).attr("src"));
      });
      
    $("#modal-close").click(function() {
        $("#img-modal").removeClass("is-active");
    });
</script>
@endpush