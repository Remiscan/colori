import { rollup } from 'https://deno.land/x/drollup@2.52.7+0.19.1/mod.ts';
import { terser } from 'https://deno.land/x/drollup@2.52.7+0.19.1/plugins/terser/mod.ts';


// Build the import map
const importMap = { imports: {} };
for await (const entry of Deno.readDir('src/ts')) {
  if (!entry.isFile) continue;
  const oldPath = `./${entry.name.replace('.ts', '.js')}`;
  const newPath = `./${entry.name}`;
  importMap.imports[oldPath] = newPath;
}
const importMapPath = await Deno.realPath('./src/ts/');
const importMapUrl = `file:///${importMapPath.replaceAll('\\', '/').replaceAll(' ', '%20')}/`;


// Bundle the module files
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
    importMapPath: importMapUrl
    
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