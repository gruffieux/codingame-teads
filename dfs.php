<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

class Node {
	public $_id;
	public $_visited, $_first, $_last, $_next, $_parent;
	public function __construct($id) {
		$this->_id = $id;
		$this->_visited = -1;
		$this->_depth = 0;
		$this->_first = $this->_last = $this->_next = $this->_parent = null;
	}
	public function link(Node $node) {
		if ($this->_last) {
			$this->_last->_next = $node;
		}
		else {
			$this->_first = $node;
		}
		$this->_last = $node;
		$node->_parent = $this;
	}
}

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
        $nodes[$xi] = new Node($xi);
    }
    if (!isset($nodes[$yi])) {
        $nodes[$yi] = new Node($yi);
    }
    $nodes[$xi]->link($nodes[$yi]);
}
error_log("total: " . count($nodes));
//error_log(var_export($nodes, true));

function dfs(Node $s, $flag, &$maxDepth) {
	$s->_visited = $flag;
	$t = $s->_first;
	
	while ($t) {
		$t->_depth = $s->_depth + 1;
		if ($t->_depth > $maxDepth) {
			$maxDepth = $t->_depth;
			echo $maxDepth;
		}
		if ($t->_visited != $flag) {
			dfs($t, $flag, $maxDepth);
		}
		$t = $t->_next;
	}
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$minHours = -1;
foreach ($nodes as $key => $node) {
	$hours = 0;
    dfs($node, $key, $hours);
    //error_log("key: " . $key . ", hours: " . $hours);
    if ($hours < $minHours || $minHours == -1) {
        $minHours = $hours;
    }
}

// The minimal amount of steps required to completely propagate the advertisement
echo($minHours . "\n");
?>