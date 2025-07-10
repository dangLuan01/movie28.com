import React, { useEffect, useRef, useState } from 'react';
import Plyr from 'plyr';
//import 'plyr/dist/plyr.css';

const VideoPlayer   = ({ movie }) => {
  const imgSrc      = import.meta.env.PUBLIC_URL_WSRV;
  const videoRef    = useRef(null);
  const hlsRef      = useRef(null);
  const plyrRef     = useRef(null); 
  const carouselRef = useRef(null);

  // Server và episode present
  const [currentServerIdx, setCurrentServerIdx] = useState(0);
  const [currentEpisodeIdx, setCurrentEpisodeIdx] = useState(0);

  const currentServer = movie.servers[currentServerIdx];
  const currentEpisode = currentServer?.episodes?.[currentEpisodeIdx];
  const currentUrl = currentEpisode?.hls;

  // Initialize carousel
  useEffect(() => {
    
    if (typeof window !== 'undefined' && window.$) {
      const $ = window.$;
      carouselRef.current = $('.owl-carousel').owlCarousel({
        mouseDrag:true,
        touchDrag: true,
        loop: false,
        margin: 10,
        nav: false,
        responsive: {
          0: { items: 3 },
          576: { items: 4 },
          768: { items: 5 },
          1200: { items: 7 }
        }
      });
    }

    return () => {
      if (carouselRef.current) {
        carouselRef.current.trigger('destroy.owl.carousel');
      }
    };
  }, []);

  // Handle video player
  useEffect(() => {
    let isMounted = true;
    const video = videoRef.current;
    if (!video || !currentUrl) return;

    // Destroy old HLS
    if (hlsRef.current) {
      hlsRef.current.destroy();
      hlsRef.current = null;
    }

    import('hls.js').then(({ default: Hls }) => {
      if (!isMounted) return;

      if (Hls.isSupported()) {
        const hls = new Hls();
        hls.loadSource(currentUrl);
        hls.attachMedia(video);
        hlsRef.current = hls;
      } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = currentUrl;
      }

      if (!plyrRef.current) {
        plyrRef.current = new Plyr(video);
      }
    });

    return () => {
      isMounted = false;
      if (hlsRef.current) {
        hlsRef.current.destroy();
        hlsRef.current = null;
      }
    };
  }, [currentUrl]);

  // Handle carousel navigation
  const handlePrev = () => {
    if (carouselRef.current) {
      carouselRef.current.trigger('prev.owl.carousel');
    }
  };

  const handleNext = () => {
    if (carouselRef.current) {
      carouselRef.current.trigger('next.owl.carousel');
    }
  };

  return (
    <>
      <div className="col-12 col-xl-8">
        <video ref={videoRef} controls playsInline />
        
        <div className="article__actions article__actions--details" style={{ marginTop: 10 }}>
          <div className="article__download">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M21,14a1,1,0,0,0-1,1v4a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V15a1,1,0,0,0-2,0v4a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V15A1,1,0,0,0,21,14Zm-9.71,1.71a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l4-4a1,1,0,0,0-1.42-1.42L13,12.59V3a1,1,0,0,0-2,0v9.59l-2.29-2.3a1,1,0,1,0-1.42,1.42Z" />
            </svg>
            Server: &nbsp;&nbsp;
            {movie.servers.map((server, idx) => (
              <button
                key={idx}
                onClick={() => {
                  const selectedServer = movie.servers[idx];
                  const maxEpIdx = selectedServer.episodes.length - 1;
                  const targetEpisodeIdx = currentEpisodeIdx <= maxEpIdx ? currentEpisodeIdx : maxEpIdx;
                  setCurrentServerIdx(idx);
                  setCurrentEpisodeIdx(targetEpisodeIdx);
                }}
                style={{
                  marginRight: 8,
                  padding: '4px 8px',
                  backgroundColor: idx === currentServerIdx ? '#0070f3' : '',
                  color: '#fff',
                  border: 'none',
                  borderRadius: 4,
                  cursor: 'pointer',
                }}>
                {server.name}
              </button>
            ))}
          </div>

          <button className="article__favorites" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
            </svg>
            Add to favorites
          </button>
        </div>
      </div>

      <div className="col-12">
        <div className="series-wrap">
          <h3 className="series-wrap__title">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M9,10a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V11A1,1,0,0,0,9,10Zm12,1a1,1,0,0,0,1-1V6a1,1,0,0,0-1-1H3A1,1,0,0,0,2,6v4a1,1,0,0,0,1,1,1,1,0,0,1,0,2,1,1,0,0,0-1,1v4a1,1,0,0,0,1,1H21a1,1,0,0,0,1-1V14a1,1,0,0,0-1-1,1,1,0,0,1,0-2ZM20,9.18a3,3,0,0,0,0,5.64V17H10a1,1,0,0,0-2,0H4V14.82A3,3,0,0,0,4,9.18V7H8a1,1,0,0,0,2,0H20Z" />
            </svg>
            {currentServer?.name || '1st season'}
          </h3>
          
          <div className="section__carousel-wrap">
            <div className="section__series owl-carousel">
              {currentServer?.episodes?.map((episode, epIdx) => (
                <div 
                  key={epIdx} 
                  className="series"
                  onClick={() => setCurrentEpisodeIdx(epIdx)}>
                  <div className="series__cover">
                    <img 
                      src={imgSrc + movie.image.thumb + "&q=1"} 
                      alt={`Episode ${epIdx + 1}`} 
                      onError={(e) => {
                        e.target.src = 'img/series/2.jpg';
                      }}
                      loading='lazy' decoding='async'/>
                    <span>
                      {epIdx === currentEpisodeIdx ? 
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
                        <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round"></g>
                        <g id="SVGRepo_iconCarrier"> 
                          <g id="Media / Pause"> 
                            <g id="Vector"> 
                              <path d="M15 5.5V18.5C15 18.9647 15 19.197 15.0384 19.3902C15.1962 20.1836 15.816 20.8041 16.6094 20.9619C16.8026 21.0003 17.0349 21.0003 17.4996 21.0003C17.9642 21.0003 18.1974 21.0003 18.3906 20.9619C19.184 20.8041 19.8041 20.1836 19.9619 19.3902C20 19.1987 20 18.9687 20 18.5122V5.48777C20 5.03125 20 4.80087 19.9619 4.60938C19.8041 3.81599 19.1836 3.19624 18.3902 3.03843C18.197 3 17.9647 3 17.5 3C17.0353 3 16.8026 3 16.6094 3.03843C15.816 3.19624 15.1962 3.81599 15.0384 4.60938C15 4.80257 15 5.03534 15 5.5Z" stroke="#ffffff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"></path> <path d="M4 5.5V18.5C4 18.9647 4 19.197 4.03843 19.3902C4.19624 20.1836 4.81599 20.8041 5.60938 20.9619C5.80257 21.0003 6.0349 21.0003 6.49956 21.0003C6.96421 21.0003 7.19743 21.0003 7.39062 20.9619C8.18401 20.8041 8.8041 20.1836 8.96191 19.3902C9 19.1987 9 18.9687 9 18.5122V5.48777C9 5.03125 9 4.80087 8.96191 4.60938C8.8041 3.81599 8.18356 3.19624 7.39018 3.03843C7.19698 3 6.96465 3 6.5 3C6.03535 3 5.80257 3 5.60938 3.03843C4.81599 3.19624 4.19624 3.81599 4.03843 4.60938C4 4.80257 4 5.03534 4 5.5Z" stroke="#ffffff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"></path> 
                            </g> 
                          </g> 
                        </g>
                      </svg> : <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <path fillRule="evenodd" clipRule="evenodd" d="M11 1C16.5228 1 21 5.47716 21 11C21 16.5228 16.5228 21 11 21C5.47716 21 1 16.5228 1 11C1 5.47716 5.47716 1 11 1Z" strokeLinecap="round" strokeLinejoin="round"/>
                        <path fillRule="evenodd" clipRule="evenodd" d="M14.0501 11.4669C13.3211 12.2529 11.3371 13.5829 10.3221 14.0099C10.1601 14.0779 9.74711 14.2219 9.65811 14.2239C9.46911 14.2299 9.28711 14.1239 9.19911 13.9539C9.16511 13.8879 9.06511 13.4569 9.03311 13.2649C8.93811 12.6809 8.88911 11.7739 8.89011 10.8619C8.88911 9.90489 8.94211 8.95489 9.04811 8.37689C9.07611 8.22089 9.15811 7.86189 9.18211 7.80389C9.22711 7.69589 9.30911 7.61089 9.40811 7.55789C9.48411 7.51689 9.57111 7.49489 9.65811 7.49789C9.74711 7.49989 10.1091 7.62689 10.2331 7.67589C11.2111 8.05589 13.2801 9.43389 14.0401 10.2439C14.1081 10.3169 14.2951 10.5129 14.3261 10.5529C14.3971 10.6429 14.4321 10.7519 14.4321 10.8619C14.4321 10.9639 14.4011 11.0679 14.3371 11.1549C14.3041 11.1999 14.1131 11.3999 14.0501 11.4669Z" strokeLinecap="round" strokeLinejoin="round"/>
                      </svg>}
                     
                      {movie.runtime || '56:36'}
                    </span>
                  </div>
                  <h3 className="series__title">
                    Tập {epIdx + 1}
                  </h3>
                </div>
              ))}
            </div>

            <button
              className="section__nav section__nav--series section__nav--prev"
              onClick={handlePrev}
              type="button">
              <svg width="17" height="15" viewBox="0 0 17 15" fill="none">
                <path d="M1.25 7.72559L16.25 7.72559" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                <path d="M7.2998 1.70124L1.2498 7.72524L7.2998 13.7502" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
            <button
              className="section__nav section__nav--series section__nav--next"
              onClick={handleNext}
              type="button">
              <svg width="17" height="15" viewBox="0 0 17 15" fill="none">
                <path d="M15.75 7.72559L0.75 7.72559" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                <path d="M9.7002 1.70124L15.7502 7.72524L9.7002 13.7502" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </>
  );
};

export default VideoPlayer;