<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

 class Node {
    public $_id;
    public $_visited, $_depth, $_first, $_last, $_next, $_parent;
    public function __construct($id) {
        $this->_id = $id;
        $this->_visited = -1;
        $this->_depth = -1;
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

function bfs(Node $origin, $max, $visitFlag) {
    //error_log("----- origin: " . $origin['index']);
    
    $list = [];
    $origin->_visited = $visitFlag;
    $origin->_depth = 0;
    $list[] = $origin;
    $maxDepth = -1;
    
    while (!empty($list)) {
        $node = array_pop($list);
        $n = $node->_first;
        //error_log("key: " . $visitFlag . ", node: " . $node->_id . ", depth: " . $node->_depth);
        while ($n) {
            if ($n->_visited != $visitFlag) {
                $n->_visited = $visitFlag;
                $n->_depth = $node->_depth + 1;
                if ($max != -1 && $n->_depth >= $max) {
                    return $max;
                }
                $list[] = $n;
                if ($n->_depth > $maxDepth || $maxDepth == -1) {
                    $maxDepth = $n->_depth;
                }
            }
            $n = $n->_next;
        }
        $n = $node->_parent;
        if ($n && $n->_visited != $visitFlag) {
            $n->_visited = $visitFlag;
            $n->_depth = $node->_depth + 1;
            $list[] = $n;
            if ($n->_depth > $maxDepth || $maxDepth == -1) {
                $maxDepth = $n->_depth;
            }
        }
    }
    
    return $maxDepth;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$minHours = -1;
foreach ($nodes as $key => $node) {
    $hours = bfs($node, $minHours, $key);
    //error_log("key: " . $key . ", hours: " . $hours);
    if ($hours < $minHours || $minHours == -1) {
        $minHours = $hours;
    }
}

// The minimal amount of steps required to completely propagate the advertisement
echo($minHours . "\n");
?>