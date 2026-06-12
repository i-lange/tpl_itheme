import fs from 'node:fs';
import archiver from 'archiver';
import pkg from './package.json' with { type: 'json' };

const args = process.argv.slice(2);
const modeArg = args.find((arg) => arg.startsWith('--mode='));
const mode = modeArg ? modeArg.split('=')[1] : 'full';

if (!['full', 'update'].includes(mode)) {
  throw new Error(`Unknown pack mode "${mode}". Expected "full" or "update".`);
}

const isUpdate = mode === 'update';
const filename = `tpl_itheme-${pkg.version}${isUpdate ? '-update' : ''}.zip`;
const output = fs.createWriteStream(filename);
const archive = archiver('zip', { zlib: { level: 9 } });

archive.pipe(output);

for (const dir of ['html', 'language']) {
  archive.directory(dir, dir);
}

if (isUpdate) {
  for (const dir of ['media/js', 'media/images', 'media/scss']) {
    archive.directory(dir, dir);
  }
} else {
  archive.directory('media', 'media');
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
    'offline.php'
]) {
  archive.file(file, { name: file });
}

let manifest = fs.readFileSync('templateDetails.xml', 'utf8');

if (isUpdate) {
  manifest = manifest.replace(/^\s*<folder>css<\/folder>\r?\n/m, '');
}

archive.append(manifest, { name: 'templateDetails.xml' });

await archive.finalize();

console.log('\n✅ Создан архив для установки! Файл: ' + filename);
