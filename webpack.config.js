const path = require('path');

module.exports = {
  entry: './src/main.js',
  output: {
    filename: 'colori.js',
    path: path.resolve(__dirname, 'dist'),
  },
};