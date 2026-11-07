const fs = require('fs');
const path = require('path');
const dir = 'C:/laragon/www/toma-digital-hub/resources/js/components/executive/crud';
const files = fs.readdirSync(dir).filter(f => f.endsWith('CRUD.jsx'));

files.forEach(file => {
    let content = fs.readFileSync(path.join(dir, file), 'utf8');
    
    // Add isSuperAdmin variable after the first useState if not already there
    if (!content.includes('const isSuperAdmin =')) {
        content = content.replace(/const \[data, setData\] = useState\(\[\]\);/, "const isSuperAdmin = window.authUser?.roles?.includes('super_admin');\n    const [data, setData] = useState([]);");
    }

    // Hide Tambah / Upload button
    content = content.replace(/<button onClick=\{\(\) => \{ setFormData.*?>[\s\S]*?<\/button>/, match => "{isSuperAdmin && (\n" + match + "\n)}");
    content = content.replace(/<button onClick=\{handleAddClick}.*?>[\s\S]*?<\/button>/, match => "{isSuperAdmin && (\n" + match + "\n)}");

    // Hide Aksi header column
    content = content.replace(/<th className="p-3 text-right">Aksi<\/th>/g, "{isSuperAdmin && <th className=\"p-3 text-right\">Aksi</th>}");

    // Hide Edit/Delete buttons td
    content = content.replace(/<td className="p-3 text-right">[\s\S]*?<button onClick=\{\(\) => handleEdit.*?<\/button>[\s\S]*?<button onClick=\{\(\) => handleDelete.*?<\/button>[\s\S]*?<\/td>/g, match => "{isSuperAdmin && (\n" + match + "\n)}");

    fs.writeFileSync(path.join(dir, file), content);
});
console.log('Modified all CRUD files successfully');
