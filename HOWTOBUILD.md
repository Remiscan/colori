# How to build colori

These instructions work on Windows, I assume the appropriate instructions for other operating systems are similar.

## JavaScript

To build **colori.js** and **colori.min.js**, you need to have installed:
- [deno](https://deno.land/#installation)

Then, if the `[auto] bundle colori.js on file change` task is running in VS Code, any change to any file in `src/ts` will trigger a build.

You can manually trigger it by either:
- running the `bundle colori.js` task in VS Code,
- or by running `deno run --allow-run --allow-read --allow-write=./dist --allow-net=deno.land --allow-env --unstable build.javascript.js` in a terminal.

## PHP

To build **colori.php**, you need to have installed:
- [deno](https://deno.land/#installation)
- [PHP 8+](https://www.php.net/downloads)

Then, if the `[auto] bundle colori.php on file change` task is running in VS Code, any change to any file in `src/php` will trigger a build.

You can manually trigger it by either:
- running the `bundle colori.php` task in VS Code,
- or by running `php build.php` in a terminal.