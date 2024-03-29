@extends('layouts.app')
@section('title', $post->post_title." -")
@section('content')
<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            @if(Auth::check())
                @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                    <li><a href="/control">Control panel</a></li>
                    <li><a href="/control/posts" aria-current="page">Posts</a></li>
                @endif
            @endif
            @if($post->category != "")
                <li><a href="/category/{{$post->category}}">{{$post->category}}</a></li>
            @endif
            <li class="is-active"><a href="#" aria-current="page">{{Str::limit($post->post_title,30,"...")}}</a></li>
        </ul>
    </nav>

    @if($is_admin == true)
    <a href="/post/{{$post->id}}/edit" class="button is-link">
        <span class="icon">
            <i class="fas fa-edit"></i>
        </span>
        <span>Edit this post</span>
    </a>
    <div>&nbsp;</div>
    @endif
    <div class="columns">
        <div class="column">
            <div>
                <h1 class="title post_title" style="display:inline-block;">{{$post->post_title}}</h1>
                @if($post->pinned == 1)
                    <i class="fas fa-thumbtack pin" style="display:inline-block; "></i>
                @endif
            </div>
        
            @if($post->tags != null)
                @foreach($post->tags as $tag)
                    <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
                @endforeach
            @endif

            <div class="is-divider"></div>

            @if(count($media) > 0)
                @foreach($media as $m)
                    @if($m->media_type == "image")
                        <article class="has-text-center">
                            <div class="media-content ">
                                <figure class="has-text-centered">
                                <img class="imagee" width="900px" src="{{asset("storage/".$m->media_url)}}" alt="">
                                </figure>
                            </div>
                        </article>
                    @elseif($m->media_type == "video")
                        <article class="has-text-center">
                            <div class="media-content">
                                <div class="has-text-centered">
                                    <video controls="controls" class="player" data-id="{{$m->id}}" @if($m->thumbnail_url != null)preload="none" poster="{{asset('/storage/')."/".$m->thumbnail_url}}"@endif>
                                        <source src="{{asset("storage/".$m->media_url)}}">
                                            @if($m != null && $m->subs != null)
                                            @foreach($m->subs as $s)
                                                <track kind="subtitles" label="{{$s->display_name}}" src="{{asset('/storage/')."/".$s->sub_url}}"/>
                                            @endforeach
                                        @endif 
                                    </video>
                                </div>
                            </div>
                            <br>
                        </article>
                    @endif
                @endforeach
                <br>
            @endif

            <article class="media">
                <div class="media-content">
                    <div class="content p_fix">
                        {!!$post->post_content!!}
                    </div>
                    <div class="is-divider"></div>

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
                        <nav class="level is-mobile">
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
            </article>
        </div>
    </div>
    <nav class="level is-mobile">
        <!-- Left side -->
        <div class="level-left">
          @if($post->next != null)
            <div class="level-item">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                <a href="/post/{{$post->next}}">Newer Post</a>
            </div>
          @endif
        </div>
      
        <!-- Right side -->
        <div class="level-right">
            @if($post->previous != null)
                <div class="level-item">
                    <a href="/post/{{$post->previous}}">Older Post</a>
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </div>
            @endif
        </div>
      </nav>
    @if($is_admin == true)
        <p>Views: {{$post->view_count}}</p>
    @endif
</div>

<div class="container white-bg-comments" id="comments">
  <div class="columns">
    <div class="column">
        <div class="subtitle">{{$post->comment_count}} / <a href="#comment_form">Leave a comment</a></div> 
            @include('posts/comment_tree',['comments'=>$comments])
    </div>
  </div>
</div>

<div class="container white-bg" id="comment_form">
    <form action="/submit_comment/{{$post->id}}" id="comm_form" method="POST">
        @csrf
        <article>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <input class="input" name="username" placeholder="Username" maxlength="25" id="username" value="{{ $username }}" required 
                            @if($username != "") readonly @endif/>
                        <input type="text" id="reply_to" class="invisible" value="" name="reply_to">
                        <a id="reply_p"></a>
                        <b id="remove_reply" class="remove_reply invisible" data-tooltip="Remove reply">X</b>
                    </p>
                    @error('username')
                        <p class="help is-danger"><b> {{ $message }}</b></p>  
                    @enderror
                </div>
                <textarea class="textarea" name="comment_content" id="comment_textarea" placeholder="Add a comment..." srequired>
                    {{ old('comment_content') }}
                </textarea>
                @error('comment_content')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                @enderror
                <div class="columns is-vcentered is-left">
                    <div class="column is-1 has-text-centered">{{$question}}</div>
                    <div class="column is-2">
                        <input type="text" name="question_1" class="invisible" value="{{$randNums[0]}}">
                        <input type="text" name="question_2" class="invisible" value="{{$randNums[1]}}">
                        <input type="text" name="question_answer" class="input is-normal" placeholder="Answer" value="{{ old('question_answer') }}" required>
                    </div>
                    <p class="is-size-7">Example: I + II = 3</p>
                    @error('question_answer')
                        &nbsp;&nbsp;<p class="help is-danger"><b> {{ $message }}</b></p>  
                     @enderror
                </div>
        
            
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <button type="submit" id="submit_comment" class="button is-link @if(config('isMobile')) is-fullwidth @endif" >
                                <span class="icon">
                                    <i class="fas fa-comment"></i>
                                </span>
                                <span>Submit comment</span>
                            </button>  
                        </div>
                    </div>
                </nav>
            </div>
        </article>
    </form>
</div>

@endsection

@section('modals')
<div class="modal" id="img-modal">
  <div class="modal-background"></div>
  <div class="modal-content column is-two-thirds-desktop is-12-mobile">
    <p class="image has-text-centered">
      <div class="has-text-centered">
        <img id="img-in-modal" width="90%" src="" alt="">
        <br>
        <a id="link-in-modal" target="_blank" href="">Download</a>
      </div>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/custom/post.js') }}"></script>
@endpush