@extends('layouts.app')

@section('content')
<div class="container white-bg">
  {{-- НАВИГАЦИЯ --}}
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li><a href="/control/categories" aria-current="page">Categories</a></li>
          <li class="is-active"><a href="/control/categories" aria-current="page">Edit category</a></li>
          <li class="is-active"><a href="/control/categories" aria-current="page">{{$categ->category_name}}</a></li>
        </ul>
    </nav>

    <div class="column is-12">
        {{-- КНОПКА BACK  --}}
        <a href="/control/categories" class="button is-link">
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
        <div class="column">
        {{-- ФОРМА --}}
        <form action="/control/categories/edit/{{$categ->id}}" method="POST">
                @csrf

                {{-- наименование категории --}}
                <div class="field">
                    <label class="label">Category name</label>
                    <div class="control">
                      <input maxlength="20" class="input @error('category_name') is-danger @enderror"
                       name="category_name" id="title" type="text" placeholder="Category name"
                       value="@if($errors->any()){{old('category_name')}}@else{{$categ->category_name}}@endif">
                    </div>
                    @error('category_name')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                  </div>

                  {{-- кнопка отправки --}}
                  <div class="control">
                    <button class="button is-link">
                      <span class="icon is-small">
                        <i class="fas fa-save"></i>
                      </span>
                      <span>Save changes</span>
                    </button>
                  </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- счетчик символов --}}
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
{{-- скрипты для этой страницы --}}
<script src="{{ asset('js/custom/control_panel/create_category.js') }}"></script>
@endpush