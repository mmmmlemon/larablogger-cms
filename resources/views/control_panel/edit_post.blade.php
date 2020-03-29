@extends('layouts.app')
@section('content')

<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li class="is-active"><a href="#" aria-current="page">Create post</a></li>
    </ul>
  </nav>
    <div class="columns">
        <div class="column">
        <a href="{{url()->previous()}}" class="button is-link">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>
                 Back
                </span>
            </a>

            <h1 class="title has-text-centered">Create Post</h1>
            <div class="is-divider"></div>

        <form action="/post/{{$post->id}}/edit" method="POST">
            @csrf
              <div class="field">
                <label class="label">Category</label>
                <div class="control">
                  <div class="select">
                    <select name="category">
                      @foreach($categories as $categ)
                    <option value="{{$categ->id}}" @if($categ->id == $post->category_id) selected @endif>{{$categ->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>


                <div class="field">
                    <div class="control">
                        <p class="help">Title</p>
                        <input class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
                      placeholder="Post title" value="@if($errors->any()){{old('post_title')}}@else{{$post->post_title}}@endif">
                    </div>
                    @error('post_title')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                  </div>

                  <div class="field">
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                        <p class="help">Content</p>
                          <textarea class="textarea" name="post_content"  placeholder="Write your post here">{{$post->post_content}}</textarea>
                        </div>
                        
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <input class="is-checkradio is-link" name="publish" id="publish_checkbox" type="checkbox" @if($post->visibility == 1) checked @endif>
                    <label for="publish_checkbox">Visibility</label>
                    <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be visible to everone">  <i class="fas fa-question-circle"></i> </span>
                  
                  </div>

                  <div class="field">
                    <p class="help">Publish date</p>
                    <p class="control has-icons-left">
                       
                      <input class="input" data-tooltip="You can't change the date of publishing" type="date" name="publish_date" min="{{date('Y-m-d', strtotime($post->date))}}" id="publish_date" placeholder="Date" value={{$post->date}} disabled>
                      <span class="icon is-small is-left">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </p>
                   
                  </div>

                  <div class="field">
                    <div class="control">
                        <p class="help">Tags</p>
                    <input class="input" type="text" name="tags" value="{{$post->tags}}" placeholder="video,post,meme,text,whatever">
                    </div>
                    
                  </div>

                  <button type="submit" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-save"></i>
                    </span>
                    <span>
                      Save post
                    </span>
                </button>
            </form>
        </div>
      
    </div>
</div>

@endsection
