@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="white-bg">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                <li><a href="/control">Control panel</a></li>
                <li class="is-active"><a href="">Design</a></li>
                <li class="is-active"><a href="#" aria-current="page">Edit About page</a></li>
                </ul>
            </nav>
                    
            <a href="{{url()->previous()}}" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span>Back</span>
            </a>

            <div class="is-divider"></div>   
            
            <form action="/control/save_about" method="POST">
                @csrf
                <label class="label">About page contents</label>
                <textarea class="textarea" id="post_content" maxlength="700" name="about_content" placeholder="Write your post here">
                    {{$content}}
                </textarea>
                @error('about_content')
                    <p class="help is-danger"><b> {{ $message }}</b></p>
                @enderror
                <br>
                <button class="button is-link">
                    <span class="icon is-small">
                        <i class="fas fa-save"></i>
                    </span>
                    <span>Save</span>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/edit_about.js') }}"></script>
@endpush