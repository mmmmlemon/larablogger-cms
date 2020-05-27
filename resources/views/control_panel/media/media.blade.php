@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="white-bg">
            {{-- навигация --}}
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><a href="/control">Control panel</a></li>
                    <li><a href="/control/posts">Posts</a></li>
                    <li class="is-active"><a href="#" aria-current="page">Media browser</a></li>
                </ul>
            </nav>
            {{-- кнопка - назад --}}
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
                {{-- если медиафайлов нет, то выводим предупреждение --}}
                @if(count($media) == 0)
                    <div class="column has-text-centered">
                        <h1 class="title">No media files yet</h1>
                        <i class="fas fa-file-video"></i>
                        <h1 class="subtitle">Come again later</h1>
                    </div>
                @else
                    {{-- таблица со списком файлов --}}
                    <table class="table is-fullwidth is-hoverable">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Filetype</th>
                                <th>Post</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        {{-- вывод списка медиафайлов --}}
                        @foreach($media as $m)
                            <tr>
                                {{-- имя файла --}}
                                <td>
                                    <b><a href="/control/media/{{$m->id}}">{{$m->display_name}}</a></b>
                                </td>
                                {{-- тип файла --}}
                                <td>
                                    {{$m->media_type}}
                                </td>
                                {{-- пост к которому прикреплен файл --}}
                                <td>
                                    <a target="_blank" href="/post/{{$m->post_id}}">{{$m->post_title}}</a>
                                </td>
                                {{-- кнопки --}}
                                <td>
                                    {{-- кнопка превью файла --}}
                                    <button class="button is-success preview" data-tooltip="Preview"
                                        data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    {{-- кнопка редактирование файла --}}
                                    <a href="/control/media/{{$m->id}}" class="button is-info" data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- кнопка удаление файла --}}
                                    <form action="/control/media/delete_media/" method="post" style="display:inline;">
                                        @csrf
                                        <input type="text" name="id" value="{{$m->id}}" class="invisible">
                                        <button class="button is-danger" data-tooltip="Delete this file">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>

        {{-- пагинация --}}
        <div>
            {{ $media->links('pagination.default') }}
        </div>
    </div>
@endsection

{{-- модальные окна --}}
@section('modals')
<!--превью файла-->
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

@endsection

@push('scripts')
{{-- Plyr --}}
<script src="{{ asset('js/plyr.js') }}"></script>
{{-- скрипты для этой страницы --}}
<script src="{{ asset('js/custom/control_panel/media.js') }}"></script>
@endpush