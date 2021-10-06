import { rollup } from 'https://deno.land/x/drollup@2.52.7+0.19.1/mod.ts';
import { terser } from 'https://deno.land/x/drollup@2.52.7+0.19.1/plugins/terser/mod.ts';


const importMap = {
  imports: {
    './color-spaces.js': './color-spaces.ts',
    './contrasts.js': './contrasts.ts',
    './conversion.js': './conversion.ts',
    './couleur.js': './couleur.ts',
    './css-formats.js': './css-formats.ts',
    './distances.js': './distances.ts',
    './graph.js': './graph.ts',
    './main.js': './main.ts',
    './named-colors.js': './named-colors.ts',
    './oklab-gamut.js': './oklab-gamut.ts',
    './palette.js': './palette.ts',
    './utils.js': './utils.ts'
  }
};
const importMapFolder = 'file:///D:/remi/Projets%20web/remiscanfr-lamp/www/colori/src/ts/';


export async function bundle() {
  
  // Bundle all modules from src/ts/ to colori.js

  const { files } = await Deno.emit('src/ts/main.ts', {
    bundle: 'module',
    compilerOptions: {
      target: 'esnext',
      module: 'esnext',
      removeComments: false,
      strict: true
    },
    importMap: importMap,
    importMapPath: importMapFolder
  });

  let nonMinifiedCode;
  for (const [fileName, text] of Object.entries(files)) {
    console.log(`emitted ${fileName} with a length of ${text.length}`);
    console.log(text.length);
    switch (fileName) {
      case 'deno:///bundle.js': {
        await Deno.writeTextFile('./dist/colori.js', text);
        nonMinifiedCode = text;
      } break;
    }
  }

  // Minify colori.js to colori.min.js

  const options = {
    input: 'dist/colori.js',
    output: {
      file: 'dist/colori.min.js',
      format: 'es',
      preferConst: true,
      plugins: [terser()]
    }
  };

  const bundle = await rollup(options);
  await bundle.write(options.output);
  await bundle.close();
}