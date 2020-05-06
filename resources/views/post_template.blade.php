<div class="column is-12 white-bg">
    <a href="/post/{{$post->id}}">
      <h1 class="title">{{$post->post_title}}</h1>
    </a> 
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
      <img class="imagee" src="{{asset("storage/".$post->media[0]->media_url)}}" alt="">
      @if(count($post->media) > 1)
      <p><a href="/post/{{$post->id}}">(and {{count($post->media)-1}} more images)</a></p>
      <br>
      @endif
      
    </figure>
 
    @endif
    @if($post->media_type == "video")
    <div class="has-text-centered">
      <video class="video-player" controls="controls">
        <source src="{{asset("storage/".$post->media[0]->media_url)}}">
      </video>
    </div>
    <br>
    @endif
    </div>

  </article>


  <div class="media-content">
    <div class="content">
       {!!Str::limit(strip_tags($post->post_content),1200,'...');!!}
       @if(Str::length($post->post_content)>1200)
          <a href="/post/{{$post->id}}">Read more</a>
       @endif
    </div>

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
        
      </div>
    </nav>

  </div>

  </div>