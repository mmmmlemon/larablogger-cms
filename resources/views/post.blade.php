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
                   <a class="level-item">
                    <span class="icon is-large"><i class="fas fa-2x fa-share"></i></span>
                  </a>
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
      <h1 class="subtitle">Comments</h1>
      <article class="media">
        <div class="media-content">
          <div class="content">
            <p>
              <strong>Username</strong>
              <br>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis porta eros lacus, nec ultricies elit blandit non. Suspendisse pellentesque mauris sit amet dolor blandit rutrum. Nunc in tempus turpis.
              <br>
               <i>date</i>
            </p>
          </div>
        </div>
      </article>

      <article class="media">
        <div class="media-content">
          <div class="content">
            <p>
              <strong>Username</strong>
              <br>
              i poo and also pee
              <br>
               <i>date</i>
            </p>
          </div>
        </div>
      </article>

      <article class="media">
        <div class="media-content">
          <div class="content">
            <p>
              <strong>Username</strong>
              <br>
             damn bro he really vibin tho owo
              <br>
               <i>date</i>
            </p>
          </div>
        </div>
      </article>
    </div>
  </div>
</div>

<div class="container white-bg">
  <article class="media">
    <div class="media-content">
      <div class="field">
        <p class="control">
          <input class="input" placeholder="username"/>
        </p>
      </div>
      <div class="field">
        <p class="control">
          <textarea class="textarea" placeholder="Add a comment..."></textarea>
        </p>
      </div>
      <nav class="level">
        <div class="level-left">
          <div class="level-item">
            <a class="button is-info">
              <span class="icon">
                <i class="fas fa-comment"></i>
            </span>
            <span>
              Submit comment
            </span>
             </a>
            
          </div>
        </div>
      </nav>
    </div>
  </article>
</div>

@endsection