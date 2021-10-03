type id = string | number;

/** Graph node in a graph that will be traversed by a path finding algorithm. */
class GraphNode {
  id: id;
  links: id[];
  visited: boolean | id = false;
  predecessor: GraphNode | null = null;

  /**
   * Builds a graph node from an object.
   * @param {object} object - An { id, links } object.
   */
  constructor(object: { id: id, links: id[] }) {
    this.id = object.id;
    this.links = object.links;
  }

  visit(mark: boolean | id = true) { this.visited = mark; }
  unvisit() { this.visited = false; }
  follow(node: GraphNode) { this.predecessor = node; }
  unfollow() { this.predecessor = null; }
}



/** Graph that will be traversed by a path finding algorithm. */
export default class Graph {
  nodes: GraphNode[];

  /**
   * Builds a graph from an array.
   * @param {object[]} array - Array of { id, links } objects.
   */
  constructor(array: Array<{ id: id, links: id[] }>) {
    this.nodes = array.map(e => new GraphNode(e));
  }

  /**
   * Finds a node.
   * @param {string} id - Identifier of the desired node.
   * @returns {GraphNode} The corresponding node.
   */
  getNode(id: id): GraphNode {
    const node = this.nodes.find(node => node.id === id);
    if (typeof node === 'undefined') throw `Node ${JSON.stringify(id)} does not exist`;
    return node;
  }

  /** Resets the nodes to their starting state. */
  cleanUp() {
    for (const node of this.nodes) {
      node.unvisit();
      node.unfollow();
    }
  }

  /**
   * Finds the shortest path between two nodes.
   * @param {string} startID - Identifier of the first node.
   * @param {string} endID - Identifier of the last node.
   * @returns {GraphNode[]} An array of node IDs, ordered from first to last along the shortest path.
   */
  shortestPath(startID: id, endID: id): GraphNode[] {
    // Source of the math: https://en.wikipedia.org/wiki/Breadth-first_search  
    if (startID === endID) return [];
  
    const start = this.getNode(startID);
    const end = this.getNode(endID);

    const queue = [start];
    start.visit();
    
    // Let's build a breadth-first tree until we find the destination.
    let found = false;
    walk: while (queue.length > 0) {
      const current = queue.shift();
      if (current.id === end.id) {
        found = true;
        break walk;
      }
  
      for (const neighbourID of current.links) {
        const neighbour = this.getNode(neighbourID);
        if (neighbour.visited === false) {
          neighbour.visit();
          neighbour.follow(current);
          queue.push(neighbour);
        }
      }
    }
  
    if (!found) throw `No path found from ${JSON.stringify(start.id)} to ${JSON.stringify(end.id)}`;
  
    // Let's backtrack through the tree to find the path.
    const path = [end];
    let current = end;
    while (current.predecessor != null) {
      path.push(current.predecessor);
      current = current.predecessor;
    }

    this.cleanUp();
    return path.reverse();
  }

  /**
   * Lists the graph nodes in a topological order.
   * @returns {GraphNode[]} The array of ordered graph nodes.
   */
  topologicalOrder(): GraphNode[] {
    // Source of the math: https://en.wikipedia.org/wiki/Topological_sorting#Depth-first_search
    const orderedList = [];
    const unvisitedNodes = [...this.nodes];

    const visit = node => {
      if (node.visited === true) return;
      if (node.visited === 'temp') throw 'The graph is not a directed acyclic graph';

      node.visit('temp'); // Mark visit as temporary to detect if we loop back to this node
      for (const link of node.links) { visit(link); }
      node.visit(true);

      orderedList.push(node);
    }

    while (unvisitedNodes.length > 0) {
      const current = unvisitedNodes.shift();
      visit(current);
    }

    this.cleanUp();
    return orderedList.reverse();
  }
}