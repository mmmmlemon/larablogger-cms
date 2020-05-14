@extends('layouts.app')
@section('content')


<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li class="is-active"><a href="#" aria-current="page">Posts</a></li>
        </ul>
      </nav>
    <div class="column">
        <a href="{{url()->previous()}}" class="button is-link">
            <span class="icon">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span>
             Back
            </span>
        </a>
        <a href="/control/create_post" class="button is-link">
            <span class="icon">
                <i class="fas fa-pen"></i>
            </span>
            <span>
            Add post
            </span>
        </a>
        <a href="/control/media" class="button is-link" data-tooltip="Add/edit categories">
            <span class="icon">
                <i class="fas fa-video"></i>
            </span>
            <span>Media browser</span>
        </a>
    </div>
    <div class="is-divider"></div>
    <div class="columns">
      
        <div class="column">
         
      
            <div class="columns">
                {{-- если есть какие-то посты то, выводим их --}}
            @if($posts->count() > 0)
            <table class="table is-fullwidth is-hoverable">
                <thead>
                    <th>Title</th>
                    <th>Visibility</th>
                    <th>Category</th>
                    <th><a @if($page=="normal")href="/control/posts/date"@else href="/control/posts"@endif>Date</a>  
                        @if($page=="normal")<i class="fas fa-sort-down"></i></th>@else <i class="fas fa-sort-up"></i></th>@endif
                        
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                    <td><b><a href="/post/{{$post->id}}">{{$post->post_title}}</a></b></td>
                    <td>
                        @if($post->visibility == 1)
                            <span class="icon is-small" data-tooltip="Post is visible">
                                <i class="fas fa-check"></i>
                            </span>
                        @else
                            <span class="icon is-small" data-tooltip="Post is hidden">
                                <i class="fas fa-times"></i>
                            </span>
                        @endif
                    </td>
                <td>{{App\Category::find($post->category_id)->category_name}}</td>
                    <td>{{date('d.m.Y', strtotime($post->date))}}</td>
                    <td>
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
    <div>
        {{ $posts->links('pagination.default') }}
    </div>
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