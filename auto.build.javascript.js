import { bundle } from './build.javascript.mod.js';

const watcher = Deno.watchFs('./src/ts');
for await (const event of watcher) {
  // Check if a TypeScript file was modified. Do nothing if not.
  if ([event.paths].flat().filter(path => path.endsWith('.ts')).length === 0) continue;
  console.log(`File system event:`, event);

  await bundle();
}