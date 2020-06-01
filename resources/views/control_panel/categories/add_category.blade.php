@extends('layouts.app')

@section('content')
@php
 $blank = ""; //пустой символ для атрибута value, в input   
@endphp
<div class="container white-bg">
    <!--НАВИГАЦИЯ-->
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li><a href="/control/categories" aria-current="page">Categories</a></li>
          <li class="is-active"><a href="/control/categories" aria-current="page">Add category</a></li>
        </ul>
      </nav>
    <!--КНОПКА BACK-->
    <div class="column is-12">
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
            <!--ФОРМА-->
            <form action="/control/categories/add" method="POST" id="category_form">
                @csrf

                <!--НАЗВАНИЕ КАТЕГОРИИ-->
                <div class="field">
                    <label class="label">Category name</label>
                    <div class="control">
                      <input class="input @error('category_name') is-danger @enderror"
                        name="category_name" type="text" maxlength="20" placeholder="Category name" id="title"
                        value="@if($errors->any()){{old('category_name')}}@else{{$blank}}@endif">
                    </div>
                    @error('category_name')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                </div>
                
                <!--КНОПКА ОТПРАВКИ-->
                <div class="control">
                    <button class="button is-link" id="save_category">
                        <span class="icon is-small">
                            <i class="fas fa-arrow-right"></i>
                          </span>
                          <span>Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!--счетчик символов-->
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script> 
<!--скрипты для этой страницы-->
<script src="{{ asset('js/custom/control_panel/create_category.js') }}"></script>
@endpush