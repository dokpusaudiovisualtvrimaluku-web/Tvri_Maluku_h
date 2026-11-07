const fs = require('fs');
const path = require('path');

const dir = 'C:/laragon/www/toma-digital-hub/resources/js/components/executive/crud/';
const files = fs.readdirSync(dir);
files.forEach(file => {
    const filePath = path.join(dir, file);
    if (filePath.endsWith('.jsx')) {
        let content = fs.readFileSync(filePath, 'utf8');
        if (content.includes('super_admin')) {
            content = content.replace(/super_admin/g, 'Super Admin');
            fs.writeFileSync(filePath, content);
            console.log('Fixed', file);
        }
    }
});

const bladeFile = 'C:/laragon/www/toma-digital-hub/resources/views/dashboard.blade.php';
let bladeContent = fs.readFileSync(bladeFile, 'utf8');
bladeContent = bladeContent.replace(/hasRole\('super_admin'\)/g, "hasRole('Super Admin')");
fs.writeFileSync(bladeFile, bladeContent);
console.log('Fixed dashboard.blade.php');

