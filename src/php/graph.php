<?php namespace colori {


  class GraphNode {
    public readonly string|int $id;
    public readonly array $links;
    public readonly mixed $data;
    private bool|string $visited = false;
    private ?GraphNode $predecessor = null;

    public function __construct(array $array) {
      $this->id = $array['id'];
      $this->links = $array['links'];
      $this->data = $array['data'] ?? null;
    }

    public function getVisitedState() { return $this->visited; }
    public function getPredecessor() { return $this->predecessor; }

    public function visit(bool|string $mark = true): void {
      $this->visited = $mark;
    }
    public function unvisit(): void {
      $this->visited = false;
    }

    public function follow(GraphNode $node): void {
      $this->predecessor = $node;
    }
    public function unfollow(): void {
      $this->predecessor = null;
    }
  }


  class Graph {
    private array $nodes;

    public function __construct(array $array) {
      $this->nodes = [];
      foreach ($array as $e) {
        $this->nodes[] = new GraphNode($e);
      }
    }

    public static function array_find(callable $callback, array $array): mixed {
      foreach($array as $k => $v) {
        if ($callback($v, $k)) return $v;
      }
      return null;
    }

    protected function getNode(string|int $id): GraphNode {
      $node = self::array_find(fn($node) => $node->id === $id, $this->nodes);
      if ($node === null) throw new \Exception("Node ". json_encode($id) ." does not exist");
      return $node;
    }

    protected function cleanUp(): void {
      foreach($this->nodes as $node) {
        $node->unvisit();
        $node->unfollow();
      }
    }

    public function shortestPath(string|int $startID, string|int $endID): array {
      if ($startID === $endID) return $this->shortestPath = [];

      try {
        $start = $this->getNode($startID);
        $end = $this->getNode($endID);

        $queue = [$start];
        $start->visit();

        // Let's build a breadth-first tree until we find the destination.
        $found = false;
        while (count($queue) > 0) {
          $current = array_shift($queue);
          if ($current->id === $end->id) {
            $found = true;
            break;
          }

          foreach ($current->links as $neighbourID) {
            $neighbour = $this->getNode($neighbourID);
            if ($neighbour->getVisitedState() === false) {
              $neighbour->visit();
              $neighbour->follow($current);
              $queue[] = $neighbour;
            }
          }
        }

        if (!$found) throw new \Exception("No path found from ". json_encode($startID) ." to ". json_encode($endID));

        // Let's backtrack through the tree to find the path.
        $path = [$end];
        $current = $end;
        $predecessor = $current->getPredecessor();
        while ($predecessor != null) {
          $path[] = $predecessor;
          $current = $predecessor;
          $predecessor = $current->getPredecessor();
        }

        $this->cleanUp();
        return array_reverse($path);
      } catch (\Throwable $error) {
        $this->cleanUp();
        throw $error;
      }
    }

    public function topologicalOrder(): array {
      // Source of the math: https://en.wikipedia.org/wiki/Topological_sorting#Depth-first_search
      $orderedList = [];
      $unvisitedNodes = $this->nodes;

      $visit = function(GraphNode $node) use (&$visit, &$orderedList, &$unvisitedNodes): void {
        if ($node->getVisitedState() === true) return;
        if ($node->getVisitedState() === 'temp') throw new \Exception("The graph is not a directed acyclic graph");

        $node->visit('temp'); // Mark visit as temporary to detect if we loop back to this node
        foreach ($node->links as $link) { $visit($this->getNode($link)); }
        $node->visit(true);

        $orderedList[] = $node;
      };

      try {
        while (count($unvisitedNodes) > 0) {
          $current = array_shift($unvisitedNodes);
          $visit($current);
        }

        $this->cleanUp();
        return array_reverse($orderedList);
      } catch (\Throwable $error) {
        $this->cleanUp();
        throw $error;
      }
    }
  }


}