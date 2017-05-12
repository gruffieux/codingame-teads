<?php
$fp = fopen('data/graph.txt', 'r');
$graph = "strict graph {\n";

if ($fp) {
    while (($line = fgets($fp)) !== false) {
        $parts = explode(' ', $line, 2);
        $graph .= $parts[0] . ' -- ' . $parts[1];
    }
    fclose($fp);
}

$graph .= "\n}\n";

$fp = fopen('data/graph.dot', 'w');

if ($fp) {
    fwrite($fp, $graph);
    fclose($fp);
}