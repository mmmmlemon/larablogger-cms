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
            @if($type == "media")
            <li><a href="/control/posts">Posts</a></li>
            <li><a href="/control/media">Media browser</a></li>
            <li class="is-active"><a href="#" aria-current="page">
                Search media


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
                    <input type="text" name="type" value="comment" class="invisible">
                  @elseif($type == "media")
                    <input type="text" name="type" value="media" class="invisible">
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
    @if(count($results) <= 0)
        @if(count($results) == 0)
        <div class="has-text-centered">
            @if($type == "post")
                <h1 class="title">No posts found</h1>
            @elseif($type == "comment")
                <h1 class="title">No comments found</h1>
            @elseif($type == "media")
                <h1 class="title">No media files found</h1>
            @endif
            <i class="fas fa-search"></i>
            <h1 class="subtitle">Try to search something different</h1>
        </div>
        @endif
    @else
            @if($type == "post")

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
        @elseif($type == "comment")
            @foreach($results as $result)
                <div class='white-bg search_full_results_block'>
                    @if($result->real_username != null)
                        <h1 class="subtitle">{{$result->real_username}}
                    @else
                        <h1 class="subtitle">{{$result->username}}
                    @endif
                    | <a style="font-size: 13pt;" href="/post/{{$result->post_id}}#comment_anchor_{{$result->id}}">{{$result->post_title}}</a></h1>
                    <div class="post_content">{!!$result->comment_content!!}</div>
                    <p>{{date("d.m.Y", strtotime($result->date))}}</p>
                    <div>
                        @if($result->visibility == 1)
                        <form action="/post/change_comment_status" method="post" style="display:inline;">
                        @csrf
                        <input type="text" name="comment_id" value="{{$result->id}}" class="invisible">
                        <input type="text" class="invisible "name="action" value="hide">
                        <button class="button is-warning" data-tooltip="Hide this comment"><i class="fas fa-ban"></i></button>
                        </form>
                    @else
                    <form action="/post/change_comment_status" method="post" style="display:inline;">
                        @csrf
                        <input type="text" name="comment_id" value="{{$result->id}}" class="invisible">
                        <input type="text" class="invisible "name="action" value="show">
                        <button class="button is-success" data-tooltip="Show this comment"><i class="fas fa-check"></i></button>
                    </form>
                    @endif
                    <button class="button is-danger showModalDelete" data-tooltip="Delete this comment" data-id="{{$result->id}}"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            @endforeach
        @elseif($type == "media")
            @foreach($results as $result)
            <div class='white-bg search_full_results_block'>
                <div class="columns">
                    <div class="column">
                        @if($result->media_type == "image")
                            <img src="{{asset("/storage/")."/".$result->media_url}}" alt="">
                        @elseif($result->media_type == "video")
                            <video style="" controls="controls" id="player" @if($result->thumbnail_url != null) preload="none" poster = "{{asset('/storage/')."/".$result->thumbnail_url}} @endif">
                                <source src="{{url('/')}}/storage/{{$result->media_url}}" id="content-video">
                            </video>
                        @endif
                    </div>
                    <div class="column is-8">
                        <h1 style="font-size: 15pt;"><a href="/control/media/{{$result->id}}">{{$result->display_name}}</a></h1>
                        <h4>{{$result->actual_name}}</h4>
                        <h4><a href="/post/{{$result->post_id}}">{{$result->post_title}}</a></h4>
                        <div style="margin-top: 10px;">
                            @if($result->media_type == "image")
                                <button class="button is-success preview" data-tooltip="Preview"
                                    data-type="{{$result->media_type}}" data-url="{{asset("storage/".$result->media_url)}}">
                                    <i class="fas fa-play"></i>
                                </button>
                            @endif
                            <a href="/control/media/{{$result->id}}" class="button is-info" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="deleteFile button is-danger" data-id="{{$result->id}}" data-tooltip="Delete this file">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>  
                    </div>
                </div>
                
            </div>
        @endforeach
        @endif
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
@elseif($type == "comment")
    <div class="modal modalDelete">
        <div class="modal-background"></div>
        <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">You sure?</p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <p>Are you sure you want to delete this comment?</p>
            <b id="modal_post_title"></b>
            <p class="has-text-danger">This action cannot be undone.</p>
        </section>
        <footer class="modal-card-foot">
            <form id="modal_form" action="/post/change_comment_status" method="post" style="display:inline;">
                    @csrf
                    <input type="text" class="invisible" id="modal_form_input" name="comment_id">
                    <input type="text" class="invisible "name="action" value="delete">
            </form>
            <button class="button is-danger" id="submit_modal">Delete</button>
            <button class="button cancel">Cancel</button>
        </footer>
    </div>
@elseif($type == "media")

    <div class="modal" id="preview-modal">
        <div class="modal-background"></div>
        <div class="modal-content column is-two-thirds-desktop is-12-mobile">
            <p class="image has-text-centered">
            <div class="has-text-centered">
                <img id="content-in-modal" width="90%" class="centered_image" src="" alt="" style="padding:0px;">
                <div id="player_div" style="display: none;">
                    <video controls="controls" id="player">
                        <source src="" id="content-video">
                    </video>
                </div>
            </div>
            </p>
        </div>
        <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
    </div>

    <div class="modal modalDelete">
        <div class="modal-background"></div>
        <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">You sure?</p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <p>Are you sure you want to delete this file?</p>
            <b id="modal_post_title"></b>
            <p>The file will be removed both from post and physically.</p>
            <p class="has-text-danger">This action cannot be undone.</p>
        </section>
        <footer class="modal-card-foot">
                <form id="modal_form" action="/control/media/delete_media" method="post" style="display:inline;">
                    @csrf
                    <input type="text" name="id" value="pee" id="modal_input" class="invisible">
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

    @if($type == "post")
        <script src="{{ asset('js/custom/control_panel/posts.js') }}"></script>
    @elseif($type == "media")
        <script src="{{ asset('js/plyr.js') }}"></script>
        <script src="{{ asset('js/custom/control_panel/media.js') }}"></script>
    @endif

@endpush