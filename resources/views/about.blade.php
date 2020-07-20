@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="white-bg">
            @if($is_admin == true)
                <div>
                    <a href="/control/edit_about" class="button is-link">
                    <span class="icon is-small">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span>Edit About page</span>
                    </a>
                    <div class="is-divider"></div>
                </div>
            @endif
            <div class="content p_fix">
                {!!$content!!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/custom/about.js') }}"></script>
@endpush
