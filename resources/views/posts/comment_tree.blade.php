@foreach($comments as $comment)
    @yield('comment', View::make('posts/comment', compact('comment','is_admin')))
    <div class="comment_container">
        @include('posts/comment_tree',['comments'=>$comment->replies])
    </div>
@endforeach
