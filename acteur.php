<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>
<?php
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id          = (int)$_GET['id'];
$acteur      = getActeurDetail($id);
$topFilms    = getActeurFilms($id);
$suggestions = getActeurSuggestions($id);

if (empty($acteur)) {
    echo "<p style='padding:5rem 2rem;color:var(--muted)'>Acteur introuvable.</p>";
    require("footer.php");
    exit;
}

$photo  = !empty($acteur['profile_path'])
    ? 'https://image.tmdb.org/t/p/w342' . $acteur['profile_path']
    : 'https://via.placeholder.com/342x513/1c1c26/7777aa?text=No+Photo';
$nom    = htmlspecialchars($acteur['name'] ?? '');
$bio    = htmlspecialchars($acteur['biography'] ?? 'Biographie non disponible.');
$birth  = $acteur['birthday'] ?? null;
$lieu   = htmlspecialchars($acteur['place_of_birth'] ?? '');
$dept   = htmlspecialchars($acteur['known_for_department'] ?? '');
?>

<style>
    .acteur-wrap {
        max-width: 1100px;
        margin: 3rem auto;
        padding: 0 2rem;
    }

    .acteur-top {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    .acteur-photo {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(245, 197, 24, 0.15);
        position: sticky;
        top: 80px;
    }

    .acteur-photo img {
        width: 100%;
        display: block;
    }

    .acteur-nom {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        color: #fff;
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    .acteur-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .acteur-meta span {
        color: var(--muted);
        font-size: 0.81rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .acteur-meta strong {
        color: var(--text);
    }

    .acteur-bio {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.86rem;
        line-height: 1.8;
        max-height: 280px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    /* Top 4 films */
    .top-films-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .top-film-card {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 1rem;
        background: var(--dark2);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
        text-decoration: none;
        transition: transform 0.25s, border-color 0.25s;
    }

    .top-film-card:hover {
        transform: translateY(-4px);
        border-color: rgba(245, 197, 24, 0.25);
    }

    .top-film-card img {
        width: 90px;
        height: 130px;
        object-fit: cover;
        display: block;
    }

    .top-film-info {
        padding: 0.75rem 0.75rem 0.75rem 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .top-film-title {
        color: var(--text);
        font-weight: 600;
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 0.35rem;
    }

    .top-film-desc {
        color: var(--muted);
        font-size: 0.73rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .top-film-year {
        color: var(--gold);
        font-size: 0.72rem;
        margin-top: 0.4rem;
    }

    /* Suggestions acteurs */
    .suggestions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 1rem;
    }

    .suggestion-card {
        text-align: center;
        text-decoration: none;
        display: block;
        transition: transform 0.2s;
    }

    .suggestion-card:hover {
        transform: translateY(-4px);
    }

    .suggestion-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 0.4rem;
        border: 2px solid rgba(245, 197, 24, 0.2);
        transition: border-color 0.2s;
        display: block;
    }

    .suggestion-card:hover img {
        border-color: var(--gold);
    }

    .suggestion-name {
        color: var(--muted);
        font-size: 0.72rem;
        line-height: 1.3;
    }
</style>

<div class="acteur-wrap">

    <!-- INFO ACTEUR -->
    <div class="acteur-top">
        <div class="acteur-photo">
            <img src="<?= $photo ?>" alt="<?= $nom ?>">
        </div>
        <div>
            <h1 class="acteur-nom"><?= $nom ?></h1>
            <div class="acteur-meta">
                <?php if ($dept): ?><span><i class="bi bi-camera-reels"></i> <strong><?= $dept ?></strong></span><?php endif; ?>
                <?php if ($birth): ?><span><i class="bi bi-cake"></i> Né(e) le <strong><?= $birth ?></strong></span><?php endif; ?>
                <?php if ($lieu):  ?><span><i class="bi bi-geo-alt"></i> <strong><?= $lieu ?></strong></span><?php endif; ?>
            </div>
            <?php if ($bio && $bio !== 'Biographie non disponible.'): ?>
                <div class="acteur-bio"><?= $bio ?></div>
            <?php else: ?>
                <p style="color:var(--muted);font-size:0.85rem;">Biographie non disponible.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- TOP 4 FILMS -->
    <?php if (!empty($topFilms)): ?>
        <div style="margin-top:3.5rem;">
            <h2 class="section-title"><span class="acc">Top 4</span> Films</h2>
            <div class="top-films-grid">
                <?php foreach ($topFilms as $film):
                    $fPoster = !empty($film['poster_path'])
                        ? 'https://image.tmdb.org/t/p/w185' . $film['poster_path']
                        : 'https://via.placeholder.com/90x130/1c1c26/7777aa?text=?';
                    $fTitre  = htmlspecialchars($film['title'] ?? $film['name'] ?? 'Inconnu');
                    $fDesc   = htmlspecialchars(mb_substr($film['overview'] ?? '', 0, 120));
                    $fYear   = substr($film['release_date'] ?? $film['first_air_date'] ?? '', 0, 4);
                    $fId     = $film['id'];
                ?>
                    <a class="top-film-card" href="film.php?id=<?= $fId ?>">
                        <img src="<?= $fPoster ?>" alt="<?= $fTitre ?>">
                        <div class="top-film-info">
                            <div class="top-film-title"><?= $fTitre ?></div>
                            <?php if ($fDesc): ?>
                                <div class="top-film-desc"><?= $fDesc ?>...</div>
                            <?php endif; ?>
                            <?php if ($fYear): ?>
                                <div class="top-film-year">📅 <?= $fYear ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ACTEURS SUGGÉRÉS -->
    <?php if (!empty($suggestions)): ?>
        <div style="margin-top:3.5rem;">
            <h2 class="section-title"><span class="acc">Acteurs</span> Suggérés</h2>
            <div class="suggestions-grid">
                <?php foreach ($suggestions as $sug):
                    $sugPhoto = !empty($sug['profile_path'])
                        ? 'https://image.tmdb.org/t/p/w185' . $sug['profile_path']
                        : 'https://via.placeholder.com/80x80/1c1c26/7777aa?text=?';
                ?>
                    <a class="suggestion-card" href="acteur.php?id=<?= $sug['id'] ?>">
                        <img src="<?= $sugPhoto ?>" alt="<?= htmlspecialchars($sug['name']) ?>">
                        <div class="suggestion-name"><?= htmlspecialchars($sug['name']) ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div style="margin-top:2.5rem;">
        <a href="javascript:history.back()" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>
</div>

<?php require("footer.php"); ?>