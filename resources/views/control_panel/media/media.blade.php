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
            <a href="{{url()->previous()}}" class="button is-link">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>
                 Back
                </span>
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
                        <form action="/" method="post" style="display:inline;">
                            @csrf
                            <button class="button is-success" data-tooltip="Preview"><i class="fas fa-play"></i></button>
                         </form>
                        <form action="/" method="post" style="display:inline;">
                            @csrf
                            <button class="button is-info" data-tooltip="Edit"><i class="fas fa-edit"></i></button>
                         </form>
                        <form action="/" method="post" style="display:inline;">
                            @csrf
                            <button class="button is-danger" data-tooltip="Delete this file"><i class="fas fa-trash"></i></button>
                         </form>
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

@push('scripts')
<script src="{{ asset('js/custom/control_panel/media.js') }}"></script>
@endpush