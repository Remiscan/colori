import { rollup } from "https://deno.land/x/drollup@2.52.7+0.19.1/mod.ts"
import { terser } from "https://deno.land/x/drollup@2.52.7+0.19.1/plugins/terser/mod.ts";

const options = {
  input: 'src/ts/main.js',
  output: [
    {
      file: 'dist/colori.js',
      format: 'es',
      preferConst: true
    }, {
      file: 'dist/colori.min.js',
      format: 'es',
      preferConst: true,
      plugins: [terser()]
    }
  ]
};

const watcher = Deno.watchFs('./src/ts');
for await (const event of watcher) {
  console.log(`File system event: ${event.kind} > ${event.paths}`);

  // Check if a TypeScript file was modified. Do nothing if not.
  if ([event.paths].flat().filter(path => path.endsWith('.ts')).length === 0) continue;

  // Compite TypeScript to JavaScript
  const tsc = Deno.run({ cmd: ['cmd', '/c', 'tsc', 'src/ts/main.ts', '--target esnext', '--module esnext', '--strict'] });
  await tsc.status();

  // Bundle JavaScript modules together
  const bundle = await rollup(options);
  await Promise.all(options.output.map(output => bundle.write(output)));
  await bundle.close();

  // Delete temporary JavaScript compiled files
  const del = Deno.run({ cmd: [ 'cmd', '/c', 'del', 'src\\ts\\*.js' ]});
  await del.status();
}