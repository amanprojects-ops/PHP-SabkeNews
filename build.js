const esbuild = require('esbuild');
const { execSync } = require('child_process');

// Build CSS with Tailwind
console.log('🎨 Building CSS...');
execSync('npm run build:css', { stdio: 'inherit' });

// Build JavaScript bundle
console.log('📦 Building JavaScript...');
execSync('npm run build:js', { stdio: 'inherit' });

// Generate critical CSS
console.log('⚡ Extracting critical CSS...');
const criticalCss = execSync('tailwindcss -i ./src/css/critical.css -o ./public/assets/css/critical.css --minify', { encoding: 'utf8' });

// Optimize images
console.log('🖼️  Optimizing images...');
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const imageDir = './public/assets/postImage';
const outputDir = './public/assets/optimized';

if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

fs.readdirSync(imageDir)
    .filter(file => /\.(jpg|jpeg|png)$/i.test(file))
    .forEach(file => {
        const image = sharp(path.join(imageDir, file));
        
        // Generate WebP version
        image
            .webp({ quality: 80 })
            .toFile(path.join(outputDir, `${path.parse(file).name}.webp`))
            .catch(console.error);
            
        // Generate AVIF version
        image
            .avif({ quality: 65 })
            .toFile(path.join(outputDir, `${path.parse(file).name}.avif`))
            .catch(console.error);
            
        // Generate responsive sizes
        [320, 640, 1200].forEach(width => {
            image
                .resize(width)
                .webp({ quality: 80 })
                .toFile(path.join(outputDir, `${path.parse(file).name}-${width}.webp`))
                .catch(console.error);
        });
    });

// Generate static cache
console.log('Generating static cache...');
execSync('php scripts/generate-static.php', { stdio: 'inherit' });

console.log('Build complete!');