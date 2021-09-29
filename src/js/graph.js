/** Graph node in a graph that will be traversed by a path finding algorithm. */
class GraphNode {
  constructor(object) {
    this.id = object.id;
    this.links = object.links;
    this.visited = false;
    this.predecessorID = null;
  }

  visit(mark = true) { this.visited = mark; }
  unvisit() { this.visited = false; }
  follow(node) { this.predecessorID = node.id; }
}



/** Graph that will be traversed by a path finding algorithm. */
export default class Graph {
  constructor(array) {
    this.nodes = array.map(e => new GraphNode(e));
  }

  getNode(id) {
    const node = this.nodes.find(node => node.id === id);
    if (typeof node === 'undefined') throw `Node ${JSON.stringify(id)} does not exist`;
    return node;
  }

  cleanUp() {
    for (const node of this.nodes) { node.unvisit(); }
  }

  shortestPath(startID, endID) {
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
    const path = [end.id];
    let current = end;
    while (current.predecessorID != null) {
      path.push(current.predecessorID);
      current = this.getNode(current.predecessorID);
    }

    this.cleanUp();
    return path.reverse();
  }

  topologicalOrder() {
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