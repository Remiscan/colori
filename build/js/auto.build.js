import { bundle } from './mod.js';

const watcher = Deno.watchFs('./src/ts');
for await (const event of watcher) {
  console.log(`File system event:`, event);
  await bundle();
}