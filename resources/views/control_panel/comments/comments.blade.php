@extends('layouts.app')

@section('content')
<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li class="is-active"><a href="#" aria-current="page">Comments</a></li>
    </ul>
  </nav>

<div class="column is-12">
    <a href="/control" class="button is-link">
        <span class="icon">
            <i class="fas fa-arrow-left"></i>
        </span>
        <span>
         Back
        </span>
    </a>
</div>

<div class="is-divider"></div>
@if(count($comments) <= 0)
    <div class="column has-text-centered">
      <h3 class="subtitle">No comments yet</h3>
    </div>
@else
    <table class="table is-hoverable is-fullwidth">
    <thead>
      <th>
        Comment
      </th>
      <th>
        User
      </th>
      <th>
        Post
      </th>
    
      <th>
        Date
      </th>
      <th>
        Actions
      </th>
    </thead>
    <tbody>
      @foreach($comments as $c)
        <tr>
        <td style="width:40%;"><a href="{{ url('/post/'.$c->post_id."#comment_anchor_".$c->id)}}" target="_blank" data-tooltip="Go to this comment" class="comment_link">{!!$c->comment_content!!}</a></td>
        <td>{{$c->username}}</td>
        <td><a  href="{{ url('/post/'.$c->post_id)}}" data-tooltip="Go to this post" target="_blank">{{$c->post_title}}</a></td>

        <td>{{date('d.m.Y',strtotime($c->date))}}</td>
        <td>
          @if($c->visibility == 1)
            <form action="/post/change_comment_status" method="post" style="display:inline;">
              @csrf
              <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
              <input type="text" class="invisible "name="action" value="hide">
              <button class="button is-warning" data-tooltip="Hide this comment"><i class="fas fa-ban"></i></button>
            </form>
          @else
          <form action="/post/change_comment_status" method="post" style="display:inline;">
            @csrf
            <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
            <input type="text" class="invisible "name="action" value="show">
            <button class="button is-success" data-tooltip="Show this comment"><i class="fas fa-check"></i></button>
          </form>
          @endif
        <button class="button is-danger showModalDelete" data-tooltip="Delete this comment" data-id="{{$c->id}}"><i class="fas fa-trash"></i></button>
        </td>
        </tr>
      @endforeach
    </tbody>
    </table>
@endif
</div>
<div class="container">
  {{ $comments->links('pagination.default') }}
</div>
@endsection

@section('modals')
<div class="modal modalDelete">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">You sure?</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <p>Are you sure you want to delete this comment?</p>
      <b id="modal_post_title"></b>
      <p class="has-text-danger">This action cannot be undone.</p>
    </section>
    <footer class="modal-card-foot">
        <form id="modal_form" action="/post/change_comment_status" method="post" style="display:inline;">
              @csrf
              <input type="text" class="invisible" id="modal_form_input" name="comment_id">
              <input type="text" class="invisible "name="action" value="delete">
      </form>
      <button class="button is-danger" id="submit_modal">Delete</button>
      <button class="button cancel">Cancel</button>
    </footer>
  </div>
@endsection


@push('scripts')
<script src="{{ asset('js/custom/control_panel/comments.js') }}"></script>
@endpush