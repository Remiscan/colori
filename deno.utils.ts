/** Takes a path (for example from Deno.realPath) and makes it into a URL. */
export function toUrl(path: string): string {
  return path.split('\\').map(e => encodeURI(e)).join('/');
}