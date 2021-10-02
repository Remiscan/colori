import { rollup } from "https://deno.land/x/drollup@2.52.7+0.19.1/mod.ts"
import { terser } from "https://deno.land/x/drollup@2.52.7+0.19.1/plugins/terser/mod.ts";

const options = {
  input: 'src/js/main.js',
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

const bundle = await rollup(options);
await Promise.all(options.output.map(output => bundle.write(output)));
await bundle.close();