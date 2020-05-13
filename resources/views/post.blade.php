@extends('layouts.app')
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
      <li class="is-active"><a href="#" aria-current="page">{{$post->post_title}}</a></li>
    </ul>
  </nav>
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
         
        </ul>
      </nav>
      <a href="{{url()->previous()}}" class="button is-link">
        <span class="icon">
            <i class="fas fa-arrow-left"></i>
        </span>
        <span>
         Back
        </span>
    </a>
    
    <div>&nbsp;</div>
    <div class="columns">
        <div class="column">
        <h1 class="title">{{$post->post_title}}</h1>
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

            <div class="media-content">
              <figure class="has-text-centered">
                <img class="imagee" width="900px" src="{{asset("storage/".$m->media_url)}}" alt="">
              </figure>
            </div>
  
          </article>
          @elseif($m->media_type == "video")
          <article class="has-text-center">

            <div class="media-content">
              <div class="has-text-centered">
              <video controls="controls" class="player" preload="none" poster="{{asset('/storage/')."/".$m->thumbnail_url}}">
                  <source src="{{asset("storage/".$m->media_url)}}">
                </video>
              </div>
            </div>
  
          </article>
          @endif

        @endforeach
          @endif
          <br>
        <article class="media">

            <div class="media-content">
              <div class="content">
               
                 {!!$post->post_content!!}
              
              </div>
            <div class="is-divider"></div>
        
              <nav class="level is-mobile">
                <div class="level-left">
                  <a class="level-item share-button" for="share_{{$post->id}}">
                    <span class="icon is-large"><i class="fas fa-2x fa-share"></i></span>
                </a>
                <nav class="breadcrumb invisible" aria-label="breadcrumbs" id="share_{{$post->id}}">
                  <ul>
                  <li class="">  
                    <a href="https://vk.com/share.php?url={{URL::to('/') . '/post/'.$post->id}}" class="" target="_blank">
                      <span class="icon has-text-info">
                        <i class="fab fa-2x fa-vk"></i>
                      </span>
                    </a>
                  </li>
                  <li class="">  
                    <a href="https://www.facebook.com/sharer.php?u={{URL::to('/') . '/post/'.$post->id}}&amp;t={{$post->post_title}}" class="" target="_blank">
                      <span class="icon has-text-white">
                        <i class="fab fa-2x fa-facebook-f"></i>
                      </span>
                    </a>
                  </li>
                  <li class="">  
                    <a href="https://twitter.com/share?url={{URL::to('/') . '/post/'.$post->id}}&amp;text={{$post->post_title}}&amp;" class="" target="_blank">
                      <span class="icon has-text-link">
                        <i class="fab fa-2x fa-twitter"></i>
                      </span>
                    </a>
                  </li>
                  </ul>
                </nav>
                  {{-- <a class="level-item">
                    <span class="icon is-large"><i class="fas fa-2x fa-heart"></i></span>
                  </a> --}}
                </div>
              </nav>
            </div>

          </article>
        </div>
    </div>
</div>

<div class="container white-bg" id="comments">
  <div class="columns">
    <div class="column">
    <div class="subtitle">{{$post->comment_count}} / <a href="#comment_form">Leave a comment</a></div> 

      @foreach($comments as $comment)
      <article class="media">
        <div class="media-content">
          <div class="content">
            <div class="is-4">
                <strong>{{$comment->username}}</strong>
                  @if($is_admin == true)
                  <form action="/post/change_comment_status/" method="POST" style="display:inline">
                    @csrf
                    <input type="text" class="invisible"  name="comment_id" value="{{$comment->id}}">
                    @php
                      $val = "";
                      $txt = "";
                      $icon = "";
                      $color ="";

                      if($comment->visibility == 1)
                      {
                        $val = "hide";
                        $txt = "Hide";
                        $icon = "fas fa-ban";
                        $color = "has-text-danger";
                      }
                      else{
                        $val = "show";
                        $txt = "Show";
                        $icon = "fas fa-check";
                        $color = "has-text-success";
                      }
                    @endphp
                    <input type="text" class="invisible "name="action" value="{{$val}}">
                    <button type="submit" class="action-button" data-tooltip="{{$txt}} this comment">
                      <span class="icon {{$color}}">
                          <i class="{{$icon}}"></i>
                      </span>
                  </button>
                  </form>

                <form action="/post/change_comment_status/" method="POST" style="display:inline">
                  @csrf
                  <input type="text" class="invisible"  name="comment_id" value="{{$comment->id}}">
                  <input type="text" class="invisible "name="action" value="delete">
                    <button type="submit" class="action-button" data-tooltip="Delete this comment">
                      <span class="icon has-text-dark">
                          <i class="fas fa-trash"></i>
                      </span>
                  </button>
                </form>
              @endif
              
          </div>
              <br>
              <div class="content">
                {!!$comment->comment_content!!}
              </div>
              
               <i>{{date('d.m.Y', strtotime($comment->date))}}</i>
           
          </div>
        </div>
      </article>

     @endforeach
    </div>
  </div>
</div>

<div class="container white-bg" id="comment_form">
<form action="/submit_comment/{{$post->id}}" method="POST">
  @csrf
  <article>
    <div class="media-content">
      <div class="field">
        <p class="control">
        <input class="input" name="username" placeholder="username" maxlength="25" id="username" value="{{$username}}"/>
        </p>
            @error('username')
              <p class="help is-danger"><b> {{ $message }}</b></p>  
            @enderror
      </div>
    
          <textarea class="textarea" name="comment_content" placeholder="Add a comment..."></textarea>
        </p>
        @error('comment_content')
        <p class="help is-danger"><b> {{ $message }}</b></p>  
      @enderror
      <br>
      <nav class="level">
        <div class="level-left">
          <div class="level-item">
        
            <button type="submit" class="button is-link">
              <span class="icon">
                <i class="fas fa-comment"></i>
            </span>
            <span>
              Submit comment
            </span>
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
      <img id="img-in-modal" width="90%" src="" alt="">
      <a id="link-in-modal" target="_blank" href="">Download</a>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/custom/post.js') }}"></script>
<script src="{{ asset('js/custom/home_page.js') }}"></script>
@endpush