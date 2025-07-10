import React, { useEffect, useRef, useState } from 'react';
import Plyr from 'plyr';
//import 'plyr/dist/plyr.css';

const VideoPlayer = ({ servers }) => {
  const videoRef = useRef(null);
  const hlsRef = useRef(null);
  const plyrRef = useRef(null);
  const [currentUrl, setCurrentUrl] = useState(servers[0]?.episodes[0]?.hls || '');
  const [currentServer, setCurrentServer] = useState(servers[0]?.name || '');

  useEffect(() => {
    let isMounted = true;
    const video = videoRef.current;
    if (!video || !currentUrl) return;

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
  return (
    <div className="col-12 col-xl-8">
      <video ref={videoRef} id="player" controls playsInline/>
      <div className="article__actions article__actions--details" style={{ marginTop: 10 }}>
        <div className="article__download">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M21,14a1,1,0,0,0-1,1v4a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V15a1,1,0,0,0-2,0v4a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V15A1,1,0,0,0,21,14Zm-9.71,1.71a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l4-4a1,1,0,0,0-1.42-1.42L13,12.59V3a1,1,0,0,0-2,0v9.59l-2.29-2.3a1,1,0,1,0-1.42,1.42Z" />
          </svg>
          Server:&nbsp;&nbsp;
           {servers.map((server, idx) => {
            const url = server.episodes[0]?.hls;
            return (
              <button
                key={idx}
                onClick={() => {
                  if (url && url !== currentUrl) {
                    setCurrentUrl(url);
                    setCurrentServer(server.name);
                  }
                }}
                style={{
                  marginRight: 8,
                  padding: '4px 8px',
                  backgroundColor: currentUrl === url ? '#0070f3' : '#eaeaea',
                  color: currentUrl === url ? '#fff' : '#000',
                  border: 'none',
                  borderRadius: 4,
                  cursor: 'pointer',
                }}>
                {server.name}
              </button>
            );
          })}
        </div>
        <button className="article__favorites" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
          </svg>
          Add to favorites
        </button>
      </div>
    </div>
  );
};

export default VideoPlayer;
