import * as esbuild from 'esbuild';

async function compile() {
    await esbuild.build({
        bundle: true,
        entryPoints: ['./resources/js/filament/plugins/reference-link.js', './resources/js/filament/plugins/media-indexer-link.js', './resources/js/filament/plugins/has-spoiler-link.js'],
        outdir: './public/js/filament/plugins/marks',
        format: 'esm',
        platform: 'browser',
        minify: true,
        target: ['es2020'],
    });
    console.log('✅ Compiled!');
}
compile();