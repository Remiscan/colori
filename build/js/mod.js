import * as path from 'https://deno.land/std@0.105.0/path/mod.ts';
import { rollup } from 'https://deno.land/x/drollup@2.58.0+0.20.0/mod.ts';
import { terser } from 'https://deno.land/x/drollup@2.58.0+0.20.0/plugins/terser/mod.ts';
import { getFiles } from 'https://deno.land/x/getfiles@v1.0.0/mod.ts';


// Build the import map
const basePath = 'src/ts';
const importMap = { imports: {} };
const modules = getFiles(basePath);
for (const module of modules) {
  const newPath = module.path.replace(basePath, '.');
  const oldPath = newPath.replace('.ts', '.js');
  importMap.imports[oldPath] = newPath;
}
const importMapUrl = path.toFileUrl(path.resolve(`./${basePath}`)).href;


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

  for (const [fileName, text] of Object.entries(files)) {
    switch (fileName) {
      case 'deno:///bundle.js': {
        await Deno.writeTextFile('./dist/colori.js', text);
        console.log('colori.js built');
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
  console.log('colori.min.js built');
  
}