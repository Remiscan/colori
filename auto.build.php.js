const watcher = Deno.watchFs('./src/php');
for await (const event of watcher) {
  console.log(`File system event:`, event);
  const bundle = Deno.run({ cmd: ["php", "build.php"] });
  await bundle.status();
}