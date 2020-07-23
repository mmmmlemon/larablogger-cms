{{-- Grid view for posts --}}
<div>
    <div class="columns">
    @php
        $count = 0;
    @endphp
    @foreach($posts as $post)
        @yield('post', View::make('posts/post_grid_template', compact('post')))
        @php
            $count++;
        @endphp
        @if($count == 3)
            </div><div class="columns">
            @php
                $count = 0;
            @endphp
        @endif
    @endforeach
            </div>
</div>