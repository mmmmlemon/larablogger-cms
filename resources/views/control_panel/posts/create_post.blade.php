@extends('layouts.app')
@section('title', 'Add a new post'." -")
@section('content')
@php
$blank="";
@endphp
<div class="container white-bg">

  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li><a href="/control/posts" aria-current="page">Posts</a></li>
      <li class="is-active"><a href="#" aria-current="page">Add post</a></li>
    </ul>
  </nav>
        

  <h1 class="title has-text-centered">Add Post</h1>
  <div class="is-divider"></div>

  <form id="post_form" action="control/create_new_post" enctype="multipart/form-data" method="POST">
    @csrf

    <div class="field">
      <label class="label">Category</label>
      <div class="control">
        <div class="select">
          <select name="category" id="post_category">
            @foreach($categories as $categ)
              <option value="{{$categ->id}}">{{$categ->category_name}}</option>
            @endforeach
          </select>
        </div>
      </div>  
    </div>

    <div class="field">
      <div class="control">
        <label class="label">Title</label>
        <input maxlength="70" id="post_title" class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
      placeholder="Post title" value="@if($errors->any()){{old('post_title')}}@else{{$blank}}@endif">
      </div>
        @error('post_title')
          <p class="help is-danger"><b> {{ $message }}</b></p>  
        @enderror
    </div>

    <label class="label">Main text/content</label>
    <textarea class="textarea post_content" id="post_content" maxlength="700" name="post_content" placeholder="Write your post here"></textarea>
    @error('post_content')
      <p class="help is-danger"><b> {{ $message }}</b></p>
    @enderror

    <div class="field">
      <input class="is-checkradio is-link" name="pinned" id="pinned_checkbox" type="checkbox">
      <label class="label" for="pinned_checkbox">Pinned</label>
      <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be pinned to the top of the home page"">
        <i class="fas fa-question-circle"></i>
      </span>
    </div>
    <div class="field">
   
      <input class="is-checkradio is-link" id="publish_checkbox" type="checkbox" name="publish" checked="checked">
      <label class="label" for="publish_checkbox">Visibility</label>
      <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be visible to everyone on the web-site, if not, it will be hidden.">
          <i class="fas fa-question-circle"></i>
      </span>
    </div>

    <div class="field">
      <label class="label">Publish date</label>
      <p class="control has-icons-left">
        <input class="input" type="date" name="publish_date" min="{{date('Y-m-d', strtotime($current_date))}}" 
          id="publish_date" placeholder="Date" value={{$current_date}}>
        <span class="icon is-small is-left">
          <i class="fas fa-calendar"></i>
        </span>
      </p>
    </div>

    <div class="field">
      <label class="label">Tags</label>
      <div class="control">
        <input class="input" type="text" data-type="tags" id="tags" placeholder="Choose Tags" value="">
      </div>
    </div>  
  </form>
  <br>

  <form action="/post/upload_files" class="dropzone" id="file_form">
    @csrf
    <div class="fallback">
      <input name="file" type="file" multiple />
    </div>
  </form>

  <div>&nbsp;</div>


  <a id="submit_post" class="button is-link @if(config('isMobile')) is-fullwidth @endif">
    <span class="icon">
      <i class="fas fa-save"></i>
    </span>
    <span>Save Post</span>
  </a>    
</div>
@endsection

@push('scripts')
  <script src="{{ asset('js/tags-input.js') }}"></script>
  <script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
  <script src="{{ asset('js/dropzone.js') }}"></script>
  <script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
  <script src="{{ asset('js/custom/control_panel/create_post.js') }}"></script>
@endpush