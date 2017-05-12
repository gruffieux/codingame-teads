<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $n // the number of adjacency relations
);
$nodes = [];
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%d %d",
        $xi, // the ID of a person which is adjacent to yi
        $yi // the ID of a person which is adjacent to xi
    );
    if (!isset($nodes[$xi])) {
        $nodes[$xi] = [];
    }
    if (!isset($nodes[$yi])) {
        $nodes[$yi] = [];
    }
    array_unshift($nodes[$xi], $yi);
    array_unshift($nodes[$yi], $xi);
    
}
error_log("total: " . count($nodes));

function bfs(array &$graph, $origin, $max) {
    //error_log("----- origin: " . $origin['index']);
    
    $list = $visit = $depth = [];
    $visit[$origin] = 1;
    $depth[$origin] = 0;
    $list[] = $origin;
    $maxDepth = -1;
    
    while (!empty($list)) {
        $u = array_pop($list);
        foreach ($graph[$u] as $v) {
            if (empty($visit[$v])) {
                $visit[$v] = 1;
                $depth[$v] = $depth[$u] + 1;
                if ($max != -1 && $depth[$v] > $max) {
                	return $max;
                }
                array_unshift($list, $v);
                if ($depth[$v] > $maxDepth || $maxDepth == -1) {
                    $maxDepth = $depth[$v];
                }
            }
        }
    }
    
    return $maxDepth;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$minHours = -1;
foreach ($nodes as $key => $node) {
	//error_log(var_export($nodes, true));
    //error_log("start: " . $key . ", hours: " . $minHours);
    $hours = bfs($nodes, $key, $minHours);
    if ($hours < $minHours || $minHours == -1) {
        $minHours = $hours;
    }
}

// The minimal amount of steps required to completely propagate the advertisement
echo($minHours . "\n");
?>