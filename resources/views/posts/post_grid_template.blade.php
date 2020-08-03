     <div class="column is-4 grid_element transparent">
        <div class="card custom_card">
            <div class="card-image">
                @if($post->media_type == "image")
                    <figure class="has-text-centered image_figure">
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
            <div class="media" style="margin-bottom:10px;">
                <div class="media-content">
                    <div>
                        <p class="title is-4" style="margin-bottom: 0px; display:inline-block;"><a class="post_title_grid" href="/post/{{$post->id}}">{{$post->post_title}}</a></p>
                        @if($post->pinned == 1)
                            <i class="fas fa-thumbtack pin" style="display:inline-block;" ></i>
                        @endif
                    </div>
                
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item" style="padding-top:10px;">
                                <p class="subtitle is-6" style="margin-bottom:10px;"><a href="/category/{{$post->category}}">{{$post->category}}</a> </p>
                            </div>
                   
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <nav class="breadcrumb" style="margin-top:0;" aria-label="breadcrumbs" id="share_{{$post->id}}">
                                    <ul>
                                        <li class="">  
                                            <a href="https://vk.com/share.php?url={{URL::to('/') . '/post/'.$post->id}}" class="" target="_blank">
                                                <span class="icon vk_color">
                                                    <i class="fab fa-lg fa-vk"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="">  
                                            <a href="https://www.facebook.com/sharer.php?u={{URL::to('/') . '/post/'.$post->id}}&amp;t={{$post->post_title}}" class="" target="_blank">
                                                <span class="icon fb_color">
                                                    <i class="fab fa-lg fa-facebook-f"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="">  
                                            <a href="https://twitter.com/share?url={{URL::to('/') . '/post/'.$post->id}}&amp;text={{$post->post_title}}&amp;" class="" target="_blank">
                                                <span class="icon tw_color">
                                                    <i class="fab fa-lg fa-twitter"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="">  
                                            <a href="https://t.me/share/url?url={{URL::to('/') . '/post/'.$post->id}}&text={{$post->post_title}}" class="" target="_blank">
                                                <span class="icon tg_color">
                                                    <i class="fab fa-lg fa-telegram"></i>
                                                </span>
                
                                            
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                     
                        </div>
                    </div>
                
           
                </div>
            </div>
            <div class="content" style="word-wrap: break-word;">
                <p >
                    @if($post->media == null)
                         {!!Str::limit(strip_tags($post->post_content),460,'...');!!}
                    @if(Str::length($post->post_content) > 460)
                        <a href="/post/{{$post->id}}">Read more</a>
                    @endif
                </p>
            
            @endif
                <div class="is-divider" style="margin-top:0; margin-bottom:15px;"></div>
                <a href="/post/{{$post->id}}#comments">{{$post->comment_count}}</a><br>
                <div style="margin-top:10px;">
                    @if($post->tags != null)
                    @foreach($post->tags as $tag)
                        <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
                    @endforeach
                @endif
                </div>

            </div>
            </div>
        </div>
    </div>