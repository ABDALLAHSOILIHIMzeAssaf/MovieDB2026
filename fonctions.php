<?php
require_once("get-proxy.php");

$key = "9e43f45f94705cc8e1d5a0400d19a7b7";

function popularMovies()
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/movie/popular?api_key=$key&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true)['results'] ?? [];
}

function topRatedMovies()
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/movie/top_rated?api_key=$key&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true)['results'] ?? [];
}

function moviesByGenre($genreId)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/discover/movie?api_key=$key&language=fr-FR&with_genres=$genreId&sort_by=popularity.desc");
    if (!$r) return [];
    return json_decode($r, true)['results'] ?? [];
}

function getMovieDetail($id)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/movie/$id?api_key=$key&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true);
}

function getMovieTrailer($id)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/movie/$id/videos?api_key=$key&language=fr-FR");
    if (!$r) {
        // fallback anglais
        $r = getProxy("https://api.themoviedb.org/3/movie/$id/videos?api_key=$key");
        if (!$r) return null;
    }
    $results = json_decode($r, true)['results'] ?? [];
    foreach ($results as $v) {
        if ($v['type'] === 'Trailer' && $v['site'] === 'YouTube') return $v['key'];
    }
    // Prendre la première vidéo YouTube disponible
    foreach ($results as $v) {
        if ($v['site'] === 'YouTube') return $v['key'];
    }
    return null;
}

function getMovieCast($id)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/movie/$id/credits?api_key=$key&language=fr-FR");
    if (!$r) return [];
    return array_slice(json_decode($r, true)['cast'] ?? [], 0, 20);
}

function searchMovies($query)
{
    global $key;
    $q = urlencode($query);
    $r = getProxy("https://api.themoviedb.org/3/search/movie?api_key=$key&query=$q&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true)['results'] ?? [];
}

function searchActeurs($query)
{
    global $key;
    $q = urlencode($query);
    $r = getProxy("https://api.themoviedb.org/3/search/person?api_key=$key&query=$q&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true)['results'] ?? [];
}

function getActeurDetail($id)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/person/$id?api_key=$key&language=fr-FR");
    if (!$r) return [];
    return json_decode($r, true);
}

function getActeurFilms($id)
{
    global $key;
    $r = getProxy("https://api.themoviedb.org/3/person/$id/combined_credits?api_key=$key&language=fr-FR");
    if (!$r) return [];
    $cast = json_decode($r, true)['cast'] ?? [];
    usort($cast, fn($a, $b) => $b['popularity'] <=> $a['popularity']);
    return array_slice($cast, 0, 4);
}

function getActeurSuggestions($id)
{
    // On prend les acteurs du 1er film de l'acteur comme suggestions
    global $key;
    $films = getActeurFilms($id);
    if (empty($films)) return [];
    $movieId = $films[0]['id'] ?? null;
    if (!$movieId) return [];
    $r = getProxy("https://api.themoviedb.org/3/movie/$movieId/credits?api_key=$key&language=fr-FR");
    if (!$r) return [];
    $cast = json_decode($r, true)['cast'] ?? [];
    // Exclure l'acteur courant
    $cast = array_filter($cast, fn($a) => $a['id'] != $id);
    return array_slice(array_values($cast), 0, 8);
}

// Récupère les films populaires groupés par genre (top 6-25 = films 5 à 24)
function getPopularByGenre($movies)
{
    $genres = [
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
        878 => 'Sci-Fi',
        53 => 'Thriller',
        10752 => 'Guerre',
        37 => 'Western'
    ];
    $grouped = [];
    foreach ($movies as $movie) {
        foreach ($movie['genre_ids'] as $gid) {
            if (isset($genres[$gid])) {
                if (!isset($grouped[$gid])) $grouped[$gid] = ['name' => $genres[$gid], 'movies' => []];
                if (count($grouped[$gid]['movies']) < 5) $grouped[$gid]['movies'][] = $movie;
                break;
            }
        }
    }
    return $grouped;
}
