import { bundle } from './mod.js';


try {
  await bundle();
  Deno.exit();
} catch (error) {
  console.error(error);
}