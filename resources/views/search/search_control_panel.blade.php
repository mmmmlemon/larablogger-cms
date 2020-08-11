@extends('layouts.app')

@section('content')

<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
            @if($type == "post")
            <li><a href="/control/posts">Posts</a></li>
            <li class="is-active"><a href="#" aria-current="page">
                Search post
            </a></li>
            @endif
            @if($type == "comment")
            <li><a href="/control/comments">Comments</a></li>
            <li class="is-active"><a href="#" aria-current="page">
                Search comment
            </a></li>
            @endif
        </ul>
    </nav>
    @if($type == "post")
        <h1 class="title">Search post</h1>
    @elseif($type == "comment")
        <h1 class="title">Search comment</h1>
    @endif
    <div class="columns">
        <div class="" style="width:100%; margin-left:2pt; margin-top:4pt; margin-bottom:0;">
          <form action="/full_search" method="GET">
            <div class="field has-addons">
                <div class="control has-icons-left has-icons-right"  style="width:90%; margin-left:5px; margin-bottom: 5px;" id="search_bar_div">
                  <input type="text" name="type" value="post" class="invisible">
                  @if($type == "post")
                    <input type="text" name="type" value="post" class="invisible">
                    <input type="text" name="is_control_panel" value="true" class="invisible">
                  @elseif($type == "comment")
                    <input type="text" name="type" value="" class="invisible">
                  @endif
        
                  <input class="input" type="text" placeholder="Search" id="search_bar" name="search_value" value="{{$val ?? '' }}">
                  <span class="icon is-small is-left">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
                <div class="control">
                  <button class="button is-link">
                    Search
                  </button>
                </div>
              </div>
            </form>
        </div>
    </div>
  
</div>
<div class="white-bg search_results" id="search_results">

</div>
<div class="container">
    <div class="white-bg">
    <h1 class="subtitle">Search results: {{count($results)}}</h1>
    @if($type == "post")
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
                <p><a href="/category/${el.category}">{{$result->category}}</a> | {{$result->date}}</p>

                <div style="margin-top:15px;">
                    @if($result->pinned == 0)                  
                        <form action="/control/pin_post" method="post" style="display:inline;">
                            @csrf
                            <input type="text" name="id" value="{{$result->id}}" class="invisible">
                            <button class="button is-success" data-tooltip="Pin this post"><i class="fas fa-thumbtack"></i></button>
                        </form>
                    @else
        
                        <form action="/control/pin_post" method="post" style="display:inline;">
                            @csrf
                            <input type="text" name="id" value="{{$result->id}}" class="invisible">
                            <button class="button is-warning" data-tooltip="Unpin this post"><i class="fas fa-thumbtack"></i></button>
                        </form>
                    @endif
            
        
                    @if($result->visibility == 1)
                
                        <form action="/control/post_status/{{$result->id}}/0" method="post" style="display:inline;">
                            @csrf
                            <button class="button is-warning" data-tooltip="Hide this post"><i class="fas fa-eye-slash"></i></button>
                        </form>
                    @else

                        <form action="/control/post_status/{{$result->id}}/1" method="post" style="display:inline;">
                            @csrf
                            <button class="button is-primary" data-tooltip="Show this post"><i class="fas fa-eye"></i></button>
                        </form>
                    @endif

                    <a href="/post/{{$result->id}}/edit" class="button is-info">
                        <span class="icon is-small" data-tooltip="Edit this post">
                            <i class="fas fa-edit"></i>
                        </span>
                    </a>

                    <button class="button is-danger showModalDelete" 
                        data-tooltip="Delete this post" data-title="{{$result->post_title}}" data-id="{{$result->id}}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
      
            
            </div>
            @endforeach
        @endif
    @elseif($type == "comment")
            <h1>damn boi</h1>
    @endif
    </div>    
</div>

@endsection

@section('modals')
@if($type == "post")
    <div class="modal modalDelete">
        <div class="modal-background"></div>
        <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">You sure?</p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <p>Are you sure you want to delete this post?</p>
            <b id="modal_post_title"></b>
            <p>This action will also delete all media attached to this post.</p>
            <p class="has-text-danger">This action cannot be undone.</p>
        </section>
        <footer class="modal-card-foot">
                <form id="modal_form" action="/control/delete_post" method="post" style="display:inline;">
                    @method('DELETE')
                    @csrf
                    <input type="text" class="invisible" id="modal_form_input" name="modal_form_input">
                </form>
                <button class="button is-danger" id="submit_modal">Delete</button>
                <button class="button cancel">Cancel</button>
        </footer>
        </div>
    </div>
@endif
@endsection


@push('scripts')
<script src="{{ asset('js/custom/search.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/posts.js') }}"></script>
@endpush