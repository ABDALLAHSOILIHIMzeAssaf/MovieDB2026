<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <title>CinéDB</title>
  <style>
    :root {
      --gold: #f5c518;
      --dark: #0a0a0f;
      --dark2: #13131a;
      --dark3: #1c1c26;
      --text: #e8e8f0;
      --muted: #7777aa;
      --red: #e50914;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--dark);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
    }

    main {
      padding-top: 64px;
    }

    /* SCROLLBAR */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--dark2);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(245, 197, 24, 0.35);
      border-radius: 3px;
    }

    /* NAVBAR */
    .navbar-main {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: linear-gradient(180deg, rgba(10, 10, 15, 0.97) 0%, rgba(10, 10, 15, 0.82) 100%);
      backdrop-filter: blur(14px);
      border-bottom: 1px solid rgba(245, 197, 24, 0.1);
      padding: 0 2rem;
      height: 64px;
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .nav-brand {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.9rem;
      color: var(--gold);
      text-decoration: none;
      letter-spacing: 2px;
      flex-shrink: 0;
    }

    .nav-brand span {
      color: var(--text);
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 0.1rem;
      list-style: none;
      flex: 1;
    }

    .nav-links>li>a {
      color: var(--muted);
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 500;
      padding: 0.45rem 0.7rem;
      border-radius: 7px;
      transition: color 0.2s, background 0.2s;
      display: flex;
      align-items: center;
      gap: 4px;
      white-space: nowrap;
    }

    .nav-links>li>a:hover {
      color: var(--gold);
      background: rgba(245, 197, 24, 0.08);
    }

    /* Genre dropdown */
    .nav-dropdown {
      position: relative;
    }

    .nav-dropdown>a::after {
      content: '▾';
      font-size: 0.65rem;
      margin-left: 2px;
    }

    .dropdown-panel {
      display: none;
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      background: var(--dark2);
      border: 1px solid rgba(245, 197, 24, 0.15);
      border-radius: 12px;
      padding: 0.6rem;
      width: 260px;
      columns: 2;
      column-gap: 0;
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.8);
    }

    .nav-dropdown:hover .dropdown-panel {
      display: block;
    }

    .dropdown-panel a {
      display: block;
      break-inside: avoid;
      color: var(--muted);
      text-decoration: none;
      font-size: 0.79rem;
      padding: 0.32rem 0.6rem;
      border-radius: 6px;
      white-space: nowrap;
      transition: color 0.15s, background 0.15s;
    }

    .dropdown-panel a:hover {
      color: var(--gold);
      background: rgba(245, 197, 24, 0.1);
    }

    /* Search */
    .nav-search {
      display: flex;
      gap: 0.5rem;
    }

    .search-wrap {
      position: relative;
    }

    .search-wrap input {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.09);
      border-radius: 20px;
      color: var(--text);
      font-size: 0.78rem;
      padding: 0.35rem 0.9rem 0.35rem 2rem;
      width: 145px;
      outline: none;
      transition: border-color 0.2s, width 0.3s;
      font-family: 'Inter', sans-serif;
    }

    .search-wrap input:focus {
      border-color: var(--gold);
      width: 180px;
    }

    .search-wrap input::placeholder {
      color: var(--muted);
    }

    .search-wrap i {
      position: absolute;
      left: 0.6rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 0.78rem;
      pointer-events: none;
    }

    /* SECTION TITLE */
    .section-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.5rem;
      letter-spacing: 2px;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    .section-title .acc {
      color: var(--gold);
    }

    .section-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, rgba(245, 197, 24, 0.25), transparent);
    }

    /* MOVIE CARD */
    .movie-card {
      background: var(--dark2);
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .movie-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65);
      border-color: rgba(245, 197, 24, 0.25);
    }

    .movie-card img {
      width: 100%;
      aspect-ratio: 2/3;
      object-fit: cover;
    }

    .movie-card-body {
      padding: 0.8rem;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .movie-card-title {
      font-weight: 600;
      font-size: 0.86rem;
      color: var(--text);
      line-height: 1.3;
      margin-bottom: 0.35rem;
    }

    .movie-card-desc {
      color: var(--muted);
      font-size: 0.74rem;
      line-height: 1.55;
      flex: 1;
    }

    .btn-voir {
      margin-top: 0.7rem;
      background: transparent;
      border: 1px solid rgba(245, 197, 24, 0.35);
      color: var(--gold);
      font-size: 0.76rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 0.38rem 1rem;
      border-radius: 20px;
      text-decoration: none;
      text-align: center;
      transition: background 0.2s, color 0.2s;
      display: block;
    }

    .btn-voir:hover {
      background: var(--gold);
      color: var(--dark);
    }

    /* BUTTONS */
    .btn-gold {
      background: var(--gold);
      color: var(--dark);
      border: none;
      padding: 0.65rem 1.6rem;
      border-radius: 25px;
      font-weight: 700;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: opacity 0.2s, transform 0.15s;
      cursor: pointer;
    }

    .btn-gold:hover {
      opacity: 0.87;
      transform: scale(1.03);
      color: var(--dark);
    }

    .btn-ghost {
      background: rgba(255, 255, 255, 0.09);
      color: var(--text);
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 0.65rem 1.6rem;
      border-radius: 25px;
      font-weight: 600;
      font-size: 0.85rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      backdrop-filter: blur(4px);
      transition: background 0.2s;
      cursor: pointer;
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.17);
      color: var(--text);
    }

    /* MODAL TRAILER */
    .trailer-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(0, 0, 0, 0.92);
      align-items: center;
      justify-content: center;
    }

    .trailer-modal.open {
      display: flex;
    }

    .trailer-inner {
      position: relative;
      width: 90%;
      max-width: 900px;
    }

    .trailer-inner iframe {
      width: 100%;
      aspect-ratio: 16/9;
      border-radius: 12px;
      border: none;
    }

    .trailer-close {
      position: absolute;
      top: -40px;
      right: 0;
      background: none;
      border: none;
      color: #fff;
      font-size: 1.6rem;
      cursor: pointer;
    }

    /* ACTOR SCROLL ROW */
    .actors-scroll {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding-bottom: 0.75rem;
      scroll-behavior: smooth;
    }

    .actors-scroll::-webkit-scrollbar {
      height: 4px;
    }

    .actor-card {
      flex-shrink: 0;
      width: 110px;
      text-align: center;
      cursor: pointer;
      text-decoration: none;
      display: block;
      transition: transform 0.2s;
    }

    .actor-card:hover {
      transform: translateY(-4px);
    }

    .actor-card img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(245, 197, 24, 0.25);
      margin: 0 auto 0.4rem;
      transition: border-color 0.2s;
    }

    .actor-card:hover img {
      border-color: var(--gold);
    }

    .actor-card-name {
      color: var(--muted);
      font-size: 0.73rem;
      font-weight: 500;
      line-height: 1.3;
    }

    /* FOOTER */
    footer {
      background: var(--dark2);
      border-top: 1px solid rgba(245, 197, 24, 0.1);
      padding: 3.5rem 0 0;
      margin-top: 5rem;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 2.5rem;
    }

    .footer-logo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.2rem;
      color: var(--gold);
      letter-spacing: 2px;
    }

    .footer-logo span {
      color: var(--text);
    }

    .footer-desc {
      color: var(--muted);
      font-size: 0.81rem;
      line-height: 1.75;
      margin-top: 0.75rem;
      max-width: 280px;
    }

    .footer-socials {
      display: flex;
      gap: 0.6rem;
      margin-top: 1.25rem;
    }

    .footer-socials a {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(245, 197, 24, 0.08);
      border: 1px solid rgba(245, 197, 24, 0.2);
      color: var(--gold);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      text-decoration: none;
      transition: background 0.2s;
    }

    .footer-socials a:hover {
      background: rgba(245, 197, 24, 0.22);
    }

    .footer-col h6 {
      color: var(--gold);
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      margin-bottom: 1rem;
    }

    .footer-col ul {
      list-style: none;
    }

    .footer-col ul li {
      margin-bottom: 0.55rem;
    }

    .footer-col ul li a {
      color: var(--muted);
      text-decoration: none;
      font-size: 0.81rem;
      transition: color 0.15s;
    }

    .footer-col ul li a:hover {
      color: var(--gold);
    }

    .footer-bottom {
      margin-top: 2.5rem;
      padding: 1.25rem 0;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .footer-bottom p {
      color: var(--muted);
      font-size: 0.77rem;
    }

    .tmdb-credit {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--muted);
      font-size: 0.77rem;
    }

    .tmdb-badge {
      background: #01b4e4;
      color: #fff;
      font-size: 0.6rem;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 3px;
      letter-spacing: 0.5px;
    }
  </style>
</head>

<body>

  <nav class="navbar-main">
    <a href="index.php" class="nav-brand">Ciné<span>DB</span></a>
    <ul class="nav-links">
      <li><a href="index.php"><i class="bi bi-fire"></i> Populaires</a></li>
      <li><a href="topRated.php"><i class="bi bi-star-half"></i> Top Rated</a></li>
      <li class="nav-dropdown">
        <a href="#"><i class="bi bi-grid-3x3-gap"></i> Genres</a>
        <div class="dropdown-panel">
          <a href="genreMovies.php?id=28">🔥 Action</a>
          <a href="genreMovies.php?id=12">🌍 Aventure</a>
          <a href="genreMovies.php?id=16">🎨 Animation</a>
          <a href="genreMovies.php?id=35">😂 Comédie</a>
          <a href="genreMovies.php?id=80">🔫 Crime</a>
          <a href="genreMovies.php?id=99">🎥 Documentaire</a>
          <a href="genreMovies.php?id=18">🎭 Drame</a>
          <a href="genreMovies.php?id=10751">👨‍👩‍👧 Famille</a>
          <a href="genreMovies.php?id=14">🧙 Fantastique</a>
          <a href="genreMovies.php?id=36">📜 Histoire</a>
          <a href="genreMovies.php?id=27">👻 Horreur</a>
          <a href="genreMovies.php?id=10402">🎵 Musique</a>
          <a href="genreMovies.php?id=9648">🔍 Mystère</a>
          <a href="genreMovies.php?id=10749">❤️ Romance</a>
          <a href="genreMovies.php?id=878">🚀 Sci-Fi</a>
          <a href="genreMovies.php?id=53">😰 Thriller</a>
          <a href="genreMovies.php?id=10752">⚔️ Guerre</a>
          <a href="genreMovies.php?id=37">🤠 Western</a>
        </div>
      </li>
    </ul>
    <div class="nav-search">
      <div class="search-wrap">
        <form action="searchMovies.php" method="get">
          <i class="bi bi-search"></i>
          <input type="text" name="query" placeholder="Films...">
        </form>
      </div>
      <div class="search-wrap">
        <form action="searchActeurs.php" method="get">
          <i class="bi bi-person-search"></i>
          <input type="text" name="query" placeholder="Acteurs...">
        </form>
      </div>
    </div>
  </nav>

  <!-- MODAL TRAILER -->
  <div class="trailer-modal" id="trailerModal">
    <div class="trailer-inner">
      <button class="trailer-close" onclick="closeTrailer()"><i class="bi bi-x-lg"></i></button>
      <iframe id="trailerIframe" src="" allowfullscreen></iframe>
    </div>
  </div>

  <script>
    function openTrailer(key) {
      document.getElementById('trailerIframe').src = 'https://www.youtube.com/embed/' + key + '?autoplay=1';
      document.getElementById('trailerModal').classList.add('open');
    }

    function closeTrailer() {
      document.getElementById('trailerIframe').src = '';
      document.getElementById('trailerModal').classList.remove('open');
    }
    document.getElementById('trailerModal').addEventListener('click', function(e) {
      if (e.target === this) closeTrailer();
    });
  </script>

  <main>