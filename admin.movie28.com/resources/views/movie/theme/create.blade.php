@extends('layouts.master')
@section('title', 'Create Theme')
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
                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12">
                                <div class="form__group">
                                    <label>Name</label>
                                    <input type="text" class="form__input slug" name="name" id="name" placeholder="Enter theme name" required/>
                                </div>
                            </div>   
                            
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <label>Country</label>
                                    <select class="js-example-basic-multiple select2" id="country_id" name="country_id">
                                        <option value="">Choose a country</option>
                                        @foreach ($params['countries'] as $country)
                                        <option value="{{$country['id']}}">{{ $country['name'] }}</option>
                                        @endforeach                                     
                                    </select>
                                </div>
                            </div>     
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <label>Genre</label>
                                    <select class="js-example-basic-multiple select2" id="genre_id" name="genre_id">
                                        <option value="">Choose a genre</option>
                                        @foreach ($params['genres'] as $genre)
                                        <option value="{{$genre['id']}}">{{ $genre['name'] }}</option>
                                        @endforeach                                
                                    </select>
                                </div>
                            </div>    
                            <div class="col-12 col-lg-6">
                                 <div class="form__group">
                                    <label>Type</label>
                                    <select class="js-example-basic-multiple select2" id="type" name="type">
                                        <option value="" selected>All type</option>
                                        @foreach ($params['data']['type'] as $key => $type)
                                        <option value="{{$type}}">{{$key}}</option>          
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-12 col-lg-6">
                                 <div class="form__group">
                                    <label>Year</label>
                                    <input type="text" class="form__input" name="year" id="year" placeholder="Enter year" />
                                </div>
                            </div>    
                            <div class="col-12 col-lg-6">
                                 <div class="form__group">
                                    <label>Layout</label>
                                    <select class="js-example-basic-multiple select2" id="layout" name="layout">
                                        @foreach ($params['data']['layout'] as $key => $layout)
                                        <option value="{{$layout}}">{{$key}}</option>          
                                        @endforeach
                                    </select>
                                </div>
                            </div>                        
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <label>Priority</label>
                                    <input type="text" class="form__input" name="priority" id="priority" placeholder="Default 9999" value="9999" />
                                </div>
                            </div> 
                            <div class="col-12 col-lg-6">
                                 <div class="form__group">
                                    <label>Limit</label>
                                    <input type="number" class="form__input" name="limit" id="limit" placeholder="Enter limit" value="12" required/>
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
