import { useState, useEffect } from 'react'
const apiKey    = import.meta.env.PUBLIC_API_KEY
type Movie = {
    name: string;
    origin_name: string;
    slug: string;
    image: { poster: string };
    type: string;
    release_date: string;
    rating: number;
    genres: {name:string};
};
export default function LoadMore({ url, initialPage }: { url: string, initialPage: number }) {
    const pageSize              = 18;
    const [page, setPage]       = useState(initialPage + 1);
    const [loading, setLoading] = useState(false);
    const [movies, setMovies]   = useState<Movie[]>([]);

    useEffect(() => {
        setMovies([]);
        setPage(initialPage + 1);
    }, [url, initialPage]);
    
    async function handleLoadMore() {
        if (loading) return;
        setLoading(true);
        try {
            const res = await fetch(url + `&page=${page}&page_size=${pageSize}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'x-api-key': apiKey
                }
            }).then((response) => response.json());

            const listMovies: Movie[] = res.data.movies;
            setMovies((prev) => [...prev, ...listMovies]);
            setPage((prev) => prev + 1);
        } catch (error) {
            console.error('Failed to load more movies:', error);
        } finally {
            setLoading(false);
        }
    }

    return (
        <>
            <div className="row">
                {movies.map((movie:any) => (
                    <div key={movie.slug} className="col-6 col-sm-4 col-lg-3 col-xl-2">
                        <div className="card">
                            <a href={movie.type === 'single' ? 'movie/' + movie.slug : 'tv-series/' + movie.slug} className="card__cover">
                                <img
                                    src={"https://wsrv.nl/?url=" + movie.image.poster + "&format=webp&quality=50&output=webp"}
                                    alt={movie.origin_name}
                                    loading="lazy"
                                    decoding="async"/>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fillRule="evenodd" clipRule="evenodd" d="M11 1C16.5228 1 21 5.47716 21 11C21 16.5228 16.5228 21 11 21C5.47716 21 1 16.5228 1 11C1 5.47716 5.47716 1 11 1Z"
                                    strokeLinecap="round" strokeLinejoin="round" />
                                    <path fillRule="evenodd" clipRule="evenodd"
                                    d="M14.0501 11.4669C13.3211 12.2529 11.3371 13.5829 10.3221 14.0099C10.1601 14.0779 9.74711 14.2219 9.65811 14.2239C9.46911 14.2299 9.28711 14.1239 9.19911 13.9539C9.16511 13.8879 9.06511 13.4569 9.03311 13.2649C8.93811 12.6809 8.88911 11.7739 8.89011 10.8619C8.88911 9.90489 8.94211 8.95489 9.04811 8.37689C9.07611 8.22089 9.15811 7.86189 9.18211 7.80389C9.22711 7.69589 9.30911 7.61089 9.40811 7.55789C9.48411 7.51689 9.57111 7.49489 9.65811 7.49789C9.74711 7.49989 10.1091 7.62689 10.2331 7.67589C11.2111 8.05589 13.2801 9.43389 14.0401 10.2439C14.1081 10.3169 14.2951 10.5129 14.3261 10.5529C14.3971 10.6429 14.4321 10.7519 14.4321 10.8619C14.4321 10.9639 14.4011 11.0679 14.3371 11.1549C14.3041 11.1999 14.1131 11.3999 14.0501 11.4669Z"
                                    strokeLinecap="round" strokeLinejoin="round" />
                                </svg>
                            </a>
                            <button className="card__add" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
                                </svg>
                            </button>
                            <span className="card__rating">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                                </svg>
                                {movie.rating}
                            </span>
                            <h2 className="card__title">
                                <a href={movie.type === 'single' ? 'movie/' + movie.slug : 'tv-series/' + movie.slug}>
                                    {movie.name}
                                </a>
                            </h2>
                            <ul className="card__list">
                                <li>Free</li>
                                <li>{movie.genres[0]?.name}</li>
                                <li>{movie.release_date}</li>
                            </ul>
                        </div>
                    </div>
                ))}
            </div>
            <div style={{ textAlign: 'center', marginTop: '20px' }}>
                <button className="catalog__more" type="button" onClick={handleLoadMore}>
                    {loading ? 'Loading...' : 'Load more'}
                </button>
            </div>
        </>
    );
}