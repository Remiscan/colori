import { doc } from 'https://deno.land/x/deno_doc@v0.14.0/mod.ts';
import { load as defaultLoad } from 'https://deno.land/x/deno_graph@0.2.0/lib/loader.ts';
import { toUrl } from './deno.utils.ts';


// Get the full file URL base path for the modules
const path = await Deno.realPath('./src/ts/');
const url = `file:///${toUrl(path)}`;

// Intercept the file loader and replace .js extensions with .ts for deno doc to find them
async function load(specifier) {
  const tsSpecifier = specifier.replace('.js', '.ts');
  return await defaultLoad(tsSpecifier);
}

// Get the data on all modules
const docData = await doc(`${url}/main.ts`, { load });

// Build the markdown documentation from the data
// This will all be wrapped inside a buildDoc function and exported
for (const node of docData) {
  console.log(node);
}

// Run with deno run --allow-read --allow-net=deno.land build.docs.js