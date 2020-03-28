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
                            <a href="/control/post_status/{{$post->id}}/0" class="button is-warning">
                                <span class="icon is-small" data-tooltip="Hide this post">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </a>
                        @else
                        <a href="/control/post_status/{{$post->id}}/1" class="button is-primary">
                            <span class="icon is-small" data-tooltip="Show this post"> 
                                <i class="fas fa-eye"></i>
                            </span>
                        </a>
                        @endif
                        <a href="/post/{{$post->id}}/edit" class="button is-info">
                            <span class="icon is-small" data-tooltip="Edit this post">
                                <i class="fas fa-edit"></i>
                            </span>
                        </a>
                        <a href="/control/delete_post/{{$post->id}}" class="button is-danger">
                            <span class="icon is-small" data-tooltip="Delete this post">
                                <i class="fas fa-trash"></i>
                            </span>
                        </a>
                    </td>
                    </tr>
                    
                
                @endforeach
                </tbody>  
            </table>
            @else
            <h1>No posts yet</h1>
            <a href="/control/create_post" class="button is-link">Create post</a>
            @endif
            </div>
        </div>

      
      
    </div>
    <div>
        {{ $posts->links('pagination.default') }}
    </div>
</div>

    


@endsection