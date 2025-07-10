@extends('layouts.master')
@section('title', '28Movies Manager')

@section('content')
    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <!-- main title -->
                <div class="col-12">
                    <div class="main__title">
                        <h2>Dashboard</h2>
                        <a href="{{route('movie.product.create')}}" class="main__title-link">add item</a>
                    </div>
                </div>
                <!-- end main title -->
                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Unique views this month</span>
                        <p>5 678</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M21.92,11.6C19.9,6.91,16.1,4,12,4S4.1,6.91,2.08,11.6a1,1,0,0,0,0,.8C4.1,17.09,7.9,20,12,20s7.9-2.91,9.92-7.6A1,1,0,0,0,21.92,11.6ZM12,18c-3.17,0-6.17-2.29-7.9-6C5.83,8.29,8.83,6,12,6s6.17,2.29,7.9,6C18.17,15.71,15.17,18,12,18ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z" />
                        </svg>
                    </div>
                </div>
                <!-- end stats -->
                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>Items added this month</span>
                        <p>172</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M10,13H4a1,1,0,0,0-1,1v6a1,1,0,0,0,1,1h6a1,1,0,0,0,1-1V14A1,1,0,0,0,10,13ZM9,19H5V15H9ZM20,3H14a1,1,0,0,0-1,1v6a1,1,0,0,0,1,1h6a1,1,0,0,0,1-1V4A1,1,0,0,0,20,3ZM19,9H15V5h4Zm1,7H18V14a1,1,0,0,0-2,0v2H14a1,1,0,0,0,0,2h2v2a1,1,0,0,0,2,0V18h2a1,1,0,0,0,0-2ZM10,3H4A1,1,0,0,0,3,4v6a1,1,0,0,0,1,1h6a1,1,0,0,0,1-1V4A1,1,0,0,0,10,3ZM9,9H5V5H9Z" />
                        </svg>
                    </div>
                </div>
                <!-- end stats -->

                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>New comments</span>
                        <p>2 573</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M8,11a1,1,0,1,0,1,1A1,1,0,0,0,8,11Zm4,0a1,1,0,1,0,1,1A1,1,0,0,0,12,11Zm4,0a1,1,0,1,0,1,1A1,1,0,0,0,16,11ZM12,2A10,10,0,0,0,2,12a9.89,9.89,0,0,0,2.26,6.33l-2,2a1,1,0,0,0-.21,1.09A1,1,0,0,0,3,22h9A10,10,0,0,0,12,2Zm0,18H5.41l.93-.93a1,1,0,0,0,.3-.71,1,1,0,0,0-.3-.7A8,8,0,1,1,12,20Z" />
                        </svg>
                    </div>
                </div>
                <!-- end stats -->
                <!-- stats -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stats">
                        <span>New reviews</span>
                        <p>1 021</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                        </svg>
                    </div>
                </div>
                <!-- end stats -->
                <!-- dashbox -->
                <div class="col-12 col-xl-12">
                   
                </div>
                <!-- end dashbox -->
            </div>
        </div>
    </main>
    <script>
        $('.choose-option').on('change', function(e){
            e.preventDefault();
            var url = $(this).val();
            $.ajax({
                    type: 'POST',
                    url: "",
                    data: {url:url},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    // contentType: false,
                    // processData: true,
                    success: (data) => {
                        
                        let movies = data.params.items.results; 
                        console.log(movies);
                        
                        let tbody = $('.dashbox__table-wrap tbody'); 
                        tbody.empty();

                        movies.forEach((movie, index) => {
                            let row = `
                                <tr>
                                    <td><div class="main__table-text">${movie.id}</div></td>
                                    <td>
                                        <div class="main__table-text">
                                            <a href="#">${movie.title}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="main__table-text">
                                            <img src="https://image.tmdb.org/t/p/w600_and_h900_bestv2/${movie.poster_path}" alt="${movie.title}" style="height: 200px;">
                                        </div>
                                    </td>
                                     <td>
                                        <div class="main__table-text">
                                            <img src="https://image.tmdb.org/t/p/w300${movie.backdrop_path}" alt="${movie.title}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="main__table-text main__table-text--rate">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z"/>
                                            </svg>
                                            ${movie.vote_average.toFixed(1)}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="main__table-text">
                                            <a href="#">${movie.release_date}</a>
                                        </div>
                                    </td>
                                </tr>
                            `;
                                tbody.append(row);
                        });
                    },
                    error: function(data) {
                       console.log(data);
                    }
                });
        });
        $('.save-movie').on('click', function(){
            const url = $('.choose-option').val();
            $.ajax({
                    type: 'POST',
                    url: "",
                    data: {url:url},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    // contentType: false,
                    // processData: true,
                    success: (data) => {
                        console.log(data);
                        
                    },
                    error: function(data) {
                       console.log(data);
                    }
                });
            
        });
    </script>
@stop
