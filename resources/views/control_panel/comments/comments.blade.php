@extends('layouts.app')
@section('title', 'Comments'." -")
@section('content')
<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li class="is-active"><a href="#" aria-current="page">Comments</a></li>
    </ul>
  </nav>

<div class="columns has-text-left">
  <div class="column is-fullwidth">
      <form action="/full_search" method="GET">
          <div class="field has-addons">
              <div class="control has-icons-left has-icons-right" style="width:100%;" id="search_bar_div">
                <input type="text" name="type" value="comment" class="invisible">
                <input class="input" type="text" placeholder="Search comment" id="search_bar" name="search_value" value="{{$val ?? '' }}">
                <span class="icon is-small is-left">
                  <i class="fas fa-search"></i>
                </span>
              </div>
              <div class="control">
                <button class="button is-link">
                  Search
                </button>
              </div>
            </div>
      </form>
  </div>
</div>

<div class="is-divider"></div>
<div class="columns">
	<div class="column">
		@if(count($comments) <= 0)
    <div class="column has-text-centered">
      <h3 class="subtitle">No comments yet</h3>
    </div>
@else
    @if(config('isMobile') != true)
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
          <td style="width:40%;"><a href="{{ url('/post/'.$c->post_id."#comment_anchor_".$c->id)}}" target="_blank" data-tooltip="Go to this comment" class="comment_link">
            @if($c->deleted == 0)
              {!!$c->comment_content!!}
            @else
              <s>{!!$c->comment_content!!}</s>
            @endif
          </a></td>
          <td>{{$c->username}}</td>
          <td><a  href="{{ url('/post/'.$c->post_id)}}" data-tooltip="Go to this post" target="_blank">{{Str::limit($c->post_title,30,"...")}}</a></td>

          <td>{{date('d.m.Y',strtotime($c->date))}}</td>
          <td>
            @if($c->visibility == 1)
              <form action="/post/change_comment_status" method="post" style="display:inline;">
                @csrf
                <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
                <input type="text" class="invisible "name="action" value="hide">
                <button class="button is-warning" data-tooltip="Hide this comment"><i class="fas fa-eye-slash"></i></button>
              </form>
            @else
            <form action="/post/change_comment_status" method="post" style="display:inline;">
              @csrf
              <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
              <input type="text" class="invisible "name="action" value="show">
              <button class="button is-success" data-tooltip="Show this comment"><i class="fas fa-eye"></i></button>
            </form>
            @endif

            @if($c->deleted == 0)
              <form action="/post/change_comment_status" method="post" style="display:inline;">
                @csrf
                <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
                <input type="text" class="invisible "name="action" value="delete">
                <button class="button is-warning" data-tooltip="Delete this comment"><i class="fas fa-ban"></i></button>
              </form>
            @else
              <form action="/post/change_comment_status" method="post" style="display:inline;">
                @csrf
                <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
                <input type="text" class="invisible "name="action" value="restore">
                <button class="button is-success" data-tooltip="Restore this comment"><i class="fas fa-check"></i></button>
              </form>
            @endif

            <button class="button is-danger showModalDelete" data-tooltip="Purge this comment" data-id="{{$c->id}}"><i class="fas fa-trash"></i></button>

          </td>
          </tr>
        @endforeach
      </tbody>
      </table>
    @else
	  	<table class="table is-hoverable">
			  <tbody>
				  @foreach($comments as $c)
					<tr>
						<td >
							<p><b>{{$c->username}}</b></p>
						
              <p style="word-wrap: break-word;"><a href="{{ url('/post/'.$c->post_id."#comment_anchor_".$c->id)}}" target="_blank" class="comment_link">
                @if($c->deleted == 0)
                    {!!$c->comment_content!!}
                @else
                  <s>{!!$c->comment_content!!}</s>
                @endif
              </a></p>
							<p style="margin-top:5px; font-size:10pt;"><a href="{{ url('/post/'.$c->post_id)}}" data-tooltip="Go to this post" target="_blank">{{Str::limit($c->post_title,30,"...")}}</a></p>
							<p style="margin-top:5px; font-size:10pt;
							">{{date('d.m.Y',strtotime($c->date))}}</p>
						
							<div class="buttons has-addons is-left" style="margin-top: 10px;">
								<p class="control">
									@if($c->visibility == 1)
										<form action="/post/change_comment_status" method="post" style="display:inline;">
										@csrf
										<input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
										<input type="text" class="invisible "name="action" value="hide">
										<button class="button is-warning is-small" data-tooltip="Hide this comment"><i class="fas fa-eye-slash"></i></button>
										</form>
									@else
									<form action="/post/change_comment_status" method="post" style="display:inline;">
										@csrf
										<input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
										<input type="text" class="invisible "name="action" value="show">
										<button class="button is-success is-small" data-tooltip="Show this comment"><i class="fas fa-eye"></i></button>
									</form>
									@endif
								</p>
								<p class="control">
                  @if($c->deleted == 0)
                    <form action="/post/change_comment_status" method="post" style="display:inline;">
                      @csrf
                      <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
                      <input type="text" class="invisible "name="action" value="delete">
                      <button class="button is-warning is-small" data-tooltip="Delete this comment"><i class="fas fa-ban"></i></button>
                    </form>
                  @else
                    <form action="/post/change_comment_status" method="post" style="display:inline;">
                      @csrf
                      <input type="text" name="comment_id" value="{{$c->id}}" class="invisible">
                      <input type="text" class="invisible "name="action" value="restore">
                      <button class="button is-success is-small" data-tooltip="Restore this comment"><i class="fas fa-check"></i></button>
                    </form>
                  @endif

                  <button class="button is-danger showModalDelete is-small" data-tooltip="Purge this comment" data-id="{{$c->id}}"><i class="fas fa-trash"></i></button>

								</p>
								
							  </div>
						</td>
					</tr>
				  @endforeach
			  </tbody>
		</table>
    @endif  
@endif
	</div>
</div>

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
      <p>Are you sure you want to purge (delete physically) this comment?</p>
      <b id="modal_post_title"></b>
      <p class="has-text-danger">This action cannot be undone.</p>
    </section>
    <footer class="modal-card-foot">
        <form id="modal_form" action="/post/change_comment_status" method="post" style="display:inline;">
              @csrf
              <input type="text" class="invisible" id="modal_form_input" name="comment_id">
              <input type="text" class="invisible "name="action" value="purge">
      </form>
      <button class="button is-danger" id="submit_modal">Delete</button>
      <button class="button cancel">Cancel</button>
    </footer>
  </div>
@endsection

@push('scripts')
<script src="{{ asset('js/custom/control_panel/comments.js') }}"></script>
@endpush