<?php
function getProxy($url)
{
    // Essai direct (maison / connexion sans proxy)
    $response = @file_get_contents($url);
    if ($response !== false) return $response;

    // Fallback : proxy du lycée
    $options = ['http' => ['proxy' => 'tcp://172.16.0.54:8080', 'request_fulluri' => true]];
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    return ($response !== false) ? $response : null;
}
