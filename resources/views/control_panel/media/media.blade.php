@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="white-bg">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><a href="/control">Control panel</a></li>
                    <li><a href="/control/posts">Posts</a></li>
                    <li class="is-active"><a href="#" aria-current="page">Media browser</a></li>
                </ul>
            </nav>
            <div class="column">
                <a href="/control/posts" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span>Back</span>
                </a>
            </div>

            <div class="is-divider"></div>

            <div class="columns">
                @if(count($media) == 0)
                    <div class="column has-text-centered">
                        <h1 class="title">No media files yet</h1>
                        <i class="fas fa-file-video"></i>
                        <h1 class="subtitle">Come again later</h1>
                    </div>
                @else
                    <table class="table is-fullwidth is-hoverable">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Filetype</th>
                                <th>Post</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        @foreach($media as $m)
                            <tr>
                                <td>
                                    <b><a href="/control/media/{{$m->id}}">{{$m->display_name}}</a></b>
                                </td>
                                <td>
                                    {{$m->media_type}}
                                </td>
                                <td>
                                    <a target="_blank" href="/post/{{$m->post_id}}">{{$m->post_title}}</a>
                                </td>
                                <td>
                                    <button class="button is-success preview" data-tooltip="Preview"
                                        data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <a href="/control/media/{{$m->id}}" class="button is-info" data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <a class="deleteFile button is-danger" data-id="{{$m->id}}" data-tooltip="Delete this file">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>

        <div>
            {{ $media->links('pagination.default') }}
        </div>
    </div>
@endsection

@section('modals')
<div class="modal" id="preview-modal">
    <div class="modal-background"></div>
    <div class="modal-content column is-two-thirds-desktop is-12-mobile">
        <p class="image has-text-centered">
          <div class="has-text-centered">
            <img id="content-in-modal" width="90%" class="centered_image" src="" alt="" style="padding:0px;">
            <div id="player_div" style="display: none;">
                <video controls="controls" id="player">
                    <source src="" id="content-video">
                </video>
            </div>
          </div>
        </p>
    </div>
    <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>

<div class="modal modalDelete">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">You sure?</p>
        <button class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <p>Are you sure you want to delete this file?</p>
        <b id="modal_post_title"></b>
        <p>The file will be removed both from post and physically.</p>
        <p class="has-text-danger">This action cannot be undone.</p>
      </section>
      <footer class="modal-card-foot">
            <form id="modal_form" action="/control/media/delete_media" method="post" style="display:inline;">
                @csrf
                <input type="text" name="id" value="pee" id="modal_input" class="invisible">
            </form>
            <button class="button is-danger" id="submit_modal">Delete</button>
            <button class="button cancel">Cancel</button>
      </footer>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/media.js') }}"></script>
@endpush