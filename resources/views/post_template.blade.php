<div class="column is-12 white-bg">
    <a href="/post/{{$post->id}}">
      <h1 class="title">{{$post->post_title}}</h1>
    </a> 
  <div class="is-divider"></div>
  <div class="media-content">
    <div class="content">
     
       {!!$post->post_content!!}
     
    </div>
    <div>
      @if(count($post->tags) > 1)
        @foreach($post->tags as $tag)
    <span class="tag is-info"><a class="has-text-white" href="/post/tag/{{$tag}}">{{$tag}}</a></span>
        @endforeach
      @endif
      <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
        <li class=""><a href="/category/{{App\Category::find($post->category_id)->category_name}}" aria-current="page">{{App\Category::find($post->category_id)->category_name}}</a></li>
        <li><a href="/post/{{$post->id}}#comments">{{$post->comment_count}} comments</a></li>
        </ul>
      </nav>
    </div>
   
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

  </div>