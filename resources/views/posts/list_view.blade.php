{{-- List view for posts --}}
@foreach($posts as $post)
    @yield('post', View::make('posts/post_template', compact('post')))
@endforeach