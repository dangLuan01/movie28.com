@extends('layouts.master')
@section('title', 'Update Movie')
@section('content')
<style>
    label{
        color: #fff;
    }
</style>
<form class="form-horizontal" id="admin-{{ $params['prefix'] }}-form" name="admin-{{ $params['prefix'] }}-form"
        enctype="multipart/form-data" method="POST" action="{{ route($params['prefix'] . '.' . $params['controller'] . '.update', ['product' => $params['id']]) }}">
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
                                    <input id="form__img-upload" name="image_poster" type="file"
                                        accept=".png, .jpg, .jpeg" />
                                    <img id="form__img" src="{{'https://wsrv.nl/?url=' . $params['item']['images']['0']['path'] . $params['item']['images']['0']['image'] ?? ''}}" alt="{{$params['item']['origin_name'] ?? ''}}" />
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
                            <div class="col-12">
                                <div class="form__group">
                                    <input type="text" class="form__input" name="origin_name" id="origin_name" placeholder="Origin name" value="{{$params['item']['origin_name'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form__group">
                                    <textarea id="text" name="content" class="form__textarea" placeholder="Content">{{$params['item']['content']}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" class="form__input" id="release_date" name="release_date" placeholder="Release year" value="{{$params['item']['release_date'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" class="form__input" id="runtime" name="runtime" placeholder="Running timed in minutes" value="{{$params['item']['runtime'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <select class="js-example-basic-single select2" id="quality" name="quality">
                                        <option value="FullHD" {{$params['item']['quality'] == 'FHD' ? 'selected' : ''}}>FullHD</option>
                                        <option value="HD" {{$params['item']['quality'] == 'HD' ? 'selected' : ''}}>HD</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <input type="text" name="age" class="form__input" placeholder="Age" value="{{$params['item']['age'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form__group">
                                    <select class="js-example-basic-multiple" id="country" name="" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 ">
                                <div class="form__group">
                                    <select class="js-example-basic-multiple" id="genre" name="genre[]" multiple="multiple">
                                        @foreach ($params['genres'] as $genre)
                                        <option value="{{$genre['id']}}" {{ $params['item']['genres']->contains($genre['id']) == true ? 'selected' : '' }}>{{$genre['name']}}</option>    
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Trailer</label>
                                    <input type="text" class="form__input" name="trailer" id="trailer" placeholder="Trailer" value="{{$params['item']['trailer'] ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Season</label>
                                    <input type="text" class="form__input" name="season" id="season" placeholder="Season" value="{{$params['item']['season'] ?? ''}}" />
                                </div>
                            </div>
                           
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Episode_ total</label>
                                    <input type="text" class="form__input" name="episode_total" id="episode_total" placeholder="Episode_total" value="{{$params['item']['episode_total'] ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Rating</label>
                                    <input type="text" class="form__input" name="rating" id="rating" placeholder="Rating" value="{{$params['item']['rating'] ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Imdb</label>
                                    <input type="text" class="form__input" name="imdb" id="imdb" placeholder="Imdb" value="{{$params['item']['imdb'] ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form__group">
                                    <label>Tmdb</label>
                                    <input type="text" class="form__input" name="tmdb" id="tmdb" placeholder="Tmdb" value="{{$params['item']['tmdb'] ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form__group">
                                    <label>Hot</label>
                                    <select class="js-example-basic-single select2" id="hot" name="hot">
                                        <option value="0" {{$params['item']['hot'] == null || $params['item']['hot'] == '0' ? 'selected' : ''}}>Không</option>
                                        <option value="1" {{$params['item']['hot'] == '1' ? 'selected' : ''}}>Có</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form__gallery">
                                    <label id="gallery1" for="form__gallery-upload">Upload thumbnail</label>
                                    <input id="form__gallery-upload" name="image_thumb" class="form__gallery-upload" type="file" accept=".png, .jpg, .jpeg"/>
                                </div>
                                <input type="text" class="form__input"value="{{$params['item']['images']['1']['path'] . $params['item']['images']['1']['image']}}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <ul class="form__radio">
                            <li>
                                <span>Item type:</span>
                            </li>
                            <li>
                                <input id="type1" type="radio" name="type" value="single" {{$params['item']['type'] == 'single' ? 'checked' : ''}}/>
                                <label for="type1">Movie</label>
                            </li>
                            <li>
                                <input id="type2" type="radio" name="type" value="series" {{$params['item']['type'] == 'series' ? 'checked' : ''}}/>
                                <label for="type2">TV Show</label>
                            </li>
                             <li>
                                <input id="type2" type="radio" name="type" value="series" {{$params['item']['type'] == 'hoathinh' ? 'checked' : ''}}/>
                                <label for="type2">Animate</label>
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
</form>
<script>
    $(document).ready(function() {
        $('#admin-{{ $params['prefix'] }}-form').submit(function(e) {
            
            // showLoadding();
            // $('.input-error').html('');
            // $('.form-group row p-0 m-0 mb-2 input').removeClass('is-invalid');
            $('.spinner').show();
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route($params['prefix'] . '.' . $params['controller'] . '.update', ['product' => $params['id']]) }}",
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
