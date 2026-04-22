<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>
<?php
$allMovies   = popularMovies();
$top5        = array_slice($allMovies, 0, 5);
$rest        = array_slice($allMovies, 5, 20);
$byGenre     = getPopularByGenre($rest);

// Récupérer les trailers du top 5
$trailers = [];
foreach ($top5 as $m) {
    $trailers[$m['id']] = getMovieTrailer($m['id']);
}
?>

<style>
/* CAROUSEL */
.hero-carousel { position: relative; height: 90vh; min-height: 500px; overflow: hidden; }
.carousel-slides { display: flex; height: 100%; transition: transform 0.9s cubic-bezier(0.77,0,0.18,1); }
.carousel-slide {
  flex-shrink: 0; width: 100%; height: 100%; position: relative;
}
.carousel-slide-bg {
  position: absolute; inset: 0;
  background-size: cover; background-position: center;
  transition: transform 6s ease;
}
.carousel-slide.active .carousel-slide-bg { transform: scale(1.05); }
.carousel-slide::after {
  content: '';  position: absolute; inset: 0;
  background: linear-gradient(90deg, rgba(10,10,15,0.88) 0%, rgba(10,10,15,0.4) 60%, transparent 100%),
              linear-gradient(0deg, rgba(10,10,15,0.7) 0%, transparent 50%);
}
.slide-content {
  position: absolute; bottom: 12%; left: 5%; z-index: 2;
  max-width: 600px; animation: slideUp 0.7s ease both;
}
@keyframes slideUp { from { opacity:0; transform: translateY(30px); } to { opacity:1; transform: translateY(0); } }
.slide-badge {
  display: inline-block; background: var(--gold); color: var(--dark);
  font-size: 0.68rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  padding: 3px 10px; border-radius: 4px; margin-bottom: 0.75rem;
}
.slide-title {
  font-family: 'Bebas Neue', sans-serif; font-size: clamp(2.2rem, 5vw, 3.8rem);
  line-height: 1; color: #fff; margin-bottom: 0.75rem; text-shadow: 0 2px 20px rgba(0,0,0,0.5);
}
.slide-desc {
  color: rgba(255,255,255,0.8); font-size: 0.88rem; line-height: 1.65;
  margin-bottom: 1.25rem; max-width: 480px;
  display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.slide-meta { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
.slide-meta span { color: rgba(255,255,255,0.6); font-size: 0.8rem; }
.slide-meta .rating { color: var(--gold); font-weight: 700; font-size: 0.9rem; }
.slide-btns { display: flex; gap: 0.75rem; flex-wrap: wrap; }

/* Carousel controls */
.carousel-dots {
  position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
  z-index: 3; display: flex; gap: 0.5rem;
}
.carousel-dot {
  width: 28px; height: 3px; border-radius: 2px;
  background: rgba(255,255,255,0.3); cursor: pointer; transition: background 0.3s, width 0.3s;
}
.carousel-dot.active { background: var(--gold); width: 48px; }
.carousel-arrow {
  position: absolute; top: 50%; transform: translateY(-50%); z-index: 3;
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
  color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 1.1rem;
  display: flex; align-items: center; justify-content: center; cursor: pointer;
  backdrop-filter: blur(4px); transition: background 0.2s;
}
.carousel-arrow:hover { background: rgba(245,197,24,0.25); border-color: var(--gold); color: var(--gold); }
.carousel-arrow.left { left: 1.5rem; }
.carousel-arrow.right { right: 1.5rem; }
.progress-bar-carousel {
  position: absolute; bottom: 0; left: 0; height: 3px;
  background: var(--gold); z-index: 3;
  animation: carouselProgress 30s linear infinite;
}
@keyframes carouselProgress { from { width:0; } to { width:100%; } }

/* GENRE SECTIONS */
.genre-section { margin-bottom: 3.5rem; }
.movies-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1.25rem;
}
</style>

<!-- CAROUSEL -->
<div class="hero-carousel" id="heroCarousel">
  <div class="carousel-slides" id="carouselTrack">
    <?php foreach ($top5 as $i => $movie):
      $bg = 'https://image.tmdb.org/t/p/original' . ($movie['backdrop_path'] ?? $movie['poster_path']);
      $trailer = $trailers[$movie['id']] ?? null;
      $year = substr($movie['release_date'] ?? '', 0, 4);
      $note = round($movie['vote_average'] ?? 0, 1);
      $desc = $movie['overview'] ?? '';
    ?>
    <div class="carousel-slide <?= $i===0?'active':'' ?>">
      <div class="carousel-slide-bg" style="background-image:url('<?= $bg ?>')"></div>
      <div class="slide-content">
        <span class="slide-badge">#<?= $i+1 ?> Populaire</span>
        <h1 class="slide-title"><?= htmlspecialchars($movie['title']) ?></h1>
        <div class="slide-meta">
          <span class="rating">⭐ <?= $note ?>/10</span>
          <?php if ($year): ?><span>📅 <?= $year ?></span><?php endif; ?>
        </div>
        <p class="slide-desc"><?= htmlspecialchars($desc) ?></p>
        <div class="slide-btns">
          <?php if ($trailer): ?>
            <button class="btn-gold" onclick="openTrailer('<?= $trailer ?>')">
              <i class="bi bi-play-fill"></i> Bande annonce
            </button>
          <?php endif; ?>
          <a href="film.php?id=<?= $movie['id'] ?>" class="btn-ghost">
            <i class="bi bi-info-circle"></i> Plus d'infos
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <button class="carousel-arrow left" onclick="carouselPrev()"><i class="bi bi-chevron-left"></i></button>
  <button class="carousel-arrow right" onclick="carouselNext()"><i class="bi bi-chevron-right"></i></button>

  <div class="carousel-dots">
    <?php for ($i=0;$i<5;$i++): ?>
      <div class="carousel-dot <?= $i===0?'active':'' ?>" onclick="goToSlide(<?= $i ?>)"></div>
    <?php endfor; ?>
  </div>

  <div class="progress-bar-carousel" id="carouselProgress"></div>
</div>

<!-- TOP 6-25 PAR GENRE -->
<div style="padding: 3rem 2rem 0; max-width: 1400px; margin: 0 auto;">
  <?php if (empty($byGenre)): ?>
    <p style="color:var(--muted)">Impossible de charger les films.</p>
  <?php else: ?>
    <?php foreach ($byGenre as $gid => $group): ?>
    <div class="genre-section">
      <h2 class="section-title"><span class="acc"><?= $group['name'] ?></span></h2>
      <div class="movies-grid">
        <?php foreach ($group['movies'] as $movie):
          $poster = !empty($movie['poster_path'])
            ? 'https://image.tmdb.org/t/p/w342' . $movie['poster_path']
            : 'https://via.placeholder.com/342x513/13131a/7777aa?text=No+Image';
          $desc = mb_substr($movie['overview'] ?? '', 0, 100);
        ?>
        <div class="movie-card">
          <img src="<?= $poster ?>" alt="<?= htmlspecialchars($movie['title']) ?>" loading="lazy">
          <div class="movie-card-body">
            <div class="movie-card-title"><?= htmlspecialchars($movie['title']) ?></div>
            <div class="movie-card-desc">
              <?php if ($desc): ?>
                <?= htmlspecialchars($desc) ?>...
              <?php else: ?>
                Aucune description disponible.
              <?php endif; ?>
            </div>
            <a href="film.php?id=<?= $movie['id'] ?>" class="btn-voir">Voir plus</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<script>
let current = 0;
const total = <?= count($top5) ?>;
const track = document.getElementById('carouselTrack');
const dots  = document.querySelectorAll('.carousel-dot');
const slides = document.querySelectorAll('.carousel-slide');
let timer;

function goToSlide(n) {
  slides[current].classList.remove('active');
  dots[current].classList.remove('active');
  current = (n + total) % total;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
  track.style.transform = `translateX(-${current * 100}%)`;
  // reset progress bar
  const pb = document.getElementById('carouselProgress');
  pb.style.animation = 'none';
  pb.offsetHeight; // reflow
  pb.style.animation = 'carouselProgress 30s linear infinite';
  clearInterval(timer);
  timer = setInterval(() => goToSlide(current + 1), 30000);
}
function carouselNext() { goToSlide(current + 1); }
function carouselPrev() { goToSlide(current - 1); }
timer = setInterval(carouselNext, 30000);
</script>

<?php require("footer.php"); ?>