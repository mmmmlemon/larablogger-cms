@extends('layouts.app')

@section('content')
<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li class="is-active"><a href="#" aria-current="page">Posts</a></li>
        </ul>
    </nav>
    @if(config('isMobile') != true)
        <div class="columns">
            <div class="column is-fullwidth">
                <div style="margin-top:10px; margin-bottom:20px;">
                    <a href="/control/create_post" class="button is-link">
                        <span class="icon">
                            <i class="fas fa-pen"></i>
                        </span>
                        <span>Add post</span>
                    </a>
                    <a href="/control/media" class="button is-link" data-tooltip="Add/edit categories">
                        <span class="icon">
                            <i class="fas fa-video"></i>
                        </span>
                        <span>Media browser</span>
                    </a>
    
                </div>
        
                <form action="/full_search" method="GET">
                    <div class="field has-addons">
                        <div class="control has-icons-left has-icons-right"  style="width:100%;" id="search_bar_div">
                          <input type="text" name="type" value="post" class="invisible">
                          <input type="text" name="is_control_panel" value="true" class="invisible">
                          <input class="input" type="text" placeholder="Search post" id="search_bar" name="search_value" value="{{$val ?? '' }}" data-type="post">
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
        <div class="white-bg search_results_posts" id="search_results">

        </div>
 
    @else
    <div class="columns has-text-centered">
        <div class="column is-12">
            <div class="buttons is-centered ">
                <a href="/control/create_post" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-pen"></i>
                    </span>
                    <span>Add post</span>
                </a>
                <a href="/control/media" class="button is-link" data-tooltip="Add/edit categories">
                    <span class="icon">
                        <i class="fas fa-video"></i>
                    </span>
                    <span>Media browser</span>
                </a>
            </div>
        </div>
        <div class="column">
            <form action="/full_search" method="GET">
                <div class="field has-addons">
                    <div class="control has-icons-left has-icons-right" id="search_bar_div">
                      <input type="text" name="type" value="post" class="invisible">
                      <input type="text" name="is_control_panel" value="true" class="invisible">
                      <input class="input" type="text" placeholder="Search post" id="search_bar" name="search_value" value="{{$val ?? '' }}" data-type="post">
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
    @endif

    <div class="is-divider"></div>

    <div class="columns">
        <div class="column">
            <div class="columns">
                @if($posts->count() > 0)
                    @if(config('isMobile') != true)
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <th>Title</th>
                                <th>Visibility</th>
                                <th>Category</th>
                                <th>
                                
                                    <a @if($page=="normal")href="/control/posts/date"@else href="/control/posts"@endif>Date</a>  
                                    @if($page=="normal")
                                        <i class="fas fa-sort-down"></i></th>
                                    @else 
                                        <i class="fas fa-sort-up"></i></th>
                                    @endif
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr>
                            
                                        <td><b><a href="/post/{{$post->id}}">{{$post->post_title}} </b> </a>  <p class="view_count_posts">{{$post->view_count}} views</p></td>
                                        <td>
                                
                                            @if($post->visibility == 1)
                                                <span class="icon is-small" data-tooltip="Post is visible">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            @else
                                                <span class="icon is-small" data-tooltip="Post is hidden">
                                                    <i class="fas fa-eye-slash"></i>
                                                </span>
                                            @endif
                                        </td>
                        
                                    <td><a href="/category/{{App\Category::find($post->category_id)->category_name}}">{{App\Category::find($post->category_id)->category_name}}</a></td>
                                
                                        <td>{{date('d.m.Y', strtotime($post->date))}}</td>
                                        <td>
                                
                                            @if($post->pinned == 0)
                                            
                                                <form action="/control/pin_post" method="post" style="display:inline;">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$post->id}}" class="invisible">
                                                    <button class="button is-success" data-tooltip="Pin this post"><i class="fas fa-thumbtack"></i></button>
                                                </form>
                                            @else
                                    
                                                <form action="/control/pin_post" method="post" style="display:inline;">
                                                    @csrf
                                                    <input type="text" name="id" value="{{$post->id}}" class="invisible">
                                                    <button class="button is-warning" data-tooltip="Unpin this post"><i class="fas fa-thumbtack"></i></button>
                                                </form>
                                            @endif
                                            
                                    
                                            @if($post->visibility == 1)
                                        
                                                <form action="/control/post_status/{{$post->id}}/0" method="post" style="display:inline;">
                                                    @csrf
                                                    <button class="button is-warning" data-tooltip="Hide this post"><i class="fas fa-eye-slash"></i></button>
                                                </form>
                                            @else
                                
                                                <form action="/control/post_status/{{$post->id}}/1" method="post" style="display:inline;">
                                                    @csrf
                                                    <button class="button is-primary" data-tooltip="Show this post"><i class="fas fa-eye"></i></button>
                                                </form>
                                            @endif
                            
                                                <a href="/post/{{$post->id}}/edit" class="button is-info">
                                                    <span class="icon is-small" data-tooltip="Edit this post">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                    
                                            <button class="button is-danger showModalDelete" 
                                                data-tooltip="Delete this post" data-title="{{$post->post_title}}" data-id="{{$post->id}}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>    
                                @endforeach
                            </tbody>  
                        </table>
                    @else
                        <a class="button is-small is-fullwidth" @if($page=="normal")href="/control/posts/date"@else href="/control/posts"@endif>
                            <span class="icon">
                            @if($page=="normal")
                                <i class="fas fa-chevron-down"></i>
                            @else 
                                <i class="fas fa-chevron-up"></i></th>
                            @endif
                            </span>
                            <span>Date</span>
                        </a>
                        <table class="table is-fullwidth is-hoverable ">
                            @foreach($posts as $post)
                                <tr class="">
                                    <td style="width:50px;">
                                    <p class="has-text-centered" style="font-size: 14pt;"><a href="/post/{{$post->id}}"><b>{{Str::limit($post->post_title,30,"...")}}</b></a> <p class="view_count_posts has-text-centered">{{$post->view_count}} views</p></p>
                                    <p class="has-text-centered" style="font-size: 10pt;">
                                        {{date('d.m.y',strtotime($post->created_at))}} |
                                        <a href="/category/{{App\Category::find($post->category_id)->category_name}}">{{App\Category::find($post->category_id)->category_name}}</a>
                                        |
                                        @if($post->visibility == 1)
                                            <i class="fas fa-eye"></i>
                                        @else
                                            <i class="fas fa-eye-slash"></i>
                                        @endif
                                    </p>
                                    
                                    <div class="buttons has-addons is-centered" style="margin-top:5px;">
                                        @if($post->pinned == 0)  
                                            <form action="/control/pin_post" method="post" style="display:inline;">
                                                <button class="button is-success" data-tooltip="Pin this post"><i class="fas fa-thumbtack"></i></button>
                                                @csrf
                                                <input type="text" name="id" value="{{$post->id}}" class="invisible">
                                            </form>
                                         @else
                                            <form action="/control/pin_post" method="post" style="display:inline;">
                                                <button class="button is-warning" data-tooltip="Unpin this post"><i class="fas fa-thumbtack"></i></button>
                                                @csrf
                                                <input type="text" name="id" value="{{$post->id}}" class="invisible">    
                                            </form>
                                        @endif
                                        
                                        
                                        @if($post->visibility == 1)
                                            <form action="/control/post_status/{{$post->id}}/0" method="post" style="display:inline;">
                                                @csrf
                                                <button class="button is-warning" data-tooltip="Hide this post"><i class="fas fa-eye-slash"></i></button>
                                            </form>
                                        @else
                                            <form action="/control/post_status/{{$post->id}}/1" method="post" style="display:inline;">
                                                @csrf
                                                <button class="button is-primary" style="border-radius
                                                
                                                :0px;" data-tooltip="Show this post"><i class="fas fa-eye"></i></button>
                                            </form>
                                        @endif

                                        <a href="/post/{{$post->id}}/edit" class="button is-info">
                                            <span class="icon is-small" data-tooltip="Edit this post">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </a>

                                        <button class="button is-danger showModalDelete" 
                                            data-tooltip="Delete this post" data-title="{{$post->post_title}}" data-id="{{$post->id}}">
                                            <i class="fas fa-trash"></i>
                                        </button>                                  
                                    </div>
                        
                                    </td>
                          
                                </tr>
                            @endforeach
                        </table>
                    @endif
                @else

                <div class="column has-text-centered">
                    <h1 class="title">No posts yet</h1>
                    <a href="/control/create_post" class="button is-link">
                        <span class="icon is-small">
                        <i class="fas fa-pen"></i>
                        </span>
                        <span>Create post</span>
                    </a>
                </div>

                @endif
            </div>
        </div>
    </div>

  
</div>
<div class="container">
    {{ $posts->links('pagination.default') }}
</div>
@endsection

@section('modals')
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
@endsection

@push('scripts')
<script src="{{ asset('js/custom/control_panel/posts.js') }}"></script>
@endpush