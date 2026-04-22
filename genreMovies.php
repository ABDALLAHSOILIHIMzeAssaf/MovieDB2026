<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>
<?php
$genreId = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int)$_GET['id'] : 28;
$noms = [
    28 => 'Action',
    12 => 'Aventure',
    16 => 'Animation',
    35 => 'Comédie',
    80 => 'Crime',
    99 => 'Documentaire',
    18 => 'Drame',
    10751 => 'Famille',
    14 => 'Fantastique',
    36 => 'Histoire',
    27 => 'Horreur',
    10402 => 'Musique',
    9648 => 'Mystère',
    10749 => 'Romance',
    878 => 'Science-Fiction',
    53 => 'Thriller',
    10752 => 'Guerre',
    37 => 'Western'
];
$nomGenre = $noms[$genreId] ?? 'Genre';
$films = moviesByGenre($genreId);
?>

<div style="max-width:1400px;margin:2.5rem auto;padding:0 2rem;">
    <h2 class="section-title"><span class="acc"><?= htmlspecialchars($nomGenre) ?></span></h2>
    <?php if (empty($films)): ?>
        <p style="color:var(--muted)">Aucun film trouvé.</p>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:1.25rem;">
            <?php foreach ($films as $m):
                $poster = !empty($m['poster_path']) ? 'https://image.tmdb.org/t/p/w342' . $m['poster_path'] : 'https://via.placeholder.com/342x513/13131a/7777aa?text=No+Image';
                $desc = mb_substr($m['overview'] ?? '', 0, 100);
            ?>
                <div class="movie-card">
                    <img src="<?= $poster ?>" alt="<?= htmlspecialchars($m['title']) ?>" loading="lazy">
                    <div class="movie-card-body">
                        <div class="movie-card-title"><?= htmlspecialchars($m['title']) ?></div>
                        <div class="movie-card-desc"><?= $desc ? htmlspecialchars($desc) . '...' : 'Aucune description.' ?></div>
                        <a href="film.php?id=<?= $m['id'] ?>" class="btn-voir">Voir plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require("footer.php"); ?>