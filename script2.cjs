const fs = require('fs');
const path = require('path');
const dir = 'C:/laragon/www/toma-digital-hub/resources/js/components/executive/crud';
const files = fs.readdirSync(dir).filter(f => f.endsWith('CRUD.jsx'));

files.forEach(file => {
    let content = fs.readFileSync(path.join(dir, file), 'utf8');

    // Fix empty state colspan
    if (file === 'ArsipCRUD.jsx') content = content.replace(/colSpan="3"/g, "colSpan={isSuperAdmin ? '3' : '2'}");
    if (file === 'DistribusiCRUD.jsx') content = content.replace(/colSpan="4"/g, "colSpan={isSuperAdmin ? '4' : '3'}");
    if (file === 'KalenderCRUD.jsx' || file === 'PenugasanCRUD.jsx') content = content.replace(/colSpan="6"/g, "colSpan={isSuperAdmin ? '6' : '5'}");
    if (['MitraCRUD.jsx', 'NewsroomCRUD.jsx', 'SocmedCRUD.jsx', 'SOPCRUD.jsx', 'RapatCRUD.jsx'].includes(file)) content = content.replace(/colSpan="5"/g, "colSpan={isSuperAdmin ? '5' : '4'}");

    fs.writeFileSync(path.join(dir, file), content);
});
console.log('Modified colspan in all CRUD files successfully');
