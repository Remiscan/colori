<!doctype html>

<!-- ▼ Cache-busted files -->
<!--<?php versionizeStart(); ?>-->

<!-- Import map -->
<script defer src="/_common/polyfills/es-module-shims.js"></script>
<script type="importmap">
{
  "imports": {
    "colori": "/colori/lib/dist/colori.js"
  }
}
</script>

<link rel="stylesheet" href="./styles.css">

<!--<?php versionizeEnd(__DIR__); ?>-->

<script type="module">
  import Couleur from 'colori';

  const aquamarine = new Couleur('aquamarine');

  const spaces = Couleur.colorSpaces;
  const counts = new Map();
  const cache = new Map();
  for (const space of spaces) {
    counts.set(space.id, 0);
  }

  let totalTime0 = 0, instances0 = 0;
  let totalTimeN = 0, instancesN = 0;

  for (let i = 0; i <= 50; i++) {
    const randomSpace = spaces[Math.round((spaces.length - 1) * Math.random())];
    const count = counts.get(randomSpace.id);

    const start = performance.now();
    const values = aquamarine.valuesTo(randomSpace);
    const time = performance.now() - start;
    let logFunction = console.log;

    if (count === 0) {
      cache.set(randomSpace.id, values);
      totalTime0 += time;
      instances0++;
    } else {
      totalTimeN += time;
      instancesN++;
      const cachedValues = cache.get(randomSpace.id);
      if (!(values.every((v, k) => v === cachedValues[k]))) logFunction = console.error;
    }

    logFunction(`to ${randomSpace.id} for the ${count}th time`, values, `${time}ms`);
    counts.set(randomSpace.id, count + 1);
  }

  console.log('Average time for first conversion', totalTime0 / instances0, 'ms');
  console.log('Average time for redone conversion', totalTimeN / instancesN, 'ms');

  {
    aquamarine.okl = .23;

    const start = performance.now();
    const values = aquamarine.valuesTo('oklrch');
    const time = performance.now() - start;

    console.log('Time for new conversion', time);
  }
</script>