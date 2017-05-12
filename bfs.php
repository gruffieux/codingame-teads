<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

class Node {
	public $id, $visit, $depth;
	private $links;
	public function __construct($id) {
		$this->id = $id;
		$this->visit = $this->depth = 0;
		$this->links = array();
	}
	public function link(Node $node) {
		$this->links[] = $node;
	}
	public function unlink(Node $node) {
		foreach ($this->links as $key => $link) {
			if ($link->id == $node->id) {
				//unset($this->links[$key]);
				array_splice($this->links, $key, 1);
				return true;
			}
		}
		
		return false;
	}
	public function destroy() {
		foreach ($this->links as $link) {
			$link->unlink($this);
		}
		
		//unset($this->links);
		array_splice($this->links, 0);
	}
	public function getLinks() {
		return $this->links;
	}
}

fscanf(STDIN, "%d",
    $n // the number of adjacency relations
);
$nodes = $nodes2 = [];
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%d %d",
        $xi, // the ID of a person which is adjacent to yi
        $yi // the ID of a person which is adjacent to xi
    );
    if (!isset($nodes[$xi])) {
        $nodes[$xi] = new Node($xi);
        $nodes2[$xi] = new Node($xi);
    }
    if (!isset($nodes[$yi])) {
        $nodes[$yi] = new Node($yi);
        $nodes2[$yi] = new Node($yi);
    }
    $nodes[$xi]->link($nodes[$yi]);
    $nodes[$yi]->link($nodes[$xi]);
    $nodes2[$xi]->link($nodes2[$yi]);
    $nodes2[$yi]->link($nodes2[$xi]);
}
//error_log("total: " . count($nodes));

function bfs(Node $start, $visit, array &$graph) {
    //error_log("start: " . $start->id);
    
    $list = [];
    $start->visit = $visit;
    $start->depth = 0;
    $list[] = $start;
    
    while (!empty($list)) {
        $node = array_pop($list);
        $links = $node->getLinks();
        $n = count($links);
        foreach ($links as $link) {
            if ($link->visit != $visit) {
                $link->visit = $visit;
                $link->depth = $node->depth + 1;
                $list[] = $link;
            }
        }
        if ($n <= 1) {
        	$node->destroy();
        	unset($graph[$node->id]);
        }
    }
    
    return $node;
}

function bfs2(Node $start, $visit) {
	$list = [];
	$start->visit = $visit;
	$start->depth = 0;
	$list[] = $start;
	$maxDepth = -1;

	while (!empty($list)) {
		$node = array_pop($list);
		$links = $node->getLinks();
		foreach ($links as $link) {
			if ($link->visit != $visit) {
				$link->visit = $visit;
				$link->depth = $node->depth + 1;
				$list[] = $link;
				if ($link->depth > $maxDepth || $maxDepth == -1) {
					$maxDepth = $link->depth;
				}
			}
		}
	}

	return $maxDepth;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

// Lancer le premier BFS afin d'élaguer le graph
$counter = 0;
$rem1 = $rem2 = [];
do {
	$counter++;
	$rem1 = $nodes;
	$key = key($nodes);
	if (isset($nodes[$key])) {
		$node = $nodes[$key];
		bfs($node, $counter, $nodes);
	}
	$rem2 = $nodes;
	$total = count($nodes);
}
while ($total > 2);

$remains = count($rem2) < 2 ? $rem1 : $rem2;

// Lancer le second BFS en partant de chacune des nodes restantes
$minHours = -1;
foreach ($remains as $key => $node) {
	$counter++;
	$hours = bfs2($nodes2[$key], $counter);
	if ($hours < $minHours || $minHours == -1) {
		$minHours = $hours;
	}
}

// The minimal amount of steps required to completely propagate the advertisement
echo($minHours . "\n");
?>