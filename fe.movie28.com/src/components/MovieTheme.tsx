import { useEffect, useState, useRef, useCallback, Fragment } from 'react';
import OwlCarousel from 'react-owl-carousel';
type Movie = {
  name: string;
  origin_name: string;
  slug: string;
  image: { poster: string };
  type: string;
  release_date: number;
  rating: number;
  genres: { name: string }[];
};

type ThemeData = {
  theme: {
    id: number;
    name: string;
    genre_id: number;
    type: string;
    limit: number;
    layout: number;
  };
  movies_of_theme: {
    movies: Movie[];
    paginate: {
      page: number;
      page_size: number;
      total_pages: number;
    }
  };
};

export default function MovieThemeLoader() {
  const THEMES_PER_LOAD                         = 2;
  const [allThemes, setAllThemes]               = useState<ThemeData[]>([]);
  const [visibleThemes, setVisibleThemes]       = useState<ThemeData[]>([]);
  const [currentThemePage, setCurrentThemePage] = useState(1);
  const [totalThemePages, setTotalThemePages]   = useState(1);
  const [loadingThemes, setLoadingThemes]       = useState(false);
  const observerTarget                          = useRef<HTMLDivElement>(null);
  const carouselRefs                            = useRef<(OwlCarousel | null)[]>([]);
  const API_URL                                 = import.meta.env.PUBLIC_API_GO_URL;
  const apiKey                                  = import.meta.env.PUBLIC_API_KEY
  
  // Fetch themes
  const fetchThemes = useCallback(async (page: number) => {
    if (loadingThemes || page > totalThemePages) return;
    
    setLoadingThemes(true);
    try {
      const res = await fetch(`${API_URL}/api/v1/theme?page_theme=${page}&limit=${THEMES_PER_LOAD}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'x-api-key': apiKey
        }
      });
      
      const results = await res.json();
      const result = results.data
      
      setAllThemes(prev => [...prev, ...result.data_themes]);
      
      setTotalThemePages(result.paginate.total_pages);
      setCurrentThemePage(page);
    } catch (error) {
        console.error('Failed to fetch themes:', error);
    } finally {
        setLoadingThemes(false);
    }
  }, [loadingThemes, totalThemePages, API_URL]);

  // Load more movies for a specific theme
  const fetchMoreMovies = async (themeId: number, currentPage: number) => {
    try {
      const res = await fetch(`${API_URL}/api/v1/theme?id=${themeId}&page_movie=${currentPage + 1}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'x-api-key': apiKey
        }
      });
      if (!res.ok) throw new Error('Network response was not ok');
      const results = await res.json();
      const result = results.data
      
      setAllThemes(prev => 
        prev.map(theme => 
          theme.theme.id === themeId 
            ? { 
                ...theme, 
                movies_of_theme: {
                  ...theme.movies_of_theme,
                  movies: [
                    ...theme.movies_of_theme.movies,
                    ...result.data_themes[0].movies_of_theme.movies
                  ],
                  paginate: {
                    ...theme.movies_of_theme.paginate,
                    page: currentPage + 1
                  }
                }
              } 
            : theme
        )
      );
    } catch (error) {
      console.error('Failed to fetch more movies:', error);
    }
  };

  // Initialize
  useEffect(() => {
    fetchThemes(1);
  }, []);

  // Update visible themes when allThemes changes
  useEffect(() => {
    setVisibleThemes(allThemes.slice(0, currentThemePage * THEMES_PER_LOAD));
  }, [allThemes, currentThemePage]);

  // Setup intersection observer for infinite scroll
  useEffect(() => {
    const observer = new IntersectionObserver(
      entries => {
        if (entries[0].isIntersecting && !loadingThemes && currentThemePage < totalThemePages) {
          fetchThemes(currentThemePage + 1);
        }
      },
      { threshold: 0.1 }
    );

    if (observerTarget.current) {
      observer.observe(observerTarget.current);
    }

    return () => {
      if (observerTarget.current) {
        observer.unobserve(observerTarget.current);
      }
    };
  }, [currentThemePage, totalThemePages, loadingThemes, fetchThemes]);
  const options = {
    mouseDrag: true,
    touchDrag: true,
    dots: true,
    loop: true,
    autoplay: false,
    smartSpeed: 600,
    margin: 20,
    responsive: {
      0: {
        items: 2,
      },
      576: {
        items: 3,
      },
      768: {
        items: 3,
        margin: 30,
      },
      992: {
        items: 4,
        margin: 30,
      },
      1200: {
        items: 6,
        margin: 30,
        dots: false,
        mouseDrag: true,
        slideBy: 6,
        smartSpeed: 400,
      },
    },
  };
  
  // const handleNext = () => {
  //   if (carouselRef.current) {
  //     carouselRef.current.next([300,350]);
  //   }
  // };
  // const handlePrev = () => {
  //   if (carouselRef.current) {
  //     carouselRef.current.prev([350,400]);
  //   }
  // };
  const handleNext = (index: number) => {
  if (carouselRefs.current[index]) {
      carouselRefs.current[index]?.next([300, 350]);
    }
  };

  const handlePrev = (index: number) => {
    if (carouselRefs.current[index]) {
        carouselRefs.current[index]?.prev([350, 400]);
    }
  };
  return (
    <>
      {visibleThemes.map((items, index) => (
        <Fragment key={items.theme.id}>
        {items.theme.layout === 1 ? (
        <div key={items.theme.id} className="catalog">
          <div className="container">
            <div className="row">
              <div className="col-12 col-xl-6">
                <h1 className="section__title section__title--head">
                  {items.theme.name}
                </h1>
              </div>
              <div className="col-12">
                <div className="row row--grid">
                  {items.movies_of_theme.movies.map((movie) => (
                    <div key={movie.slug} className="col-6 col-sm-4 col-lg-3 col-xl-2">
                      <div className="card" key={movie.slug}>
                        <a href={ movie.type === 'single' ? '/movie/' + movie.slug : '/tv-series/' + movie.slug } className="card__cover">
                          <img src={ 'https://wsrv.nl/?url=' + movie.image.poster + '&format=webp&quality=50&output=webp' } alt={movie.origin_name} loading='lazy' decoding='async'/>
                          <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd" clipRule="evenodd" d="M11 1C16.5228 1 21 5.47716 21 11C21 16.5228 16.5228 21 11 21C5.47716 21 1 16.5228 1 11C1 5.47716 5.47716 1 11 1Z" strokeLinecap="round" strokeLinejoin="round"/>
                            <path fillRule="evenodd" clipRule="evenodd" d="M14.0501 11.4669C13.3211 12.2529 11.3371 13.5829 10.3221 14.0099C10.1601 14.0779 9.74711 14.2219 9.65811 14.2239C9.46911 14.2299 9.28711 14.1239 9.19911 13.9539C9.16511 13.8879 9.06511 13.4569 9.03311 13.2649C8.93811 12.6809 8.88911 11.7739 8.89011 10.8619C8.88911 9.90489 8.94211 8.95489 9.04811 8.37689C9.07611 8.22089 9.15811 7.86189 9.18211 7.80389C9.22711 7.69589 9.30911 7.61089 9.40811 7.55789C9.48411 7.51689 9.57111 7.49489 9.65811 7.49789C9.74711 7.49989 10.1091 7.62689 10.2331 7.67589C11.2111 8.05589 13.2801 9.43389 14.0401 10.2439C14.1081 10.3169 14.2951 10.5129 14.3261 10.5529C14.3971 10.6429 14.4321 10.7519 14.4321 10.8619C14.4321 10.9639 14.4011 11.0679 14.3371 11.1549C14.3041 11.1999 14.1131 11.3999 14.0501 11.4669Z" strokeLinecap="round" strokeLinejoin="round"/>
                          </svg>
                        </a>
                        <button className="card__add" type="button">
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
                          </svg>
                        </button>
                        <span className="card__rating">
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                          </svg>
                          {movie.rating}
                        </span>
                        <h2 className="card__title">
                          <a href={ movie.type === 'single' ? '/movie/' + movie.slug : '/tv-series/' + movie.slug }>
                            {movie.name}
                          </a>
                        </h2>
                        <ul className="card__list">
                          <li>Free</li>
                          <li>{movie.genres[0]?.name || 'Unknown'}</li>
                          <li>{movie.release_date}</li>
                        </ul>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
              <div className="row">
                <div className="col-12">
                  {items.movies_of_theme.paginate.page < items.movies_of_theme.paginate.total_pages && (
                    <button
                      className="catalog__more"
                      type="button"
                      onClick={() => fetchMoreMovies(items.theme.id, items.movies_of_theme.paginate.page)}>
                      Load more
                    </button>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
        ):(
        <div className="catalog" key={items.theme.id}>
          <div className="container">
            <div className="row">
              <div className="col-12">
                <h1 className="section__title">{items.theme.name}</h1>
              </div>
              <div className="col-12">
                <div className="section__carousel-wrap">
                    <OwlCarousel className="" {...options} ref={(el) => {carouselRefs.current[index] = el;}}>
                       {items.movies_of_theme.movies.map((movie) => (
                        <div className="card" key={movie.slug}>
                          <a href={ movie.type === 'single' ? '/movie/' + movie.slug : '/tv-series/' + movie.slug } className="card__cover">
                            <img src={ 'https://wsrv.nl/?url=' + movie.image.poster + '&format=webp&quality=50&output=webp' } alt={movie.origin_name} loading='lazy' decoding='async'/>
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fillRule="evenodd" clipRule="evenodd" d="M11 1C16.5228 1 21 5.47716 21 11C21 16.5228 16.5228 21 11 21C5.47716 21 1 16.5228 1 11C1 5.47716 5.47716 1 11 1Z" strokeLinecap="round" strokeLinejoin="round"/>
                              <path fillRule="evenodd" clipRule="evenodd" d="M14.0501 11.4669C13.3211 12.2529 11.3371 13.5829 10.3221 14.0099C10.1601 14.0779 9.74711 14.2219 9.65811 14.2239C9.46911 14.2299 9.28711 14.1239 9.19911 13.9539C9.16511 13.8879 9.06511 13.4569 9.03311 13.2649C8.93811 12.6809 8.88911 11.7739 8.89011 10.8619C8.88911 9.90489 8.94211 8.95489 9.04811 8.37689C9.07611 8.22089 9.15811 7.86189 9.18211 7.80389C9.22711 7.69589 9.30911 7.61089 9.40811 7.55789C9.48411 7.51689 9.57111 7.49489 9.65811 7.49789C9.74711 7.49989 10.1091 7.62689 10.2331 7.67589C11.2111 8.05589 13.2801 9.43389 14.0401 10.2439C14.1081 10.3169 14.2951 10.5129 14.3261 10.5529C14.3971 10.6429 14.4321 10.7519 14.4321 10.8619C14.4321 10.9639 14.4011 11.0679 14.3371 11.1549C14.3041 11.1999 14.1131 11.3999 14.0501 11.4669Z" strokeLinecap="round" strokeLinejoin="round"/>
                            </svg>
                          </a>
                          <button className="card__add" type="button">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 24 24">
                              <path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
                            </svg>
                          </button>
                          <span className="card__rating">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 24 24">
                              <path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                            </svg>
                            {movie.rating}
                          </span>
                          <h2 className="card__title">
                            <a href={ movie.type === 'single' ? '/movie/' + movie.slug : '/tv-series/' + movie.slug }>
                              {movie.name}
                            </a>
                          </h2>
                          <ul className="card__list">
                            <li>Free</li>
                            <li>{movie.genres[0]?.name || 'Unknown'}</li>
                            <li>{movie.release_date}</li>
                          </ul>
                        </div>
                      ))};
                     
                    </OwlCarousel>
                  {/* <div className="section__carousel owl-carousel" id="subscriptions">
                    
                  </div> */}
                  <button onClick={() => handlePrev(index)}  className="section__nav section__nav--cards section__nav--prev" data-nav="#subscriptions" type="button">
                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M1.25 7.72559L16.25 7.72559" strokeWidth="1.5" strokeLinecap="round"
                      strokeLinejoin="round" />
                      <path d="M7.2998 1.70124L1.2498 7.72524L7.2998 13.7502" strokeWidth="1.5" strokeLinecap="round"
                      strokeLinejoin="round" />
                    </svg>
                  </button>
                  <button onClick={() => handleNext(index)}  className="section__nav section__nav--cards section__nav--next" data-nav="#subscriptions" type="button">
                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.75 7.72559L0.75 7.72559" strokeWidth="1.5" strokeLinecap="round"
                      strokeLinejoin="round" />
                      <path d="M9.7002 1.70124L15.7502 7.72524L9.7002 13.7502" strokeWidth="1.5" strokeLinecap="round"
                      strokeLinejoin="round" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
	      </div>
        )}
        </Fragment>
      ))}
      
      {/* Observer target for infinite scroll */}
      <div ref={observerTarget}></div>
      {loadingThemes && (
        <div className="loading-themes" style={{ textAlign: 'center' }}>
          <p>Loading more themes...</p>
        </div>
      )}
    </>
  );
}