<?php namespace colori {


  class GraphNode {
    private string $id;
    private bool $visited;
    private ?string $predecessorID;
    private array $links;

    public function __construct(array $array) {
      $this->id = $array['id'];
      $this->visited = false;
      $this->predecessorID = null;
      $this->links = $array['links'];
    }

    public function id(): string { return $this->id; }
    public function visited(): bool { return $this->visited; }
    public function links(): array { return $this->links; }
    public function predecessorID(): ?string { return $this->predecessorID; }

    public function visit(): void {
      $this->visited = true;
    }

    public function follow(GraphNode $node): void {
      $this->predecessorID = $node->id();
    }
  }


  class Graph {
    private array $nodes;
    private array $shortestPaths;

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

    public function getNode(string $id): GraphNode {
      $node = self::array_find(fn($node) => $node->id() === $id, $this->nodes);
      if ($node === null) throw new Exception("Node ". json_encode($id) ." does not exist");
      return $node;
    }

    public function shortestPath(string $startID, string $endID): array {
      if ($startID === $endID) return $this->shortestPath = [];

      $start = $this->getNode($startID);
      $end = $this->getNode($endID);

      $queue = [$start];
      $start->visit();

      // Let's build a breadth-first tree until we find the destination.
      $found = false;
      while (count($queue) > 0) {
        $current = array_shift($queue);
        if ($current->id() === $end->id()) {
          $found = true;
          break;
        }

        foreach ($current->links() as $neighbourID) {
          $neighbour = $this->getNode($neighbourID);
          if ($neighbour->visited() === false) {
            $neighbour->visit();
            $neighbour->follow($current);
            $queue[] = $neighbour;
          }
        }
      }

      if (!$found) throw new Exception("No path found from ". json_encode($startID) ." to ". json_encode($endID));

      // Let's backtrack through the tree to find the path.
      $path = [$end->id()];
      $current = $end;
      while ($current->predecessorID() != null) {
        $path[] = $current->predecessorID();
        $current = $this->getNode($current->predecessorID());
      }
      return array_reverse($path);
    }
  }


}