@extends('layouts.master')
@section('title', 'Create Collection')
@section('content')
<style>
    label{
        color: #fff;
    }
</style>
<form class="form-horizontal" id="admin-{{ $params['prefix'] }}-form" name="admin-{{ $params['prefix'] }}-form"
        enctype="multipart/form-data" method="POST" action="{{ route($params['prefix'] . '.' . $params['controller'] . '.store') }}">
<main class="main">
<div class="container-fluid">
    <div class="row">
        <!-- main title -->
        <div class="col-12">
            <div class="main__title">
                <h2>Create item</h2>
            </div>
        </div>
        <!-- end main title -->
        <!-- form -->
        <div class="col-12">
            <form action="#" class="form">
                <div class="row">
                    <div class="col-12 col-md-5 form__cover">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-12">
                                <div class="form__img">
                                    <label for="form__img-upload">Upload cover</label>
                                    <input id="form__img-upload" name="image" type="file"
                                        accept=".png, .jpg, .jpeg" />
                                         <img id="form__img" src="#" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12">
                                <div class="form__group">
                                    <label>Name</label>
                                    <input type="text" class="form__input slug" name="name" id="name" placeholder="Collection Name" value="" />
                                </div>
                            </div>   
                            <div class="col-12">
                                <div class="form__group">
                                    <label>Slug</label>
                                    <input type="text" class="form__input" name="slug" id="slug" placeholder="Slug" value="" />
                                </div>
                            </div> 
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <label>Choose Movie</label>
                                    <select class="js-example-basic-multiple select2" id="movie" name="movie_id[]" multiple="multiple">
                                        @foreach ($params['movie'] as $movie)
                                        <option value="{{$movie['id']}}">{{$movie['name']}}</option>    
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                             <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <label>Priority</label>
                                    <input type="text" class="form__input" name="priority" id="priority" placeholder="Default 9999" value="" />
                                </div>
                            </div> 
                            <div class="col-5 col-lg-6">
                                <div class="form__group">
                                    <label>Status</label>
                                    <select class="js-example-basic-single select2" id="status" name="status">
                                        <option value="0" >Hidden</option>
                                        <option value="1" selected>Active</option>
                                    </select>
                                </div>  
                            </div>
                                                      
                        </div>
                    </div>
                   
                    <div class="col-12">
                        <button type="submit" class="form__btn">publish</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- end form -->
    </div>
</div>
</main>
</form>
<script>
    $(document).ready(function() {
        $('#admin-{{ $params['prefix'] }}-form').submit(function(e) {
            $('.spinner').show();
            // showLoadding();
            // $('.input-error').html('');
            // $('.form-group row p-0 m-0 mb-2 input').removeClass('is-invalid');
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route($params['prefix'] . '.' . $params['controller'] . '.store') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    // hideLoadding();
                    // toastr.success(data.message);
                   $('.spinner').hide();
                    setTimeout(() => {
                         location.reload();
                    }, "500");
                },
                error: function(data) {
                    $('.spinner').hide();
                    // hideLoadding();

                    // for (x in data.responseJSON.errors) {
                    //     $('#' + x).parents('.form-group').find('.input-error').html(data
                    //         .responseJSON.errors[x]);
                    //     $('#' + x).parents('.form-group').find('.input-error').show();
                    //     $('#' + x).addClass('is-invalid');
                    // }
                }
            });
        });

    });
</script>
@stop
