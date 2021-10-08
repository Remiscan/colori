import { doc } from 'https://deno.land/x/deno_doc@v0.14.0/mod.ts';
import { load as defaultLoad } from 'https://deno.land/x/deno_graph@0.2.0/lib/loader.ts';
import * as path from 'https://deno.land/std@0.105.0/path/mod.ts';


// Intercept the file loader and replace .js extensions with .ts for deno doc to find them
async function load(specifier) {
  const tsSpecifier = specifier.replace('.js', '.ts');
  return await defaultLoad(tsSpecifier);
}

// Get the data on all modules
const url = path.toFileUrl(path.resolve('./src/ts/main.ts')).href;
const docData = await doc(url, { load });

// Build the markdown documentation from the data
// This will all be wrapped inside a buildDoc function and exported
for (const node of docData) {
  console.log(node);
}

// Run with deno run --allow-read --allow-net=deno.land build.docs.js