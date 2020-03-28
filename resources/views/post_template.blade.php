<div class="column is-12 white-bg">
    <a href="/post/{{$post->id}}">
      <h1 class="title">{{$post->post_title}}</h1>
    </a> 
  <div class="is-divider"></div>
  <div class="media-content">
    <div class="content">
      <p> 
       {{$post->post_content}}
      </p>
    </div>
    <div>
      @if(count($post->tags) > 1)
        @foreach($post->tags as $tag)
        <span class="tag is-info">{{$tag}}</span>
        @endforeach
      @endif
      <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
        <li class=""><a href="/category/{{App\Category::find($post->category_id)->category_name}}" aria-current="page">{{App\Category::find($post->category_id)->category_name}}</a></li>
        <li><a href="/post/{{$post->id}}#comments">5 comments</a></li>
        </ul>
      </nav>
    </div>
   
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

  </div>