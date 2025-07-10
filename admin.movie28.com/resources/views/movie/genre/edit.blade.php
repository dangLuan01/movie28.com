@extends('layouts.master')
@section('title', 'Update Genre')
@section('content')
<style>
    label{
        color: #fff;
    }
</style>
<form class="form-horizontal" id="admin-{{ $params['prefix'] }}-form" name="admin-{{ $params['prefix'] }}-form"
        enctype="multipart/form-data" method="POST" action="{{ route($params['prefix'] . '.' . $params['controller'] . '.update', ['genre' => $params['id']]) }}">
<input type="hidden" name="_method" value="PUT">
<input type="hidden" name="id" value="{{$params['id']}}">
<main class="main">
<div class="container-fluid">
    <div class="row">
        <!-- main title -->
        <div class="col-12">
            <div class="main__title">
                <h2>Update item</h2>
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
                                    <img id="form__img" src="{{'https://wsrv.nl/?url=' . $params['item']['image'] ?? ''}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12">
                                <div class="form__group">
                                    <input type="text" class="form__input" name="name" id="name" placeholder="name" value="{{$params['item']['name'] ?? ''}}" />
                                    <input type="hidden" name="slug" id="slug" value="{{$params['item']['slug'] ?? ''}}" />
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="form__btn">publish</button>
                    </div>
                        {{-- <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form__video">
                                        <label id="movie1" for="form__video-upload">Upload video</label>
                                        <input data-name="#movie1" id="form__video-upload" name="movie"
                                            class="form__video-upload" type="file"
                                            accept="video/mp4,video/x-m4v,video/*" />
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form__group form__group--link">
                                        <input type="text" class="form__input" placeholder="or add a link" />
                                    </div>
                                </div>
                                
                            </div>
                        </div> --}}
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
                url: "{{ route($params['prefix'] . '.' . $params['controller'] . '.update', ['genre' => $params['id']]) }}",
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
