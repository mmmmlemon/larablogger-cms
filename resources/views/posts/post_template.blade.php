<div class="column is-12 white-bg">
    <div>
        <a href="/post/{{$post->id}}" style="display:inline-block;">
            <h1 class="title post_title" @if(config('isMobile')) style="font-size: 20pt;" @endif>{{$post->post_title}}</h1>
        </a> 
        @if($post->pinned == 1)
            <i class="fas fa-thumbtack pin" style="display:inline-block;"></i>
        @endif
    </div>
    <br>
    @if($post->tags != null)
    @foreach($post->tags as $tag)
        <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
    @endforeach
    @endif
    <div class="is-divider"></div>
    <article class="has-text-center">
        <div class="media-content">
            @if($post->media_type == "image")
            <figure class="has-text-centered">
                <img class="imagee" src="{{asset("storage/".$post->media[0]->media_url)}}" data-id="{{$post->media[0]->id}}" alt="">
                @if(count($post->media) > 1)
                    <p><a href="/post/{{$post->id}}">(and {{count($post->media)-1}} more images)</a></p>
                    <br>
                @endif
            </figure>
            <div class="has-text-left">
                {!!strip_tags(Str::limit($post->post_content,strpos($post->post_content,"</p>"),''));!!}
                @if(Str::length($post->post_content) > strpos($post->post_content,"</p>"))
                    <a href="/post/{{$post->id}}">Read more</a>
                @endif
            </div>
            @endif

            @if($post->media_type == "video")
                <div class="has-text-centered">
                    <video class="video-player" controls="controls" @if($post->media[0]->thumbnail_url != null)preload="none" data-id="{{$post->media[0]->id}}"  poster="{{asset('/storage/')."/".$post->media[0]->thumbnail_url}}@endif">
                        <source src="{{asset("storage/".$post->media[0]->media_url)}}">
                            @if($post->media[0] != null && $post->media[0]->subs != null)
                                @foreach($post->media[0]->subs as $s)
                                    <track kind="subtitles" label="{{$s->display_name}}" src="{{asset('/storage/')."/".$s->sub_url}}"/>
                                @endforeach
                            @endif 
                    </video>
                    <br>
                    <div class="has-text-left">
                        {!!strip_tags(Str::limit($post->post_content,strpos($post->post_content,"</p>"),''));!!}
                        @if(Str::length($post->post_content) > strpos($post->post_content,"</p>"))
                            <a href="/post/{{$post->id}}">Read more</a>
                        @endif
                    </div>
                   
                </div>
                <br>
            @endif
        </div>
  </article>
  <div class="media-content">
        <div class="content">
            @if($post->media == null)
                {!!Str::limit(strip_tags($post->post_content),1200,'...');!!}
                @if(Str::length($post->post_content) > 1200)
                    <a href="/post/{{$post->id}}">Read more</a>
                @endif
            @endif
        </div>
        <div class="is-divider"></div>
        <div>
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    @if($post->category != "")
                        <li><a href="/category/{{$post->category}}">{{$post->category}}</a></li>
                    @endif  
                    <li><a href="/post/{{$post->id}}#comments">{{$post->comment_count}}</a></li>
                </ul>
            </nav>
        </div>
   
        @if(config('isMobile') != true)
        <nav class="level is-mobile">
            <div class="level-left">
                <a class="level-item share-button has-text-info" for="share_{{$post->id}}">
                    <span class="icon is-large"><i class="fas fa-2x fa-share"></i></span>
                </a>
                <nav class="breadcrumb invisible" aria-label="breadcrumbs" id="share_{{$post->id}}">
                    <ul>
                        <li class="">  
                            <a href="https://vk.com/share.php?url={{URL::to('/') . '/post/'.$post->id}}" class="" target="_blank">
                                <span class="icon vk_color">
                                    <i class="fab fa-2x fa-vk"></i>
                                </span>
                            </a>
                        </li>
                        <li class="">  
                            <a href="https://www.facebook.com/sharer.php?u={{URL::to('/') . '/post/'.$post->id}}&amp;t={{$post->post_title}}" class="" target="_blank">
                                <span class="icon fb_color">
                                    <i class="fab fa-2x fa-facebook-f"></i>
                                </span>
                            </a>
                        </li>
                        <li class="">  
                            <a href="https://twitter.com/share?url={{URL::to('/') . '/post/'.$post->id}}&amp;text={{$post->post_title}}&amp;" class="" target="_blank">
                                <span class="icon tw_color">
                                    <i class="fab fa-2x fa-twitter"></i>
                                </span>
                            </a>
                        </li>
                        <li class="">  
                            <a href="https://t.me/share/url?url={{URL::to('/') . '/post/'.$post->id}}&text={{$post->post_title}}" class="" target="_blank">
                                <span class="icon tg_color">
                                    <i class="fab fa-2x fa-telegram"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>
    @else
        <nav class="level is-mobile" style="margin-top:15px;">
            <div class="level-left">
                <nav class="breadcrumb " aria-label="breadcrumbs" id="share_{{$post->id}}">
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
        </nav>
    @endif
  </div>
</div>