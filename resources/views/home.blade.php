@extends('layouts.app')
@section('content')
    {{-- grid/list view --}}
    {{-- search --}}
    @if($isMobile == false)
    <div class="container white-bg">
        <div class="columns">
            <div class="buttons has-addons" style="width:7rem; margin-left:2pt; margin-top:4pt; margin-bottom:0;">
                <button class="button @if($view_type == 'list') view_button_active ignore @else view_button @endif" id="list_view">
                    <span>
                        <i class="fas fa-bars"></i>
                    </span>
                </button>
                <button class="button @if($view_type == 'grid') view_button_active ignore @else view_button @endif" id="grid_view">
                    <span>
                        <i class="fas fa-grip-horizontal"></i>
                    </span>
                </button>
            </div>
            <div class="" style="width:100%; margin-left:2pt; margin-top:4pt; margin-bottom:0;">
                <div class="field has-addons">
                    <div class="control has-icons-left"  style="width:60%;">
                      <input class="input" type="text" placeholder="Search">
                      <span class="icon is-small is-left">
                        <i class="fas fa-search"></i>
                      </span>
                    </div>
                    <div class="control">
                      <a class="button is-info">
                        Search
                      </a>
                    </div>
                  </div>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        @if($tag_name != null)
            <div class="white-bg has-text-centered">
                <h1 class="title post_title">Posts by tag '{{$tag_name}}'</h1>
            </div>
        @endif
        @if(count($posts)>0)
            @if($view_type == 'grid' && $isMobile == false)
                @yield('grid_view', View::make('posts/grid_view', compact('posts')))
            @elseif($view_type == 'list' && $isMobile == false)
                @yield('list_view', View::make('posts/list_view', compact('posts')))
            @elseif($isMobile == true)
                @yield('list_view', View::make('posts/list_view', compact('posts')))
            @else
                
            @endif
        @else
            <div class="white-bg has-text-centered">
                <h1 class="title">Nothing to see here yet</h1>
                <i class="fas fa-hand-peace"></i>
                <h1 class="subtitle">Come again later</h1>
            </div>
        @endif
        <div>
            {{ $posts->links('pagination.default') }}
        </div>
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