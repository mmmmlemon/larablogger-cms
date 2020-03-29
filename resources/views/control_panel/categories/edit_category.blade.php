@extends('layouts.app')
@section('content')
<div class="container white-bg">
    <div class="column is-12">
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
        <div class="column">
        <form action="/control/categories/edit/{{$categ->id}}" method="POST">
                @csrf
                <div class="field">
                    <label class="label">Category name</label>
                    <div class="control">
                      <input class="input @error('category_name') is-danger @enderror"
                       name="category_name" type="text" placeholder="Category name"
                       value="@if($errors->any()){{old('category_name')}}@else {{$categ->category_name}} @endif">
                    </div>
                    @error('category_name')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                  </div>
                  <div class="control">
                    <button class="button is-link">Submit</button>
                  </div>
            </form>
        </div>
    </div>
</div>
@endsection