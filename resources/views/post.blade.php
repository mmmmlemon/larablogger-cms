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
      <li><a href="/category/{{App\Category::find($post->category_id)->category_name}}">{{App\Category::find($post->category_id)->category_name}}</a></li>
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
        <div class="is-divider"></div>
        <article class="media">

            <div class="media-content">
              <div class="content">
               
                 {!!$post->post_content!!}
              
              </div>
              <div class="is-divider"></div>
              @if(count($post->tags) > 1)
              @foreach($post->tags as $tag)
              <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
              @endforeach
            @endif
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
    <div class="subtitle">{{count($comments)}} comments / <a href="#comment_form">Leave a comment</a></div> 
      @foreach($comments as $comment)
      <article class="media">
        <div class="media-content">
          <div class="content">
            <p>
              <strong>{{$comment->username}}</strong>
              <br>
              <div class="content">
                {!!$comment->comment_content!!}
              </div>
              
               <i>{{date('d.m.Y', strtotime($comment->date))}}</i>
            </p>
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
  <article class="media">
    <div class="media-content">
      <div class="field">
        <p class="control">
        <input class="input" name="username" placeholder="username" value="{{$username}}"/>
        </p>
            @error('username')
              <p class="help is-danger"><b> {{ $message }}</b></p>  
            @enderror
      </div>
      <div class="field">
        <p class="control">
          <textarea class="textarea" name="comment_content" placeholder="Add a comment..."></textarea>
        </p>
        @error('comment_content')
        <p class="help is-danger"><b> {{ $message }}</b></p>  
      @enderror
      </div>
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

@push('scripts')
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script>
   $('.textarea').richText({
    imageUpload:false,
    videoEmbed:false
  });

</script>
<script src="{{ asset('js/home_page.js') }}"></script>
@endpush