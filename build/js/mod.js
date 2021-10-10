import { rollup } from 'https://deno.land/x/drollup@2.58.0+0.20.0/mod.ts';
import { terser } from 'https://deno.land/x/drollup@2.58.0+0.20.0/plugins/terser/mod.ts';
import * as path from 'https://deno.land/std@0.105.0/path/mod.ts';


// Build the import map
const importMap = { imports: {} };
for await (const entry of Deno.readDir('src/ts')) {
  if (!entry.isFile) continue;
  const oldPath = `./${entry.name.replace('.ts', '.js')}`;
  const newPath = `./${entry.name}`;
  importMap.imports[oldPath] = newPath;
}
const importMapUrl = path.toFileUrl(path.resolve('./src/ts')).href;


// Bundle the module files
export async function bundle() {
  
  // Bundle all modules from src/ts to colori.js

  const { files } = await Deno.emit('src/ts/main.ts', {
    bundle: 'module',
    compilerOptions: {
      target: 'es2015',
      module: 'es2015',
      removeComments: false,
      strict: true
    },
    importMap: importMap,
    importMapPath: `${importMapUrl}/`
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