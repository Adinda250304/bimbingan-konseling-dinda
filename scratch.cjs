const fs = require('fs');
const path = require('path');

function processDir(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            processDir(fullPath);
        } else if (fullPath.endsWith('.blade.php')) {
            let content = fs.readFileSync(fullPath, 'utf8');
            let modified = false;
            
            content = content.replace(/([a-zA-Z0-9_-]+)-\[([0-9.]+)px\]/g, (match, prefix, pxValue) => {
                const remValue = parseFloat(pxValue) / 16;
                modified = true;
                return `${prefix}-[${remValue}rem]`;
            });

            if (modified) {
                fs.writeFileSync(fullPath, content, 'utf8');
                console.log(`Updated ${fullPath}`);
            }
        }
    }
}

processDir('/Volumes/Untitled/project_laravel/bimbingan-konseling-dinda/resources/views');
console.log('Done!');
