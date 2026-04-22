<?php require("header.php"); ?>
<?php require("fonctions.php"); ?>

<div style="max-width:1200px;margin:2.5rem auto;padding:0 2rem;">
    <?php
    if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
        $q = trim($_GET['query']);
        $acteurs = searchActeurs($q);
        echo '<h2 class="section-title">Acteurs pour <span class="acc">' . htmlspecialchars($q) . '</span></h2>';
        if (empty($acteurs)) {
            echo '<p style="color:var(--muted);margin-top:1rem;">Aucun acteur trouvé.</p>';
        } else {
            echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:1.5rem;margin-top:1.5rem;">';
            foreach ($acteurs as $a) {
                if (empty($a['profile_path'])) continue;
                $photo = 'https://image.tmdb.org/t/p/w185' . $a['profile_path'];
                echo '<a href="acteur.php?id=' . $a['id'] . '" style="text-decoration:none;text-align:center;display:block;transition:transform 0.2s;" onmouseover="this.style.transform=\'translateY(-5px)\'" onmouseout="this.style.transform=\'none\'">
              <img src="' . htmlspecialchars($photo) . '" alt="' . htmlspecialchars($a['name']) . '" style="width:100px;height:100px;border-radius:50%;object-fit:cover;margin:0 auto 0.5rem;display:block;border:2px solid rgba(245,197,24,0.2);">
              <div style="color:var(--text);font-size:0.78rem;font-weight:500;">' . htmlspecialchars($a['name']) . '</div>
            </a>';
            }
            echo '</div>';
        }
    } else {
        echo '<p style="color:var(--muted);">Veuillez saisir un nom d\'acteur.</p>';
    }
    ?>
</div>

<?php require("footer.php"); ?>