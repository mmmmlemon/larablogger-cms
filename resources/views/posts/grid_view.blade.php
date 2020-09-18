{{-- Grid view for posts --}}
<div>
    <div id ="spinner" class="">
        <div class="@if(config('isMobile') != true)loader_pill @else loader_pill_mobile @endif has-text-centered">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <div>Loading posts...</div>
        </div>
</div>
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