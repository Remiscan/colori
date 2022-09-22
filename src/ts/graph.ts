type id = string | number;

/** Graph node in a graph that will be traversed by a path finding algorithm. */
class GraphNode {
  public readonly id: id;
  public readonly links: id[];
  public readonly data: any;
  private visited: boolean | string = false;
  private predecessor: GraphNode | null = null;

  /**
   * Builds a graph node from an object.
   * @param object An { id, links } object.
   */
  constructor(object: { id: id, links: id[], data?: any }) {
    this.id = object.id;
    this.links = object.links;
    this.data = object.data ?? null;
  }

  getVisitedState() { return this.visited; }
  getPredecessor() { return this.predecessor; }

  visit(mark: boolean | string = true) { this.visited = mark; }
  unvisit() { this.visited = false; }
  follow(node: GraphNode) { this.predecessor = node; }
  unfollow() { this.predecessor = null; }
}



/** Custom errors */
export class UndefinedNodeError extends Error {
  id: id;

  constructor(id: id) {
    super(`Node ${JSON.stringify(id)} does not exist`);
    this.id = id;
  }
}

export class PathNotFoundError extends Error {
  startID: id;
  endID: id;

  constructor(startID: id, endID: id) {
    super(`No path found from ${JSON.stringify(startID)} to ${JSON.stringify(endID)}`);
    this.startID = startID;
    this.endID = endID;
  }
}

export class CyclicGraphError extends Error {
  constructor() {
    super(`The graph is not a directed acyclic graph`);
  }
}



/** Graph that will be traversed by a path finding algorithm. */
export default class Graph {
  public readonly nodes: GraphNode[];

  /**
   * Builds a graph from an array.
   * @param array Array of { id, links, data? } objects.
   */
  constructor(array: Array<{ id: id, links: id[], data?: any }>) {
    this.nodes = array.map(e => new GraphNode(e));
  }

  /**
   * Finds a node.
   * @param id Identifier of the desired node.
   * @returns The corresponding node.
   */
  protected getNode(id: id): GraphNode {
    const node = this.nodes.find(node => node.id === id);
    if (node == null) throw new UndefinedNodeError(id);
    return node;
  }

  /** Resets the nodes to their starting state. */
  protected cleanUp() {
    for (const node of this.nodes) {
      node.unvisit();
      node.unfollow();
    }
  }

  /**
   * Finds the shortest path between two nodes.
   * @param startID Identifier of the first node.
   * @param endID Identifier of the last node.
   * @returns An array of graph nodes, ordered from first to last along the shortest path.
   */
  shortestPath(startID: id, endID: id): GraphNode[] {
    // Source of the math: https://en.wikipedia.org/wiki/Breadth-first_search  
    if (startID === endID) return [];
  
    try {
      const start = this.getNode(startID);
      const end = this.getNode(endID);

      const queue = [start];
      start.visit();
      
      // Let's build a breadth-first tree until we find the destination.
      let found = false;
      walk: while (queue.length > 0) {
        const current = queue.shift()!;
        if (current.id === end.id) {
          found = true;
          break walk;
        }
    
        for (const neighbourID of current.links) {
          const neighbour = this.getNode(neighbourID);
          if (neighbour.getVisitedState() === false) {
            neighbour.visit();
            neighbour.follow(current);
            queue.push(neighbour);
          }
        }
      }
    
      if (!found) throw new PathNotFoundError(start.id, end.id);
    
      // Let's backtrack through the tree to find the path.
      const path = [end];
      let current = end;
      let predecessor = current.getPredecessor();
      while (predecessor != null) {
        path.push(predecessor);
        current = predecessor;
        predecessor = current.getPredecessor();
      }

      this.cleanUp();
      return path.reverse();
    } catch (error) {
      this.cleanUp();
      throw error;
    }
  }

  /**
   * Lists the graph nodes in a topological order.
   * @returns The array of ordered graph nodes.
   */
  topologicalOrder(): GraphNode[] {
    // Source of the math: https://en.wikipedia.org/wiki/Topological_sorting#Depth-first_search
    const orderedList: GraphNode[] = [];
    const unvisitedNodes: GraphNode[] = [...this.nodes];

    const visit = (node: GraphNode) => {
      if (node.getVisitedState() === true) return;
      if (node.getVisitedState() === 'temp') throw new CyclicGraphError();

      node.visit('temp'); // Mark visit as temporary to detect if we loop back to this node
      for (const link of node.links) {
        const destination = this.getNode(link);
        visit(destination);
      }
      node.visit(true);

      orderedList.push(node);
    };

    try {
      while (unvisitedNodes.length > 0) {
        const current = unvisitedNodes.shift()!;
        visit(current);
      }

      this.cleanUp();
      return orderedList.reverse();
    } catch (error) {
      this.cleanUp();
      throw error;
    }
  }
}