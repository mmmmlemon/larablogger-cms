<div class="column is-4">
    <div class="card custom_card">
        <div class="card-image">
            @if($post->media_type == "image")
                <figure class="has-text-centered">
                    <img class="imagee custom_card_rooftop" src="{{asset("storage/".$post->media[0]->media_url)}}" alt="">
                    {{-- @if(count($post->media) > 1)
                        <p><a href="/post/{{$post->id}}">(and {{count($post->media)-1}} more images)</a></p>
                        <br>
                    @endif --}}
                </figure>
            @endif
            
            @if($post->media_type == "video")
                <figure class="has-text-centered">
                    <div class="custom_card_rooftop" style="overflow: hidden;">
                    {{-- <img class="imagee" src="{{asset("storage/".$post->media[0]->media_url)}}" alt=""> --}}
                    <video class="video-player" controls="controls" @if($post->media[0]->thumbnail_url != null)preload="none"  poster="{{asset('/storage/')."/".$post->media[0]->thumbnail_url}}@endif">
                        <source class="custom_card_rooftop" src="{{asset("storage/".$post->media[0]->media_url)}}">
                            @if($post->media[0] != null && $post->media[0]->subs != null)
                                @foreach($post->media[0]->subs as $s)
                                    <track kind="subtitles" label="{{$s->display_name}}" src="{{asset('/storage/')."/".$s->sub_url}}"/>
                                @endforeach
                            @endif 
                    </video>
                </div>
                </figure>
            @endif
        </div>
        <div class="card-content">
        <div class="media">
            <div class="media-content">
                <p class="title is-4"><a href="/post/{{$post->id}}">{{$post->post_title}}</a></p>
                <p class="subtitle is-6"><a href="/category/{{$post->category}}">{{$post->category}}</a></p>
            </div>
        </div>
        <div class="content">
            @if($post->media == null)
            {!!Str::limit(strip_tags($post->post_content),460,'...');!!}
            @if(Str::length($post->post_content) > 460)
                <a href="/post/{{$post->id}}">Read more</a>
            @endif
            <br><br>
        @endif
            <a href="/post/{{$post->id}}#comments">{{$post->comment_count}}</a><br>
            @foreach($post->tags as $tag)
            <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
        @endforeach

        </div>
        </div>
    </div>
</div>