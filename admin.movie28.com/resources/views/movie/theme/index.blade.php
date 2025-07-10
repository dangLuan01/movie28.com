@extends('layouts.master')
@section('title', 'List Theme')
@section('content')
@php
    $model = $params['model']
@endphp
<!-- main content -->
<main class="main">
    <div class="container-fluid">
        <div class="row">
            <!-- main title -->
            <div class="col-12">
                <div class="main__title">
                    <h2>List Theme</h2>
                    <span class="main__title-stat">{{$model['total']}} total</span>
                    <a href="{{route('movie.theme.create')}}" class="main__title-link">add item</a>
                </div>
            </div>
            <!-- end main title -->
            <!-- users -->
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>LAYOUT</th>
                                <th>PRIORITY</th>
                                <th>MOVIE LIMIT</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($model['items'] as $theme)
                            <tr>
                                <td>
                                    <div class="main__table-text">{{$theme['id']}}</div>
                                </td>
                                <td>
                                    <div class="main__table-text">
                                        <a href="#">{{$theme['name']}}</a>
                                    </div>
                                </td>
                                <td>
                                    <div class="main__table-text">
                                        {{ $theme['layout'] == 1 ? 'Paginate' : 'Owl-Carousel' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="main__table-text n">
                                        {{$theme['priority']}}
                                    </div>
                                </td>
                                 <td>
                                    <div class="main__table-text n">
                                        {{$theme['limit']}}
                                    </div>
                                </td>
                                <td>
                                    @if ($theme['status'] != 0)
                                    <div class="main__table-text main__table-text--green">
                                        Visible
                                    </div>
                                    @else  
                                    <div class="main__table-text main__table-text--red">
                                        Hidden
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="main__table-btns">
                                        <a href="{{route('movie.theme.edit', ['theme' => $theme['id']])}}" class="main__table-btn main__table-btn--edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M22,7.24a1,1,0,0,0-.29-.71L17.47,2.29A1,1,0,0,0,16.76,2a1,1,0,0,0-.71.29L13.22,5.12h0L2.29,16.05a1,1,0,0,0-.29.71V21a1,1,0,0,0,1,1H7.24A1,1,0,0,0,8,21.71L18.87,10.78h0L21.71,8a1.19,1.19,0,0,0,.22-.33,1,1,0,0,0,0-.24.7.7,0,0,0,0-.14ZM6.83,20H4V17.17l9.93-9.93,2.83,2.83ZM18.17,8.66,15.34,5.83l1.42-1.41,2.82,2.82Z" />
                                            </svg>
                                        </a>
                                        <a href="{{route('movie.theme.status', ['status' => $theme['status'], 'id' => $theme['id']])}}"
                                            class="main__table-btn main__table-btn--banned">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12,13a1.49,1.49,0,0,0-1,2.61V17a1,1,0,0,0,2,0V15.61A1.49,1.49,0,0,0,12,13Zm5-4V7A5,5,0,0,0,7,7V9a3,3,0,0,0-3,3v7a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V12A3,3,0,0,0,17,9ZM9,7a3,3,0,0,1,6,0V9H9Zm9,12a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V12a1,1,0,0,1,1-1H17a1,1,0,0,1,1,1Z" />
                                            </svg>
                                        </a>
                                        <a href="#modal-delete"
                                            class="main__table-btn main__table-btn--delete open-modal">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18ZM20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A1,1,0,0,0,4,8H5V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V8h1a1,1,0,0,0,0-2ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10Zm7,14a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V8H17Zm-3-1a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end users -->
            <!-- paginator -->
            {{ $model['items']->appends(request()->query())->links('pagination::default') }}
            <!-- end paginator -->
        </div>
    </div>
</main>
<!-- end main content -->
<!-- modal status -->
{{-- <div id="modal-status" class="zoom-anim-dialog mfp-hide modal">
    <h6 class="modal__title">Status change</h6>
    <p class="modal__text">Are you sure about immediately change status?</p>
    <div class="modal__btns">
        <a href="" class="modal__btn modal__btn--apply" type="button">
            Apply
        </a>
        <button class="modal__btn modal__btn--dismiss" type="button">
            Dismiss
        </button>
    </div>
</div> --}}
<!-- end modal status -->
 <!-- modal delete -->
<div id="modal-delete" class="zoom-anim-dialog mfp-hide modal">
    <h6 class="modal__title">Item delete</h6>
    <p class="modal__text">Are you sure to permanently delete this item?</p>
    <div class="modal__btns">
        <button class="modal__btn modal__btn--apply" type="button">
            Delete
        </button>
        <button class="modal__btn modal__btn--dismiss" type="button">
            Dismiss
        </button>
    </div>
</div>
<!-- end modal delete -->
@stop
