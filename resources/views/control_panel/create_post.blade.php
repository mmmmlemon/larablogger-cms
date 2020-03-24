@extends('layouts.app')
@section('content')

<div class="container white-bg">
    <div class="columns">
        <div class="column">
        <a href="{{url()->previous()}}" class="button is-link" data-tooltip="Back to Control panel">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>
                 Back
                </span>
            </a>
            <h1 class="title has-text-centered">Create Post</h1>
            <div class="is-divider"></div>

            <form action="control_panel/create_new_post" method="GET">
                <div class="field">
                    <div class="control">
                        <p class="help">Title</p>
                      <input class="input" type="text" name="post_title" placeholder="Post title">
                    </div>
                    
                  </div>

                  <div class="field">
                    <div class="field-body">
                      <div class="field">
                        <div class="control">
                        <p class="help">Content</p>
                          <textarea class="textarea" name="post_content" placeholder="Write your post here"></textarea>
                        </div>
                        
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <input class="is-checkradio is-link" name="publish" id="exampleCheckbox" type="checkbox" name="exampleCheckbox" checked="checked">
                    <label for="exampleCheckbox">Publish</label>
                    <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be published immediately, otherwise you have to pick a different date">  <i class="fas fa-question-circle"></i> </span>
                  
                  </div>

                  <div class="field">
                    <p class="help">Publish date</p>
                    <p class="control has-icons-left">
                       
                      <input class="input" type="date" name="publish_date" placeholder="Date" value={{$current_date}}>
                      <span class="icon is-small is-left">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </p>
                   
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