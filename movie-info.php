<?php

require_once __DIR__ . '/vendor/autoload.php';

if ($argc != 2) {
    die("Usage: php movie-info.php <file-path>\n");
}

$filePath = $argv[1];
if (!file_exists($filePath)) {
    die("File $filePath does not exist\n");
}

$contents = trim(file_get_contents($filePath), "\n");
$movieNames = explode("\n", $contents);

$config = new \Imdb\Config();
$config->language = 'es-ES';
$search = new \Imdb\TitleSearch($config);

echo implode(',', ['Title', 'Original title', 'Director', 'IMDB link']) .  "\n";
foreach ($movieNames as $movieName) {
    $results = $search->search($movieName);
    $movie = reset($results);
    $title = $movie->title();
    $origTitle = $movie->orig_title();
    $directorName = ($movie->director())[0]['name'];
    echo implode(',', [$title, $origTitle, $directorName, linkToImdb($movie->imdbid())]) .  "\n";
}

function linkToImdb($imdbId) {
    return 'http://www.imdb.com/title/tt' . $imdbId . '/';
}