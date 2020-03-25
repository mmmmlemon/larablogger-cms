<div id="posts_content" class="invisible">
    @if($posts->count() > 0)
        @foreach($posts as $post)
            <h1>{{$post->post_title}}</h1>
        
        @endforeach
    @else
    <h1>No posts yet</h1>
    <a href="/control/create_post" class="button is-link">Create post</a>
    @endif
    </div>