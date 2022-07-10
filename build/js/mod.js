import { build } from 'https://deno.land/x/esbuild@v0.14.48/mod.js';


// Bundle the module files
export async function bundle() {
  const commonOptions = {
    entryPoints: ['src/ts/main.ts'],
    bundle: true,
    format: 'esm'
  };

  // Bundle colori.js
  {
    const options = {...commonOptions, ...{
      outfile: 'dist/colori.js'
    }};

    await build(options);
  }

  // Bundle colori.min.js
  {
    const options = {...commonOptions, ...{
      minify: true,
      outfile: 'dist/colori.min.js'
    }};

    await build(options);
  }
}