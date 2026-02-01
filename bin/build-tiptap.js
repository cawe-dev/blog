import * as esbuild from 'esbuild';

async function compile() {
    await esbuild.build({
        bundle: true,
        entryPoints: ['./resources/js/filament/plugins/reference-link.js'],
        outfile: './public/js/filament/plugins/reference-link.js',
        format: 'esm',
        platform: 'browser',
        minify: true,
        target: ['es2020'],
    });
    console.log('✅ Compiled!');
}
compile();