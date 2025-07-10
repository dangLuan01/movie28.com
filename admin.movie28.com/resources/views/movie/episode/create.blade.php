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
                <h2>Add new episode</h2>
            </div>
        </div>
        <!-- end main title -->
        <!-- form -->
        <div class="col-12">
            <form class="form-horizontal" id="admin-{{ $params['prefix'] }}-form" name="admin-{{ $params['prefix'] }}-form"
            enctype="multipart/form-data" method="POST" action="{{ route($params['prefix'] . '.' . $params['controller'] . '.store') }}">
                <input type="hidden" name="_method" value="POST">
                <div class="row">
                    <div class="col-12 col-md-7 form__content">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <label>Movie</label>
                                <div class="form__group">
                                    <select class="js-example-basic-multiple select2" id="movie" name="movie_id" multiple="multiple">
                                        <option value="" disabled>Choose movie</option>
                                        @foreach ($params['movie'] as $movie)
                                            <option value="{{ $movie['id'] }}">{{ $movie['name'] }}</option>
                                        @endforeach                                           
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form__group">
                            <label>Server</label>
                            <select class="js-example-basic-single episode" id="server_id" name="server_id">
                                @foreach ($params['server'] as $server)
                                    <option value={{$server['id']}}>{{$server['name']}}</option>     
                                @endforeach
                               
                            </select>
                        </div>
                    </div>
                    <div class="col12 col-sm-12"></div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="form__group">
                            <label>Episode</label>
                            <select class="js-example-basic-single" id="episode" name="episodes[episode][]">
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form__group">
                            <label>Link</label>
                            <input type="text" class="form__input" id="hls" name="episodes[hls][]" placeholder="Link url" value="" />
                        </div>
                    </div>
                    <div class="col-1">
                        <label></label>
                        <button type="button" class="form__btn" id="add-episode">Add</button>
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
<script>
    $(document).ready(function() {
        let episodeOptions = '';
        function loadEpisodes(count) {
            episodeOptions = '';
            if (count != 1) {
                for (let i = 1; i <= parseInt(count); i++) {
                    episodeOptions += `<option value="${i}">Tập ${i}</option>`;
                }
            } else {
                episodeOptions += `<option value="FHD">Full HD</option>
                                   <option value="HD">HD</option>`;
            }
            $('#episode').each(function() {
                if ($(this).children('option').length === 0) {
                    $(this).html(episodeOptions);
                }
            });
        }
        $('#add-episode').on('click', function() {
            const episodeGroup = `
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="form__group">
                        <label>Episode</label>
                        <select class="js-example-basic-single episode-select form__input" name="episodes[episode][]">
                            ${episodeOptions}
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form__group">
                        <label>Link</label>
                        <input type="text" class="form__input" name="episodes[hls][]" placeholder="Link url" value="" />
                    </div>
                </div>
                <div class="col-1">
                    <label></label>
                    <button type="button" class="form__btn remove-episode">X</button>
                </div>
            `;
            $(this).closest('.col-1').after(episodeGroup);
            $('.js-example-basic-single').select2();
        });

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
        $('#movie').change(function(){
            const movieId = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route($params['prefix'] . '.' . $params['controller'] . '.get-episode') }}",
                data: {
                    movie_id: movieId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#episode').empty();
                    loadEpisodes(response.data);
                }
            });
        })
        $(document).on('click', '.remove-episode', function() {
            $(this).closest('.col-1').prev().remove(); 
            $(this).closest('.col-1').prev().remove(); 
            $(this).closest('.col-1').remove();     
        });
    });
    

</script>
@stop
