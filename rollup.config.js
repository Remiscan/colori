import { terser } from 'rollup-plugin-terser';

export default {
  input: 'src/main.js',
  output: [
    {
      file: 'colori.js',
      format: 'es'
    }, {
      file: 'colori.min.js',
      format: 'es',
      plugins: [terser({ keep_classnames: true, mangle: false, compress: false })]
    }
  ]
};