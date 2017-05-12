<?php
$nodes = [];
$lines = file('data/graph.txt');
foreach ($lines as $line) {
	echo $line;
	$parts = explode(' ', $line, 2);
	$xi = $parts[0];
	$yi = $parts[1];
	if (!isset($nodes[$xi])) {
        $nodes[$xi] = [];
    }
    if (!isset($nodes[$yi])) {
        $nodes[$yi] = [];
    }
    $nodes[$xi][] = $yi;
    $nodes[$yi][] = $xi;
}

function bfs(array &$graph, $origin, $max) {
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
    
    foreach ($splice as $u => $childs) {
    	foreach ($childs as $v) {
    		unset($graph[$v]);
    	}
    	unset($graph[$u]);
    }
    
    return $maxDepth;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$list = $visit = [];
$start = key($nodes);
$list[] = $start;
$visit[$start] = 1;
$minHours = -1;

while (!empty($list)) {
	$start = array_pop($list);
	if (!isset($nodes[$start])) {
		continue;
	}
	$hours = bfs($nodes, $start, $minHours);
	if ($hours < $minHours || $minHours == -1) {
		$minHours = $hours;
	}
	
	// Ecriture du fichier dot
	$graph = "strict graph {\n";
	foreach ($nodes as $u => $childs) {
		foreach ($childs as $v) {
			$graph .= "$u -- $v";
		}
	}
	$graph .= "\n}\n";
	$filename = sprintf("data/graph-%d.dot", $start);
	$fp = fopen($filename, 'w');
	if ($fp) {
		fwrite($fp, $graph);
		fclose($fp);
	}
	
	foreach ($nodes[$start] as $v) {
		if (empty($visit[$v])) {
			$visit[$v] = 1;
			array_unshift($list, $v);
		}
	}
}


// The minimal amount of steps required to completely propagate the advertisement
echo $minHours;