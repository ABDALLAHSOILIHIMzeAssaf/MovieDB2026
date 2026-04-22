<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>
<?php $films = topRatedMovies(); ?>

<div style="max-width:1400px;margin:2.5rem auto;padding:0 2rem;">
    <h2 class="section-title"><span class="acc">Top</span> Rated</h2>
    <?php if (empty($films)): ?>
        <p style="color:var(--muted)">Données indisponibles.</p>
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
                        <div class="movie-card-desc" style="margin-bottom:0.35rem;">
                            <span style="color:var(--gold);font-size:0.78rem;font-weight:600;">⭐ <?= round($m['vote_average'], 1) ?>/10</span>
                        </div>
                        <div class="movie-card-desc"><?= $desc ? htmlspecialchars($desc) . '...' : 'Aucune description.' ?></div>
                        <a href="film.php?id=<?= $m['id'] ?>" class="btn-voir">Voir plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require("footer.php"); ?>