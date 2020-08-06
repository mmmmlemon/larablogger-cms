@extends('layouts.app')

@section('content')
@php
 $blank = ""; //empty character for value atrribute in inputs
@endphp
<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
          <li><a href="/control">Control panel</a></li>
          <li><a href="/control/categories" aria-current="page">Categories</a></li>
          <li class="is-active"><a href="/control/categories" aria-current="page">Add category</a></li>
        </ul>
      </nav>

    <div class="is-divider"></div>

    <div class="columns">
        <div class="column">
             <form action="/control/categories/add" method="POST" id="category_form">
                @csrf

                <div class="field">
                    <label class="label">Category name</label>
                    <div class="control">
                      <input class="input @error('category_name') is-danger @enderror"
                        name="category_name" type="text" maxlength="50" placeholder="Category name" id="title"
                        value="@if($errors->any()){{old('category_name')}}@else{{$blank}}@endif">
                    </div>
                    @error('category_name')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                </div>

                <div class="control">
                    <button class="button is-link @if(config('isMobile')) is-fullwidth @endif" id="save_category">
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
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script> 
<script src="{{ asset('js/custom/control_panel/create_category.js') }}"></script>
@endpush