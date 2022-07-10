import { build } from 'https://deno.land/x/esbuild@v0.14.48/mod.js';


// Bundles all modules together
export async function bundle() {
  const commonOptions = {
    entryPoints: ['src/ts/main.ts'],
    bundle: true,
    format: 'esm'
  };

  // Bundle colori.js
  await build({...commonOptions, ...{
    outfile: 'dist/colori.js'
  }});
  console.log('colori.js built');

  // Bundle colori.min.js
  await build({...commonOptions, ...{
    minify: true,
    outfile: 'dist/colori.min.js'
  }});
  console.log('colori.min.js built');
}