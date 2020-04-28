@extends('layouts.app')
@section('content')

<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li class="is-active"><a href="#" aria-current="page">Categories</a></li>
        </ul>
      </nav>
    <div class="column is-12">
        <a href="{{url()->previous()}}" class="button is-link">
            <span class="icon">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span>
             Back
            </span>
        </a>

        <a href="/control/categories/add" class="button is-link">
            <span class="icon">
                <i class="fas fa-plus"></i>
            </span>
            <span>
            Add new category
            </span>
        </a>
    </div>

    <div class="is-divider"></div>
    <div class="columns">
     
        <div class="column">
            <table class="table is-hovered is-fullwidth">
                <thead>
                    <th>Name</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach($categs as $categ)
                        <tr>
                            <td>
                                {{$categ->category_name}}
                            </td>
                            <td>
                                <a href="/control/categories/edit/{{$categ->id}}" class="button is-info">
                                    <span class="icon is-small" data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                </a>
                                <button class="button is-danger showModalDelete" 
                        data-tooltip="Delete this post" data-title="{{$categ->category_name}}" data-id="{{$categ->id}}">
                            <i class="fas fa-trash"></i>
                        </button>
                              
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
        <p>Are you sure you want to delete this category?</p>
        <b id="modal_post_title"></b>
        <p class="has-text-danger">This action cannot be undone.</p>
      </section>
      <footer class="modal-card-foot">
          <form id="modal_form" action="/control/categories/delete/" method="post" style="display:inline;">
                @method('DELETE')
                @csrf
                <input type="text" class="invisible" id="modal_form_input" name="modal_form_input">
        </form>
        <button class="button is-danger" id="submit_modal">Delete</button>
        <button class="button cancel">Cancel</button>
      </footer>
    </div>
  </div>>

@endsection

@push('scripts')
<script src="{{ asset('js/custom/control_panel/categories.js') }}"></script>
@endpush