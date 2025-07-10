@extends('layouts.master')
@section('title', 'Create Movie')
@section('content')
<style>
    label{
        color: #fff;
    }
</style>

<main class="main">
<div class="container-fluid">
    <div class="row">
        <!-- main title -->
        <div class="col-12">
            <div class="main__title">
                <h2>Add new item</h2>
            </div>
        </div>
        <!-- end main title -->
        <!-- form -->
        <div class="col-12">
            <form class="form-horizontal" id="admin-{{ $params['prefix'] }}-form" name="admin-{{ $params['prefix'] }}-form"
            enctype="multipart/form-data" method="POST" action="{{ route($params['prefix'] . '.' . $params['controller'] . '.store') }}">
                <input type="hidden" name="_method" value="POST">
                <div class="row">
                    <div class="col-12 col-md-5 form__cover">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-12">
                                <div class="form__img">
                                    <label for="form__img-upload">Upload cover (190 x 270)</label>
                                    <input id="form__img-upload" name="image_poster" type="file"
                                        accept=".png, .jpg, .jpeg" />
                                    <img id="form__img" src="#" alt=" " />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12">
                                <div class="form__group">
                                    <input type="text" class="form__input" name="name" id="name" placeholder="Title" required/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form__group">
                                    <input type="text" class="form__input" name="slug" id="slug" placeholder="Slug" required/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form__group">
                                    <textarea id="text" name="" class="form__textarea" placeholder="Description"></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" class="form__input" id="release_date" name="release_date" placeholder="Release release_date" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" class="form__input" id="runtime" name="runtime" placeholder="Running timed in minutes" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <select class="js-example-basic-single select2" id="quality" name="quality">
                                        <option value="FHD">FullHD</option>
                                        <option value="HD">HD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" class="form__input" name="age" placeholder="Age" />
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <select class="js-example-basic-multiple" id="country" name="" multiple="multiple">
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <select class="js-example-basic-multiple" id="genre" name="genre[]" multiple="multiple">
                                        @foreach ($params['genres'] as $genre)
                                        <option value="{{$genre['id']}}">{{$genre['name']}}</option>    
                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label class="color-label">Trailer</label>
                                    <input type="text" class="form__input" name="trailer" id="trailer" placeholder="Trailer" value="" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Season</label>
                                    <input type="text" class="form__input" name="season" id="season" placeholder="Season" value="" />
                                </div>
                            </div>
                           
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Episode_ total</label>
                                    <input type="text" class="form__input" name="episode_total" id="episode_total" placeholder="Episode_total" value="" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Rating</label>
                                    <input type="text" class="form__input" name="rating" id="rating" placeholder="Rating" value="" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Imdb</label>
                                    <input type="text" class="form__input" name="imdb" id="imdb" placeholder="Imdb" value="" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Tmdb</label>
                                    <input type="text" class="form__input" name="tmdb" id="tmdb" placeholder="Tmdb" value="" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <label>Hot</label>
                                    <select class="js-example-basic-single select2" id="hot" name="hot">
                                        <option value="0" >Không</option>
                                        <option value="1">Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form__gallery">
                                    <label id="gallery1" for="form__gallery-upload">Upload photos</label>
                                    <input data-name="#gallery1" id="form__gallery-upload" name="image_thumb" class="form__gallery-upload" type="file" accept=".png, .jpg, .jpeg" multiple />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <ul class="form__radio">
                            <li>
                                <span>Item type:</span>
                            </li>
                            <li>
                                <input id="type1" type="radio" name="type" value="single" checked="" />
                                <label for="type1">Movie</label>
                            </li>
                            <li>
                                <input id="type2" type="radio" name="type" value="series"/>
                                <label for="type2">TV Show</label>
                            </li>
                             <li>
                                <input id="type3" type="radio" name="type" value="hoathinh"/>
                                <label for="type3">Animate</label>
                            </li>
                        </ul>
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
<script>
    $(document).ready(function() {
        $('#admin-{{ $params['prefix'] }}-form').submit(function(e) {
            
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
                    setTimeout(() => {
                        location.reload();
                    }, "1000");
                },
                error: function(data) {
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
