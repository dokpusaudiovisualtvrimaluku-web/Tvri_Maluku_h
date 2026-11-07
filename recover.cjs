const fs = require('fs');
const lines = fs.readFileSync('C:/Users/MSI/.gemini/antigravity/brain/f011a6d5-22ab-4836-a470-f9a4a4e97940/.system_generated/logs/transcript_full.jsonl', 'utf8').split('\n');

for (let l of lines) {
  if (!l) continue;
  try {
    let d = JSON.parse(l);
    if (d.tool_calls) {
      for (let t of d.tool_calls) {
        if (t.function && t.function.name === 'default_api:write_to_file') {
          let args = JSON.parse(t.function.arguments);
          if (args.CodeContent && args.CodeContent.includes('const DashboardExecutive = () => {') && args.CodeContent.length > 50000) {
            fs.writeFileSync('C:/laragon/www/toma-digital-hub/resources/js/components/executive/ExecutiveKPI.jsx', args.CodeContent);
            console.log('Restored large file!');
            process.exit(0);
          }
        }
      }
    }
  } catch (e) {}
}
console.log('Not found');
