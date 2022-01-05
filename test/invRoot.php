<?php
require_once '../src/php/utils.php';
?>

<!doctype html>
<link rel="stylesheet" href="./styles.css">

<h1>Compare Math.cbrt() in JS and my invRoot function in PHP</h1>

<table class="results">
  <thead>
    <tr>
      <td>n</td>
      <td>JS: Math.cbrt(n)</td>
      <td>JS: n ** (1/3)</td>
      <td>PHP: n ** (1/3)</td>
      <td>PHP: invRoot(n, 3)</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script type="module">
  const table = document.querySelector('.results > tbody');
  let js, expjs;
  let same;
  const NAN = NaN;

  <?php
  for ($n = -5; $n <= 5; $n = $n + .5) {
    $php = colori\utils\invRoot($n, 3);
    $expphp = $n ** (1/3);
    ?>
    js = Math.cbrt(<?=$n?>);
    expjs = (<?=$n?>) ** (1/3);
    same = (Math.round(10**3 * js) / 10**3) === (Math.round(10**3 * <?=$php?>) / 10**3);
    table.innerHTML += `
      <tr>
        <td><?=$n?></td>
        <td>${js}</td>
        <td class="${(Math.round(10**3 * js) / 10**3) === (Math.round(10**3 * expjs) / 10**3) ? 'yes' : 'no'}">${expjs}</td>
        <td class="${(Math.round(10**3 * js) / 10**3) === (Math.round(10**3 * <?=$expphp?>) / 10**3) ? 'yes' : 'no'}"><?=$expphp?></td>
        <td class="${(Math.round(10**3 * js) / 10**3) === (Math.round(10**3 * <?=$php?>) / 10**3) ? 'yes' : 'no'}"><?=$php?></td>
      </tr>`;
    <?php
  }
  ?>
</script>