<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>
<?php
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id      = (int)$_GET['id'];
$movie   = getMovieDetail($id);
$trailer = getMovieTrailer($id);
$cast    = getMovieCast($id);

if (empty($movie)) {
    echo "<p style='padding:5rem 2rem;color:var(--muted)'>Film introuvable.</p>";
    require("footer.php");
    exit;
}

$backdrop = !empty($movie['backdrop_path']) ? 'https://image.tmdb.org/t/p/original' . $movie['backdrop_path'] : '';
$poster   = !empty($movie['poster_path'])   ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']   : 'https://via.placeholder.com/500x750/13131a/7777aa?text=No+Image';
$title    = htmlspecialchars($movie['title'] ?? '');
$overview = htmlspecialchars($movie['overview'] ?? 'Aucune description disponible.');
$year     = substr($movie['release_date'] ?? '', 0, 4);
$note     = round($movie['vote_average'] ?? 0, 1);
$runtime  = $movie['runtime'] ?? 0;
$genres   = array_column($movie['genres'] ?? [], 'name');
?>

<style>
    .film-hero {
        position: relative;
        height: 55vh;
        min-height: 380px;
        background: url('<?= $backdrop ?>') center/cover no-repeat;
    }

    .film-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, var(--dark) 0%, rgba(10, 10, 15, 0.5) 60%, rgba(10, 10, 15, 0.2) 100%);
    }

    .film-detail-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .film-detail-top {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 2.5rem;
        margin-top: -120px;
        position: relative;
        z-index: 2;
    }

    .film-poster {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        border: 2px solid rgba(245, 197, 24, 0.15);
        flex-shrink: 0;
    }

    .film-poster img {
        width: 100%;
        display: block;
    }

    .film-info {
        padding-top: 2rem;
    }

    .film-genres {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }

    .genre-tag {
        font-size: 0.72rem;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid rgba(245, 197, 24, 0.3);
        color: var(--gold);
        font-weight: 500;
    }

    .film-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(2rem, 4vw, 3.2rem);
        line-height: 1;
        color: #fff;
        margin-bottom: 0.75rem;
    }

    .film-meta {
        display: flex;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .film-meta span {
        color: var(--muted);
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .film-meta .rating {
        color: var(--gold);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .film-overview {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.88rem;
        line-height: 1.75;
        margin-bottom: 1.5rem;
        max-width: 640px;
    }

    .film-btns {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Trailer embed */
    .trailer-section {
        margin-top: 3rem;
    }

    .trailer-embed {
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 16/9;
        background: var(--dark2);
    }

    .trailer-embed iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .no-trailer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.75rem;
        height: 200px;
        color: var(--muted);
        font-size: 0.85rem;
        border: 1px dashed rgba(255, 255, 255, 0.1);
        border-radius: 14px;
    }

    /* Cast scroll */
    .cast-section {
        margin-top: 3rem;
        margin-bottom: 1rem;
    }

    .cast-scroll-wrap {
        position: relative;
    }

    .cast-scroll {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding: 0.5rem 0 1rem;
        scroll-behavior: smooth;
    }

    .cast-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .actor-thumb {
        flex-shrink: 0;
        width: 120px;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: transform 0.2s;
    }

    .actor-thumb:hover {
        transform: translateY(-5px);
    }

    .actor-thumb img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 0.5rem;
        border: 2px solid rgba(245, 197, 24, 0.2);
        transition: border-color 0.2s;
        display: block;
    }

    .actor-thumb:hover img {
        border-color: var(--gold);
    }

    .actor-thumb-name {
        color: var(--text);
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.3;
    }

    .actor-thumb-char {
        color: var(--muted);
        font-size: 0.68rem;
        margin-top: 2px;
    }

    .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(10, 10, 15, 0.85);
        border: 1px solid rgba(245, 197, 24, 0.25);
        color: var(--gold);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition: background 0.2s;
    }

    .scroll-btn:hover {
        background: rgba(245, 197, 24, 0.15);
    }

    .scroll-btn.left {
        left: -18px;
    }

    .scroll-btn.right {
        right: -18px;
    }
</style>

<!-- HERO -->
<div class="film-hero"></div>

<div class="film-detail-wrap" style="padding-bottom:3rem;">
    <div class="film-detail-top">
        <div class="film-poster"><img src="<?= $poster ?>" alt="<?= $title ?>"></div>
        <div class="film-info">
            <?php if ($genres): ?>
                <div class="film-genres">
                    <?php foreach ($genres as $g): ?>
                        <span class="genre-tag"><?= htmlspecialchars($g) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <h1 class="film-title"><?= $title ?></h1>
            <div class="film-meta">
                <span class="rating">⭐ <?= $note ?>/10</span>
                <?php if ($year): ?><span><i class="bi bi-calendar3"></i> <?= $year ?></span><?php endif; ?>
                <?php if ($runtime > 0): ?><span><i class="bi bi-clock"></i> <?= $runtime ?> min</span><?php endif; ?>
            </div>
            <p class="film-overview"><?= $overview ?></p>
            <div class="film-btns">
                <?php if ($trailer): ?>
                    <button class="btn-gold" onclick="openTrailer('<?= $trailer ?>')">
                        <i class="bi bi-play-fill"></i> Bande annonce
                    </button>
                <?php endif; ?>
                <a href="index.php" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
            </div>
        </div>
    </div>

    <!-- BANDE ANNONCE EMBARQUÉE -->
    <div class="trailer-section">
        <h2 class="section-title"><span class="acc">Bande</span> Annonce</h2>
        <?php if ($trailer): ?>
            <div class="trailer-embed">
                <iframe src="https://www.youtube.com/embed/<?= $trailer ?>" allowfullscreen loading="lazy"></iframe>
            </div>
        <?php else: ?>
            <div class="no-trailer">
                <i class="bi bi-camera-video-off" style="font-size:2rem;"></i>
                <span>Aucune bande annonce disponible</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- CASTING -->
    <div class="cast-section">
        <h2 class="section-title"><span class="acc">Acteurs</span> du film</h2>
        <?php if (empty($cast)): ?>
            <p style="color:var(--muted); font-size:0.85rem;">Aucun acteur disponible.</p>
        <?php else: ?>
            <div class="cast-scroll-wrap">
                <button class="scroll-btn left" onclick="document.getElementById('castScroll').scrollLeft -= 300"><i class="bi bi-chevron-left"></i></button>
                <div class="cast-scroll" id="castScroll">
                    <?php foreach ($cast as $actor):
                        $photo = !empty($actor['profile_path'])
                            ? 'https://image.tmdb.org/t/p/w185' . $actor['profile_path']
                            : 'https://via.placeholder.com/185x185/1c1c26/7777aa?text=?';
                    ?>
                        <a class="actor-thumb" href="acteur.php?id=<?= $actor['id'] ?>">
                            <img src="<?= $photo ?>" alt="<?= htmlspecialchars($actor['name']) ?>">
                            <div class="actor-thumb-name"><?= htmlspecialchars($actor['name']) ?></div>
                            <?php if (!empty($actor['character'])): ?>
                                <div class="actor-thumb-char"><?= htmlspecialchars(mb_substr($actor['character'], 0, 20)) ?></div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <button class="scroll-btn right" onclick="document.getElementById('castScroll').scrollLeft += 300"><i class="bi bi-chevron-right"></i></button>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require("footer.php"); ?>