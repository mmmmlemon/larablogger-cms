@foreach($comments as $comment)
    @yield('comment', View::make('comment', compact('comment','is_admin')))
    <div class="comment_container">
        @include('comment_tree',['comments'=>$comment->replies])
    </div>
@endforeach
