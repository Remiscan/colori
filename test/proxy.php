<link rel="stylesheet" href="./styles.css">

<h1>Testing proxy performance</h1>

<table class="results">
  <thead>
    <tr>
      <td>Test</td>
      <td>Duration (Couleur)</td>
      <td>Duration (Proxy)</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<p>Caching values in a Proxy works. There doesn't seem to be any time saved by doing that though...</p>

<script type="module">
  // ▼ ES modules cache-busted grâce à PHP
  /*<?php ob_start();?>*/

  import Couleur from '../dist/colori.js';

  /*<?php $imports = ob_get_clean();
  require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
  echo versionizeFiles($imports, __DIR__); ?>*/



  class CouleurProxy extends Couleur {
    constructor(...args) {
      super(...args);
      return new Proxy(this, {
        get: function(target, prop, receiver) {
          const proxy = this;
          if (typeof proxy.cache === 'undefined') proxy.resetCache(); // init cache
      
          const intercept = ['valuesTo'];
          if (typeof target[prop] === 'function') {
            return function(...args) {
              if (intercept.includes(prop)) {
                if (prop === 'valuesTo') {
                  const spaceID = typeof args[0] === 'string' ? args[0] : typeof args[0].id !== 'undefined' ? args[0].id : null;
                  const shouldCache = spaceID != null && !['rgb', 'srgb'].includes(spaceID);
                  const cachedValues = proxy.cache.values[spaceID];
                  if (shouldCache) {
                    if (cachedValues !== null) {
                      //console.log(`[proxy] Returning ${spaceID} values from cache`, cachedValues);
                      return cachedValues;
                    } else {
                      const values = Reflect.get(target, prop, receiver).apply(this, args);
                      console.log(`[proxy] Adding ${spaceID} values to cache`, values);
                      proxy.cache.values[spaceID] = values;
                      return values;
                    }
                  }
                }
              }
              return Reflect.get(target, prop, receiver).apply(this, args);
            }
          } else {
            return Reflect.get(target, prop, receiver);
          }
        },
      
        set: function(target, prop, value, receiver) {
          const proxy = this;
          const intercept = ['r', 'g', 'b', 'a'];
          if (intercept.includes(prop)) {
            proxy.resetCache();
          }
          Reflect.set(target, prop, value, receiver);
          return true;
        },

        resetCache: function() {
          const proxy = this;
          proxy.cache = {
            values: {}
          };
          for (const space of Couleur.colorSpaces) {
            proxy.cache.values[space.id] = null;
          }
        },
      });
    }
  }

  const color = new Couleur('red');
  const proxy = new CouleurProxy('red');

  const tests = [
    {
      name: 'lab expression x1',
      function: color => color.lab,
      iterations: 1
    }, {
      name: 'lab expression x10^2',
      function: color => color.lab,
      iterations: 100
    }, {
      name: 'lab expression x10^4',
      function: color => color.lab,
      iterations: 100000
    }
  ];

  const table = document.querySelector('.results > tbody');

  for (const test of tests) {
    let start;

    start = performance.now();
    for (let i = 0; i < test.iterations; i++) {
      const lab = color.lab;
    }
    const colorDuration = performance.now() - start;

    start = performance.now();
    for (let i = 0; i < test.iterations; i++) {
      const lab = proxy.lab;
    }
    const proxyDuration = performance.now() - start;

    const proxyBetter = proxyDuration < colorDuration;

    table.innerHTML += `
      <tr>
        <td>${test.name}</td>
        <td class="${proxyBetter ? 'no' : 'yes'}">${colorDuration} ms</td>
        <td class="${proxyBetter ? 'yes' : 'no'}">${proxyDuration} ms</td>
      </tr>
    `;
  }
</script>