<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>

<div style="max-width:1400px;margin:2.5rem auto;padding:0 2rem;">
    <?php
    if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
        $q = trim($_GET['query']);
        $films = searchMovies($q);
        echo '<h2 class="section-title">Résultats pour <span class="acc">' . htmlspecialchars($q) . '</span></h2>';
        if (empty($films)) {
            echo '<p style="color:var(--muted);margin-top:1rem;">Aucun film trouvé.</p>';
        } else {
            echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:1.25rem;margin-top:1.5rem;">';
            foreach ($films as $m) {
                $poster = !empty($m['poster_path']) ? 'https://image.tmdb.org/t/p/w342' . $m['poster_path'] : 'https://via.placeholder.com/342x513/13131a/7777aa?text=No+Image';
                $desc = mb_substr($m['overview'] ?? '', 0, 100);
                echo '<div class="movie-card">
              <img src="' . htmlspecialchars($poster) . '" alt="' . htmlspecialchars($m['title']) . '" loading="lazy">
              <div class="movie-card-body">
                <div class="movie-card-title">' . htmlspecialchars($m['title']) . '</div>
                <div class="movie-card-desc">' . ($desc ? htmlspecialchars($desc) . '...' : 'Aucune description.') . '</div>
                <a href="film.php?id=' . $m['id'] . '" class="btn-voir">Voir plus</a>
              </div>
            </div>';
            }
            echo '</div>';
        }
    } else {
        echo '<p style="color:var(--muted);">Veuillez saisir un terme de recherche.</p>';
    }
    ?>
</div>

<?php require("footer.php"); ?>