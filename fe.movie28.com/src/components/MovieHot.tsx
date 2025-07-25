import useEmblaCarousel from 'embla-carousel-react';
import { useEffect, useCallback, useState } from 'react'

type Movie = {
  name: string;
  origin_name: string;
  slug: string;
  image: { thumb: string };
  type: string;
  release_date: string;
  rating: number;
  genres: { name: string };
};

export default function MovieHot({ moviesHot }: { moviesHot: Movie[] }) {
//   const [emblaRef, emblaApi] = useEmblaCarousel({
//     loop: true,
//     align: 'center',
//     skipSnaps: false,
//   });

//   const scrollPrev = useCallback(() => emblaApi?.scrollPrev(), [emblaApi]);
//   const scrollNext = useCallback(() => emblaApi?.scrollNext(), [emblaApi]);
  const [emblaRef, emblaApi] = useEmblaCarousel({ loop: true, skipSnaps: false, })
  const [selectedIndex, setSelectedIndex] = useState(0)

  const scrollNext = useCallback(() => {
    if (!emblaApi) return
    const slideCount = emblaApi.slideNodes().length
    const targetIndex = (selectedIndex + 3) % slideCount
    emblaApi.scrollTo(targetIndex)
  }, [emblaApi, selectedIndex])

  const scrollPrev = useCallback(() => {
    if (!emblaApi) return
    const slideCount = emblaApi.slideNodes().length
    const targetIndex = (selectedIndex - 3 + slideCount) % slideCount
    emblaApi.scrollTo(targetIndex)
  }, [emblaApi, selectedIndex])

  useEffect(() => {
    if (!emblaApi) return
    const onSelect = () => {
      setSelectedIndex(emblaApi.selectedScrollSnap())
    }
    emblaApi.on('select', onSelect)
    onSelect()
  }, [emblaApi])

  return (
    <div className="home home--static">
      <div className="embla" ref={emblaRef}>
        <div className="embla__container">
          {moviesHot.map((movie: any) => (
            <div className="embla__slide" key={movie.slug}>
              <div className="home__card">
                    <a href={movie.type == 'single' ? 'movie/' + movie.slug : 'tv-series/' + movie.slug}>
                    <img src={'https://wsrv.nl/?url='+ movie.image.thumb + '&fit=cover&height=350&width=450&format=webp&quality=100&output=webp'} alt={movie.name} />
                    </a>
                    <div>
                    <h2>{movie.name}</h2>
                    <ul>
                        <li>Free</li>
                        <li>{movie.genres[0].name}</li>
                        <li>{movie.release_date}</li>
                    </ul>
                    </div>
                    <button className="home__add" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                        d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z" />
                    </svg>
                    </button>
                    <span className="home__rating"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                        d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                    </svg>
                    9.1</span>
                </div>
            </div>
          ))}
        </div>
      </div>

      <button className="home__nav home__nav--prev" onClick={scrollPrev}></button>
      <button className="home__nav home__nav--next" onClick={scrollNext}></button>
    </div>
  );
}
