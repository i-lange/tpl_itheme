import fs from 'node:fs';
import archiver from 'archiver';
import pkg from './package.json' with { type: 'json' };

const filename = `tpl_itheme-${pkg.version}.zip`;
const output = fs.createWriteStream(filename);
const archive = archiver('zip', { zlib: { level: 9 } });

archive.pipe(output);

for (const dir of ['html', 'language', 'media']) {
  archive.directory(dir, dir);
}

for (const file of [
    'analytics.php',
    'component.php',
    'error.php',
    'footer.php',
    'head.php',
    'header.php',
    'index.php',
    'joomla.asset.json',	
    'offline.php',
    'templateDetails.xml'
]) {
  archive.file(file, { name: file });
}

await archive.finalize();

console.log('\n✅ Создан архив для установки! Файл: ' + filename);