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
                                <a href="/category/delete/{{$categ->id}}" class="button is-danger">
                                    <span class="icon is-small" data-tooltip="Delete this category">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection