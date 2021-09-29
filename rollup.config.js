import { terser } from 'rollup-plugin-terser';

export default {
  input: 'src/js/main.js',
  output: [
    {
      file: 'dist/colori.js',
      format: 'es',
      preferConst: true
    }, {
      file: 'dist/colori.min.js',
      format: 'es',
      plugins: [terser()],
      preferConst: true
    }
  ]
};