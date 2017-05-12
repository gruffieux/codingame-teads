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
    $nodes[$xi][] = $yi;
    $nodes[$yi][] = $xi;
}
error_log("total: " . count($nodes));

function bfs(array &$graph, $origin, $max, &$less) {
    //error_log("----- origin: " . $origin['index']);
    
    $list = $visit = $depth = $splice = [];
    $visit[$origin] = 1;
    $depth[$origin] = 0;
    $list[] = $origin;
    $maxDepth = -1;
    
    while (!empty($list)) {
        $u = array_pop($list);
        if (!isset($graph[$u])) {
        	continue;
        }
        foreach ($graph[$u] as $v) {
            if (empty($visit[$v])) {
                $visit[$v] = 1;
                $depth[$v] = $depth[$u] + 1;
                if ($max != -1 && $depth[$v] > $max) {
                	$splice[$u][] = $v;
                }
                array_unshift($list, $v);
                if ($depth[$v] > $maxDepth || $maxDepth == -1) {
                    $maxDepth = $depth[$v];
                }
            }
        }
    }
    
    // On enlève les nodes inutiles
    $less = '';
    foreach ($splice as $u => $childs) {
    	foreach ($childs as $v) {
    		if (isset($graph[$v])) {
    			$less .= $v . ',';
    			unset($graph[$v]);
    		}
    	}
    }
    
    return $maxDepth;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$list = $visit = [];
$u = key($nodes);
$list[] = $u;
$visit[$u] = 1;
$minHours = -1;

while (!empty($list)) {
	$u = array_pop($list);
	if (!isset($nodes[$u])) {
		continue;
	}
	$less = '';
	$hours = bfs($nodes, $u, $minHours, $less);
	if ($hours < $minHours || $minHours == -1) {
		$minHours = $hours;
	}
	foreach ($nodes[$u] as $v) {
		if (empty($visit[$v])) {
			$visit[$v] = 1;
			array_unshift($list, $v);
		}
	}
	$nexts = '';
	foreach ($list as $n) {
		$nexts .= $n . ',';
	}
	error_log("start: " . $u . " hours: " . $minHours . ' nexts: ' . $nexts);
	error_log("less: " . $less);
}

// The minimal amount of steps required to completely propagate the advertisement
echo($minHours . "\n");
?>