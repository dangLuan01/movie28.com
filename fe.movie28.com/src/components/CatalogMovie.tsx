import { useState } from "react";
import Select from "react-select";
import MovieList from "./MovieList";
import LoadMore from "./LoadMore";

const domainApi = import.meta.env.PUBLIC_API_GO_URL;
const apiKey = import.meta.env.PUBLIC_API_KEY;

type Genre = {
  slug: string;
  name: string;
};

type Movie = {
  name: string;
  origin_name: string;
  slug: string;
  image: { poster: string };
  type: string;
  release_date: string;
  rating: number;
  genres: { name: string }[];
};

type Props = {
  genres: Genre[];
  initialMovies: Movie[];
  initialGrade: string;
};

export default function CatalogMovie({
  genres,
  initialMovies,
  initialGrade,
}: Props) {
  const [movies, setMovies] = useState<Movie[]>(initialMovies);
  const [selectedGenre, setSelectedGenre] = useState<string>("all");
  const [selectedYear, setSelectedYear] = useState<string>("");
  const [selectedGrade, setSelectedGrade] = useState<string>(initialGrade);

  const genreOptions = [
    { value: "all", label: "All genres" },
    ...genres.map((genre) => ({
      value: genre.slug,
      label: genre.name,
    })),
  ];

  const yearOptions = [
    { value: "", label: "All the years" },
    { value: "1950-1959", label: "'50s" },
    { value: "1960-1969", label: "'60s" },
    { value: "1970-1979", label: "'70s" },
    { value: "1980-1989", label: "'80s" },
    { value: "1990-1999", label: "'90s" },
    { value: "2000-2009", label: "2000-10" },
    { value: "2010-2019", label: "2010-20" },
    { value: "2020-2029", label: "2020-30" },
  ]

  const handleChange = async (
    genreSlug: string,
    year: string,
    grade: string
  ) => {
    const el = document.querySelector('.catalog');
    if (el) {
      const top = el.getBoundingClientRect().top + window.pageYOffset;

      window.scrollTo({
        top,
        behavior: 'smooth'
      });
    }
    let url = domainApi + 
              `/api/v1/movie/catalog?genre=${genreSlug}&release_date=${year}&type=${grade}`;

    const res = await fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "x-api-key": apiKey,
      },
    });
    const datas = await res.json();
    setMovies(datas.data.movies);
    
    
  };

  const onGradeChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const val = e.target.id;
    setSelectedGrade(val);
    handleChange(selectedGenre, selectedYear, val);
  };
  const customStyles = {
  control: (base:any) => ({
    ...base,
    backgroundColor: "#151F30",
    borderColor: "#151F30", 
    "&:hover": {
      borderColor: "#151F30"
    },
    minHeight: "40px",
    color: "#fff",
  }),
  singleValue: (base:any) => ({
    ...base,
    color: "#fff",
    fontSize: "14px",
    fontFamily: '"Inter", sans-serif',
  }),
  menu: (base:any) => ({
    ...base,
    backgroundColor: "#0e1a2b",      // nền xanh đậm
    borderRadius: "8px",
    marginTop: "4px",
    zIndex: 9999
  }),
  option: (base:any, state:any) => ({
    ...base,
    backgroundColor: state.isFocused
      ? "#0e1a2b"   // xanh sáng khi hover
      : "transparent",
    color: state.isFocused ? "#2f80ed" : "#ffffff",
    cursor: "pointer",
    padding: "10px 12px",
    fontSize: "14px",
    fontFamily: '"Inter", sans-serif',
  }),
  dropdownIndicator: (base:any, state:any) => ({
    ...base,
    color: state.isFocused ? "#1f4ed8" : "#ffffff",
    "&:hover": {
      color: "#1f4ed8",
    },
  }),
  indicatorSeparator: () => ({
    display: "none",
  }),
  placeholder: (base:any) => ({
    ...base,
    color: "#ffffff",
    
  }),
  input: (base:any) => ({
    ...base,
    color: "#ffffff",
  }),
  };
  const url = domainApi + 
            `/api/v1/movie/catalog?genre=${selectedGenre}&release_date=${selectedYear}&type=${selectedGrade}`;
  return (
    <div className="catalog">
      <div className="container">
        <div className="row">
          <div className="col-12">
            <div className="catalog__nav">
              <div className="catalog__select-wrap">
                  <Select options={genreOptions} value={genreOptions.find((opt) => opt.value === selectedGenre)}
                      onChange={(option) => {
                          const val = option?.value || "all";
                          setSelectedGenre(val);
                          handleChange(val, selectedYear, selectedGrade);
                      }}
                      classNamePrefix="catalog__select"
                      styles={customStyles}
                  />
                  <Select 
                      options={yearOptions}
                      value={yearOptions.find(
                          (opt) => opt.value === selectedYear
                      )}
                      onChange={(option) => {
                          const val = option?.value || "all";
                          setSelectedYear(val);
                          handleChange(selectedGenre, val, selectedGrade);
                      }}
                      classNamePrefix="catalog__select"
                      styles={customStyles}
                  />
              </div>
              <div className="slider-radio">
                <input type="radio" name="grade" id="featured" checked={selectedGrade === "featured"} onChange={onGradeChange}/>
                <label htmlFor="featured">Featured</label>
                <input type="radio" name="grade" id="single" checked={selectedGrade === "single"} onChange={onGradeChange}/>
                <label htmlFor="single">Movie</label>
                <input type="radio" name="grade" id="series" checked={selectedGrade === "series"} onChange={onGradeChange}/>
                <label htmlFor="series">Series</label>
              </div>
            </div>
            <MovieList movies={movies} />
            <LoadMore url={url} initialPage={1}/>
          </div>
		    </div>
      </div>
	  </div>
  );
}
