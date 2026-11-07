const fs = require('fs');
const file = 'C:/laragon/www/toma-digital-hub/resources/js/components/executive/ExecutiveKPI.jsx';
let c = fs.readFileSync(file, 'utf8');

// 1. Add metrics state and useEffect
const stateCode = "  const [metrics, setMetrics] = useState({\n" +
"    performance: { berita_hari_ini: 0, target_berita: 20, program_selesai: 0, program_berjalan: 0, breaking_news: 0, konten_digital: 0, total_berita: 0 },\n" +
"    command_center: { ide: 0, tugas: 0, liputan: 0, upload: 0, editing: 0, qc: 0, on_air: 0, chart_mingguan: [] },\n" +
"    workforce: { reporter: 0, kontributor: 0, editor: 0, mc: 0 },\n" +
"    public_info: { distribusi_pie: [], top_programs: [] },\n" +
"    stakeholders: [],\n" +
"    map: []\n" +
"  });\n\n" +
"  useEffect(() => {\n" +
"    axios.get('/api/dashboard/metrics').then(res => {\n" +
"      setMetrics(res.data);\n" +
"    }).catch(err => console.error(err));\n" +
"  }, []);\n";

if (!c.includes('const [metrics, setMetrics] = useState')) {
    c = c.replace(/const \[selectedRegion, setSelectedRegion\] = useState\(\{/, stateCode + '\n  const [selectedRegion, setSelectedRegion] = useState({');
}

// 2. Change Panel Titles
c = c.replace(/title="Executive Performance Overview"/, 'title="Data Berita Newsroom & Kalender Produksi"');
c = c.replace(/title="Archipelagic Broadcasting Map"/, 'title="Data Penugasan & Kinerja Lapangan"');
c = c.replace(/title="Content Command Center"/, 'title="Data Penugasan"');
c = c.replace(/title="Digital Workforce Empowerment"/, 'title="Data Mitra"');
c = c.replace(/title="Public Information Dashboard"/, 'title="Distribusi Konten & Kalender Produksi"');
c = c.replace(/title="Stakeholder Collaboration"/, 'title="Data Mitra & Institusi"');

// 3. Replace Static Numbers with Metrics

// Panel 1: Performance
c = c.replace(/<span className="text-3xl font-bold text-white drop-shadow-\[0_2px_4px_rgba\(0,0,0,0.5\)\]">18<\/span>/, '<span className="text-3xl font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">{metrics.performance.berita_hari_ini}</span>');
c = c.replace(/<span className="text-\[10px\] text-slate-500 font-medium">Target: 20<\/span>/, '<span className="text-[10px] text-slate-500 font-medium">Target: {metrics.performance.target_berita}</span>');
c = c.replace(/<span className="text-2xl font-bold text-red-400">2<\/span>/, '<span className="text-2xl font-bold text-red-400">{metrics.performance.breaking_news}</span>');
c = c.replace(/<span className="text-2xl font-bold text-white">31<\/span>/, '<span className="text-2xl font-bold text-white">{metrics.performance.konten_digital}</span>');

// Panel 3: Command Center
c = c.replace(/val: 6/g, 'val: metrics.command_center.ide');
c = c.replace(/val: 12/g, 'val: metrics.command_center.tugas');
c = c.replace(/val: 15, color: 'bg-blue-900\/40/g, "val: metrics.command_center.liputan, color: 'bg-blue-900/40");
c = c.replace(/val: 15, color: 'bg-purple-900\/40/g, "val: metrics.command_center.upload, color: 'bg-purple-900/40");
c = c.replace(/val: 4/g, 'val: metrics.command_center.editing');
c = c.replace(/val: 2/g, 'val: metrics.command_center.qc');
c = c.replace(/val: 13/g, 'val: metrics.command_center.on_air');

// Panel 3: Chart
c = c.replace(/data=\{\[\s*\{\s*name:\s*'Sen',\s*val:\s*15\s*\},[\s\S]*?\]\}/, 'data={metrics.command_center.chart_mingguan.length > 0 ? metrics.command_center.chart_mingguan : [{name:"-", val:0}]}');

// Panel 4: Workforce
c = c.replace(/val: 12, icon: <Users2/, 'val: metrics.workforce.reporter, icon: <Users2');
c = c.replace(/val: 28, icon: <Globe/, 'val: metrics.workforce.kontributor, icon: <Globe');
c = c.replace(/val: 8, icon: <MonitorPlay/, 'val: metrics.workforce.editor, icon: <MonitorPlay');
c = c.replace(/val: 6, icon: <Server/, 'val: metrics.workforce.mc, icon: <Server');

// Panel 5: Pie Chart
c = c.replace(/data=\{\[\{n:'Budaya', v:35\},\{n:'Pendidikan', v:22\},\{n:'Pemerintah', v:18\},\{n:'Pariwisata', v:13\},\{n:'Ekonomi', v:12\}\]\}/, 'data={metrics.public_info.distribusi_pie.length > 0 ? metrics.public_info.distribusi_pie : [{n:"-", v:1}]}');

// Panel 5: Distribution legend
const legendCode = "{metrics.public_info.distribusi_pie.slice(0,4).map((d,i) => (\n" +
"    <div key={i} className=\"flex items-center gap-2\"><span className=\"w-2.5 h-2.5 rounded-sm\" style={{backgroundColor: DONUT_COLORS[i%DONUT_COLORS.length]}}></span> <span className=\"text-slate-300 w-16 truncate\" title={d.n}>{d.n}</span> <span className=\"font-bold text-white\">{d.v}</span></div>\n" +
"))}";
c = c.replace(/<div className="flex items-center gap-2"><span className="w-2.5 h-2.5 bg-blue-500 rounded-sm"><\/span> <span className="text-slate-300 w-16">Budaya<\/span> <span className="font-bold text-white">35%<\/span><\/div>[\s\S]*?13%<\/span><\/div>/, legendCode);

// Panel 5: Top Program
const topProgramCode = "<ol className=\"text-xs space-y-2.5 text-slate-200 list-decimal list-inside flex-1\">\n" +
"  {metrics.public_info.top_programs.length > 0 ? metrics.public_info.top_programs.map((p,i) => (\n" +
"     <li key={i} className=\"border-b border-slate-800 pb-2 flex justify-between\">\n" +
"       <span className=\"truncate pr-2\">{p.judul_produksi}</span>\n" +
"       <span className=\"text-[10px] bg-blue-900/50 text-blue-300 px-2 py-0.5 rounded whitespace-nowrap\">{p.kategori}</span>\n" +
"     </li>\n" +
"  )) : <li className=\"text-slate-500\">Belum ada data program</li>}\n" +
"</ol>";
c = c.replace(/<ol className="text-xs space-y-2.5 text-slate-200 list-decimal list-inside flex-1">[\s\S]*?<\/ol>/, topProgramCode);

// Panel 6: Stakeholder (Mitra)
c = c.replace(/\{name: 'Pemprov Maluku', k: 18, b: 15, l: 4, idx: 94\},[\s\S]*?\]\.map/, 
"metrics.stakeholders.length > 0 ? metrics.stakeholders : [{nama_mitra: 'Belum ada data', jenis_mitra: '-'}]).map");

c = c.replace(/<td className="p-3 font-medium flex items-center gap-2"><Building size=\{14\} className="text-slate-500"\/> \{row.name\}<\/td>/, '<td className="p-3 font-medium flex items-center gap-2"><Building size={14} className="text-slate-500"/> {row.nama_mitra}</td>');

c = c.replace(/<td className="p-3 text-center text-blue-300 font-bold">\{row\.k\}<\/td>\s*<td className="p-3 text-center">\{row\.b\}<\/td>\s*<td className="p-3 text-center">\{row\.l\}<\/td>\s*<td className="p-3">\s*<div className="flex items-center gap-2">\s*<div className="flex-1 h-2 bg-slate-800 rounded-full overflow-hidden"><div className="h-full bg-gradient-to-r from-green-600 to-green-400 rounded-full" style=\{\{width: "$\{row\.idx\}%"\}\}><\/div><\/div>\s*<span className="w-6 text-right text-\[10px\] font-bold">\{row\.idx\}%<\/span>\s*<\/div>\s*<\/td>/g, 
"<td className=\"p-3 text-center\" colSpan=\"4\"><span className=\"text-[10px] bg-slate-800 px-2 py-1 rounded\">{row.jenis_mitra}</span></td>");


fs.writeFileSync(file, c);
console.log('Done modifying component.');
