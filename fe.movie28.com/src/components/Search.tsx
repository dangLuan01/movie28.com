import { useState, useEffect, useRef } from "react";
const domainApi = import.meta.env.PUBLIC_API_GO_URL;
const apiKey    = import.meta.env.PUBLIC_API_KEY;
export default function Search() {
  const [query, setQuery]     = useState("");
  const [results, setResults] = useState<any[]>([]);
  const [isOpen, setIsOpen]   = useState(false);
  const popupContentRef       = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!query) {
      setIsOpen(false);
      setResults([]);
      return;
    }
    
    const timeout = setTimeout(() => {
      fetch(domainApi + `/api/v1/search?query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers:{
          'Content-Type': 'application/json',
          'x-api-key': apiKey
        }
      }).then(res => res.json())
      .then(datas => {
          setResults(datas.data);
          setIsOpen(true);

          setTimeout(() => {
          popupContentRef.current?.scrollTo({
            top: 0,
            behavior: "smooth"
          });
        }, 0);
        })
        .catch(err => console.error(err));
    }, 1000); // chờ 1s

    return () => clearTimeout(timeout); // clear nếu gõ tiếp
  }, [query]);

  return (
    <>
    <form className="header__form" onSubmit={e => e.preventDefault()}>
      <input
        className="header__form-input"
        type="text"
        placeholder="I'm looking for..."
        value={query}
        onChange={e => setQuery(e.target.value)}/>
      <button className="header__form-btn" type="button"  onClick={() => setIsOpen(true)} 
          disabled={!query}>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
              d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z" />
          </svg>
	    </button>
    </form>
    <button className="header__search" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path
            d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z" />
        </svg>
    </button>

    {isOpen && (
    <div className="popup-overlay">
      <div className="popup-box">
        <button className="close-btn" onClick={() => setIsOpen(false)}>
          &times;
        </button>
        <div className="popup-content" ref={popupContentRef}>
          <div className="title-main">Danh sách phim</div>
          {results && results.length > 0 ? (
            results.map((movie, idx) => (
            <a href={movie.type == 'single' ? '/movie/' + movie.slug : '/tv-series/' + movie.slug} key={idx}>
              <div className="movie-card">
                <img className="poster" src={ 'https://wsrv.nl/?url=' + movie.image.poster + '&format=webp&quality=50&output=webp'} alt={movie.name} loading="lazy" decoding="sync"/>
                <div className="card-details">
                    <div>
                        <div className="movie-title">{movie.name}</div>
                        <div className="movie-subtitle">{movie.origin_name}</div>
                    </div>
                    <div className="movie-info">
                        <span className="badge">{movie.age}</span>
                        <span className="movie-meta">{movie.release_date} • {movie.runtime}</span>
                    </div>
                </div>
              </div>
            </a>
            ))
          ) : (
            <p className="not-movie">Không có phim nào.</p>
          )}
        </div>
      </div>
    </div>
    )}
    </>
  );
}
