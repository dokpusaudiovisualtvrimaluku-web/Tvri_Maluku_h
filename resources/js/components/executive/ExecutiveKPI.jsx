import React, { useState, useEffect } from 'react';
import RapatCRUD from './crud/RapatCRUD';
import MitraCRUD from './crud/MitraCRUD';
import ArsipCRUD from './crud/ArsipCRUD';
import NewsroomCRUD from './crud/NewsroomCRUD';
import DistribusiCRUD from './crud/DistribusiCRUD';
import PenugasanCRUD from './crud/PenugasanCRUD';
import KalenderCRUD from './crud/KalenderCRUD';
import axios from 'axios';
import Swal from 'sweetalert2';
import {
  BarChart, Bar, PieChart, Pie, Cell, ComposedChart, Area, Line, LabelList,
  XAxis, YAxis, Tooltip, ResponsiveContainer
} from 'recharts';
import { PolarArea, Radar } from 'react-chartjs-2';
import { Chart as ChartJS, RadialLinearScale, ArcElement, Tooltip as ChartJsTooltip, Legend as ChartJsLegend, PointElement, LineElement, Filler } from 'chart.js';

ChartJS.register(RadialLinearScale, ArcElement, ChartJsTooltip, ChartJsLegend, PointElement, LineElement, Filler);

import { 
  Users2, Globe, MonitorPlay, Server, UploadCloud, PlayCircle, Video, FileText, Link as LinkIcon, Edit, FileCheck, Tv, MapPin, Building, Briefcase, Calendar, CheckCircle2, TrendingUp, AlertTriangle, Map, Database, Compass, Clock
} from 'lucide-react';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8b5cf6'];
const DONUT_COLORS = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444']; 

const MAP_INDONESIA = [
  { name: 'TVRI Kota Ambon', top: '40%', left: '48%', size: 4, active: 5, inactive: 0, color: '#ef4444', glow: 'rgba(239,68,68,1)' },
  { name: 'Dinas Pekerjaan Umum dan Tata Ruang Provinsi Maluku Tengah', top: '35%', left: '55%', size: 3, active: 3, inactive: 1, color: '#f97316', glow: 'rgba(249,115,22,1)' },
  { name: 'TVRI Seram Bgn Barat', top: '32%', left: '45%', size: 2, active: 2, inactive: 0, color: '#eab308', glow: 'rgba(234,179,8,1)' },
  { name: 'TVRI Seram Bgn Timur', top: '45%', left: '70%', size: 2, active: 1, inactive: 2, color: '#10b981', glow: 'rgba(16,185,129,1)' },
  { name: 'TVRI Buru', top: '30%', left: '30%', size: 2, active: 2, inactive: 0, color: '#06b6d4', glow: 'rgba(6,182,212,1)' },
  { name: 'TVRI Buru Selatan', top: '38%', left: '25%', size: 2, active: 1, inactive: 1, color: '#3b82f6', glow: 'rgba(59,130,246,1)' },
  { name: 'Dinas Pekerjaan Umum dan Tata Ruang Provinsi Maluku Tenggara', top: '65%', left: '75%', size: 3, active: 3, inactive: 0, color: '#6366f1', glow: 'rgba(99,102,241,1)' },
  { name: 'TVRI Kota Tual', top: '63%', left: '72%', size: 3, active: 2, inactive: 1, color: '#8b5cf6', glow: 'rgba(139,92,246,1)' },
  { name: 'TVRI Kepulauan Aru', top: '75%', left: '85%', size: 4, active: 4, inactive: 0, color: '#d946ef', glow: 'rgba(217,70,239,1)' },
  { name: 'Dinas Pekerjaan Umum dan Tata Ruang Provinsi Maluku Barat Daya', top: '80%', left: '35%', size: 2, active: 0, inactive: 3, color: '#f43f5e', glow: 'rgba(244,63,94,1)' },
  { name: 'TVRI Kep. Tanimbar', top: '85%', left: '60%', size: 3, active: 2, inactive: 2, color: '#84cc16', glow: 'rgba(132,204,22,1)' }
];

const RADIAN = Math.PI / 180;
const renderCustomizedLabel = ({ cx, cy, midAngle, innerRadius, outerRadius, percent }) => {
  const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
  const x = cx + radius * Math.cos(-midAngle * RADIAN);
  const y = cy + radius * Math.sin(-midAngle * RADIAN);

  return (
    <text x={x} y={y} fill="white" textAnchor="middle" dominantBaseline="central" fontSize={11} fontWeight="bold">
      {`${(percent * 100).toFixed(0)}%`}
    </text>
  );
};

const PENUGASAN_PROGRAM_DATA = [
  { name: 'Berita & Info', value: 568, color: '#2563EB' },
  { name: 'Kebudayaan & Seni', value: 355, color: '#10B981' },
  { name: 'Anak & Edukasi', value: 284, color: '#F59E0B' },
  { name: 'Olahraga & Hiburan', value: 213, color: '#8B5CF6' }
];

const DashboardPanel = ({ number, title, children, className = '' }) => (
  <div className={`bg-[#0A1128]/90 backdrop-blur-md border border-[#1E293B] shadow-[0_0_20px_rgba(0,0,0,0.5)] rounded-xl p-3 md:p-4 flex flex-col relative overflow-hidden ${className}`}>
    {title && (
      <div className="flex items-center gap-3 mb-4 border-b border-blue-900/50 pb-2">
        {number && (
          <span className="bg-blue-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs font-bold shadow-[0_0_10px_rgba(37,99,235,0.8)]">
            {number}
          </span>
        )}
        <h2 className="text-blue-100 font-bold text-sm tracking-widest uppercase">{title}</h2>
      </div>
    )}
    <div className="flex-1 flex flex-col min-h-0">
      {children}
    </div>
  </div>
);

const ExecutiveKPI = ({ activeSubMenu }) => {
  if (activeSubMenu === 'menu1-rapat') return <RapatCRUD />;
  if (activeSubMenu === 'menu1-mitra') return <MitraCRUD />;
  if (activeSubMenu === 'menu1-arsip') return <ArsipCRUD />;
  if (activeSubMenu === 'menu1-newsroom') return <NewsroomCRUD />;
  if (activeSubMenu === 'menu1-distribusi') return <DistribusiCRUD />;
  if (activeSubMenu === 'menu1-penugasan') return <PenugasanCRUD />;
  if (activeSubMenu === 'menu1-kalender') return <KalenderCRUD />;

  const [metrics, setMetrics] = useState({
    rapat: { total: 0, hari_ini: 0, selesai: 0, terjadwal: 0, berjalan: 0, latest: [] },
    mitra: { reporter: 0, kontributor: 0, editor: 0, mc: 0, stakeholders: [], map: [], radar: { pusat: [], daerah: [] } },
    island_content: { total: 0, video: 0, pdf: 0, link: 0, kabupatens: [] },
    newsroom: { hari_ini: 0, breaking_news: 0, total: 0, trend_bulanan: [] },
    distribusi: { polar: [], total: 0 },
    penugasan: { direncanakan: 0, diproses: 0, selesai: 0, total: 0, latest: [] },
    kalender: { selesai: 0, berjalan: 0, total: 0, top_programs: [], chart_mingguan: [], heatmap_bulanan: [], top_stasiun: [] }
  });
  
  const [selectedMapInfo, setSelectedMapInfo] = useState(MAP_INDONESIA[0]);
  const [currentTime, setCurrentTime] = useState(new Date());

  useEffect(() => {
    axios.get('/api/dashboard/metrics').then(res => {
      if(res.data) setMetrics(res.data);
    }).catch(err => console.error(err));

    const timer = setInterval(() => setCurrentTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  const formattedDate = currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  const formattedTime = currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  return (
  <div className="space-y-4 animate-in fade-in duration-700 text-slate-200 bg-[#020617] p-2 md:p-4 rounded-2xl min-h-[calc(100vh-6rem)]">
    
    {/* Real-time Header */}
    <div className="flex flex-col sm:flex-row justify-between items-center bg-slate-900/60 p-4 rounded-xl border border-slate-800/80 mb-2 shadow-sm backdrop-blur-md">
       <div>
         <h1 className="text-xl font-bold text-white tracking-wide flex items-center gap-2">
            <Tv className="w-5 h-5 text-blue-500" />
            TOMA Executive Dashboard
         </h1>
         <p className="text-xs text-slate-400 mt-1">Sistem Pemantauan Terintegrasi Dinas Pekerjaan Umum dan Tata Ruang Provinsi Maluku</p>
       </div>
       <div className="mt-3 sm:mt-0 flex items-center bg-blue-900/20 border border-blue-800/50 px-4 py-2.5 rounded-lg shadow-inner">
         <Clock className="w-6 h-6 text-blue-400 mr-3 animate-pulse" />
         <div className="flex flex-col items-start sm:items-end">
           <span className="text-base font-bold text-blue-100 font-mono tracking-widest">{formattedTime} WIT</span>
           <span className="text-[10px] text-blue-300 uppercase tracking-wider">{formattedDate}</span>
         </div>
       </div>
    </div>

    <div className="grid grid-cols-1 xl:grid-cols-12 gap-4">
      
      {/* 1. Command Center & Smart Meeting (Rapat Koordinasi) */}
      <DashboardPanel number="1" title="Rapat Koordinasi" className="xl:col-span-4">
         <div className="grid grid-cols-2 gap-3 mb-4">
            <div className="bg-[#0F172A] p-3 rounded-lg border border-[#1E293B]">
               <span className="text-[10px] text-slate-400 block mb-1">Total Rapat</span>
               <span className="text-3xl font-bold text-white">{metrics.rapat.total}</span>
            </div>
            <div className="bg-[#0F172A] p-3 rounded-lg border border-blue-900/50">
               <span className="text-[10px] text-blue-400 block mb-1">Rapat Terjadwal</span>
               <span className="text-3xl font-bold text-blue-400">{metrics.rapat.terjadwal}</span>
            </div>
            <div className="bg-[#0F172A] p-3 rounded-lg border border-green-900/50">
               <span className="text-[10px] text-green-400 block mb-1">Selesai</span>
               <span className="text-3xl font-bold text-green-400">{metrics.rapat.selesai}</span>
            </div>
            <div className="bg-[#0F172A] p-3 rounded-lg border border-orange-900/50">
               <span className="text-[10px] text-orange-400 block mb-1">Sedang Berjalan</span>
               <span className="text-3xl font-bold text-orange-400">{metrics.rapat.berjalan}</span>
            </div>
         </div>
         <span className="text-[10px] text-slate-400 font-bold mt-2 mb-1 block tracking-widest uppercase">Produksi per Stasiun TVRI</span>
         <div className="w-full h-24 mb-3">
            <ResponsiveContainer width="100%" height="100%">
               <BarChart data={[
                  {name: 'Ambon', val: 65, fill: '#ef4444'},
                  {name: 'Tual', val: 42, fill: '#3b82f6'},
                  {name: 'Masohi', val: 38, fill: '#10b981'},
                  {name: 'Bula', val: 24, fill: '#f59e0b'},
                  {name: 'Namlea', val: 18, fill: '#8b5cf6'}
               ]} margin={{top: 5, right: 10, left: -30, bottom: 0}}>
                  <XAxis dataKey="name" stroke="#475569" fontSize={9} tickLine={false} axisLine={false} />
                  <YAxis stroke="#475569" fontSize={9} tickLine={false} axisLine={false} />
                  <Tooltip cursor={{fill: '#1e293b'}} contentStyle={{backgroundColor: '#0f172a', border: '1px solid #1e293b', borderRadius: '8px', fontSize: '10px'}} />
                  <Bar dataKey="val" radius={[3, 3, 0, 0]}>
                     {[...Array(5)].map((_, index) => (
                        <Cell key={`cell-${index}`} fill={['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'][index]} />
                     ))}
                  </Bar>
               </BarChart>
            </ResponsiveContainer>
         </div>

         <span className="text-[10px] text-slate-400 font-bold mb-2 block tracking-widest">RAPAT TERBARU</span>
         <ul className="text-xs space-y-2">
            {(metrics.rapat.latest.length > 0 ? metrics.rapat.latest : [{judul_rapat: 'Belum ada data', status: '-'}]).map((r, i) => (
               <li key={i} className="bg-slate-900/50 p-2 rounded flex justify-between items-center border border-slate-800">
                  <span className="truncate pr-2 font-medium">{r.judul_rapat}</span>
                  <span className={`text-[9px] px-2 py-0.5 rounded ${r.status==='Selesai' ? 'bg-green-900/50 text-green-400' : 'bg-blue-900/50 text-blue-400'}`}>{r.status}</span>
               </li>
            ))}
         </ul>
      </DashboardPanel>

      {/* 2. Digital Workforce & Stakeholder (Mitra) */}
      <DashboardPanel number="2" title="Mitra & Stakeholder" className="xl:col-span-4">
         <div className="flex flex-col h-full justify-between">
            <div>
               <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                  <div>
                     <h2 className="font-extrabold text-slate-300 text-sm flex items-center gap-2">
                        <Compass size={16} className="text-blue-500" />
                        Radar Chart: Evaluasi Stasiun
                     </h2>
                     <p className="text-[9px] text-slate-500 mt-1">Perbandingan TVRI Pusat vs Daerah</p>
                  </div>
                  <span className="text-[8px] font-bold bg-blue-900/30 text-blue-400 px-2 py-1 rounded shadow-sm border border-blue-900/50">
                     Radar
                  </span>
               </div>
               
               <div className="relative h-[250px] w-full mt-2 flex items-center justify-center">
                  <Radar
                     data={{
                        labels: ['Audience Reach', 'Kualitas HD', 'Efisiensi Dana', 'Tepat Jadwal', 'Konten Lokal', 'Engagement'],
                        datasets: [
                           {
                              label: 'TVRI Pusat',
                              data: metrics.mitra.radar.pusat && metrics.mitra.radar.pusat.length > 0 ? metrics.mitra.radar.pusat : [90, 95, 80, 88, 70, 85],
                              fill: true,
                              backgroundColor: 'rgba(0, 51, 153, 0.4)',
                              borderColor: '#3b82f6',
                              pointBackgroundColor: '#f59e0b',
                              pointBorderColor: '#fff',
                              pointHoverBackgroundColor: '#fff',
                              pointHoverBorderColor: '#3b82f6'
                           },
                           {
                              label: 'TVRI Daerah',
                              data: metrics.mitra.radar.daerah && metrics.mitra.radar.daerah.length > 0 ? metrics.mitra.radar.daerah : [70, 75, 88, 82, 95, 65],
                              fill: true,
                              backgroundColor: 'rgba(16, 185, 129, 0.3)',
                              borderColor: '#10b981',
                              pointBackgroundColor: '#10b981',
                              pointBorderColor: '#fff',
                              pointHoverBackgroundColor: '#fff',
                              pointHoverBorderColor: '#10b981'
                           }
                        ]
                     }}
                     options={{
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                           legend: {
                              position: 'top',
                              labels: { color: '#94a3b8', font: { size: 9, family: "'Inter', sans-serif" }, usePointStyle: true, boxWidth: 6 }
                           },
                           tooltip: {
                              backgroundColor: '#0f172a',
                              borderColor: '#1e293b',
                              borderWidth: 1,
                              titleFont: { size: 10 },
                              bodyFont: { size: 10 }
                           }
                        },
                        scales: {
                           r: {
                              angleLines: { color: '#1e293b' },
                              grid: { color: '#1e293b' },
                              pointLabels: { color: '#94a3b8', font: { size: 8 } },
                              ticks: { display: false, min: 40, max: 100 }
                           }
                        }
                     }}
                  />
               </div>
            </div>

            <div className="mt-4 pt-3 border-t border-slate-800 flex justify-start items-center text-[9px] text-slate-400 bg-slate-900/50 p-2 rounded-xl">
               <span className="flex items-center">
                  <strong className="text-blue-400 mr-1">Insight:</strong> TVRI Daerah unggul pada Konten Lokal.
               </span>
            </div>
         </div>
      </DashboardPanel>

      {/* 3. Island Content Network */}
      <DashboardPanel number="3" title="Island Content Network" className="xl:col-span-4">
         <div className="flex flex-col gap-3 h-full">
            <div className="flex items-center justify-between bg-[#0F172A] border border-[#1E293B] rounded-xl p-3 shadow-[inset_0_0_20px_rgba(0,0,0,0.5)]">
               <div>
                  <span className="block text-[10px] text-slate-400 font-semibold tracking-wider mb-1">TOTAL KONTEN DIGITAL</span>
                  <span className="text-4xl font-black text-white">{metrics.island_content.total}</span>
               </div>
               <div className="w-12 h-12 bg-blue-900/30 rounded-full flex items-center justify-center border border-blue-500/30">
                  <Database size={24} className="text-blue-400" />
               </div>
            </div>
            
            <div className="grid grid-cols-3 gap-2">
               <div className="bg-red-900/20 border border-red-900/50 p-2 rounded-lg text-center flex flex-col items-center justify-center">
                  <Video size={16} className="text-red-400 mb-1"/>
                  <span className="text-lg font-bold text-red-400">{metrics.island_content.video}</span>
                  <span className="text-[9px] text-slate-300">Video</span>
               </div>
               <div className="bg-orange-900/20 border border-orange-900/50 p-2 rounded-lg text-center flex flex-col items-center justify-center">
                  <FileText size={16} className="text-orange-400 mb-1"/>
                  <span className="text-lg font-bold text-orange-400">{metrics.island_content.pdf}</span>
                  <span className="text-[9px] text-slate-300">PDF</span>
               </div>
               <div className="bg-blue-900/20 border border-blue-900/50 p-2 rounded-lg text-center flex flex-col items-center justify-center">
                  <LinkIcon size={16} className="text-blue-400 mb-1"/>
                  <span className="text-lg font-bold text-blue-400">{metrics.island_content.link}</span>
                  <span className="text-[9px] text-slate-300">Link</span>
               </div>
            </div>

            <div className="flex-1 mt-2 bg-[#0F172A] border border-[#1E293B] rounded-xl p-3 flex flex-col relative overflow-hidden">
               <span className="block text-[10px] text-slate-400 font-bold tracking-wider mb-2 relative z-10">MAP INDONESIA (SEBARAN PRODUKSI STASIUN TVRI)</span>
               <div className="flex-1 flex gap-2 relative z-10 min-h-[160px]">
                  <div className="flex-1 relative bg-blue-950/20 rounded-lg overflow-hidden border border-blue-900/30">
                     <div className="absolute inset-0 opacity-10 bg-[linear-gradient(rgba(255,255,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.1)_1px,transparent_1px)] bg-[length:15px_15px]"></div>
                     
                     {MAP_INDONESIA.map((region, i) => (
                        <div 
                            key={i} 
                            className="absolute flex flex-col items-center group cursor-pointer transform -translate-x-1/2 -translate-y-1/2" 
                            style={{top: region.top, left: region.left}}
                            onClick={() => setSelectedMapInfo(region)}
                        >
                           <div className="relative flex items-center justify-center">
                              <div className={`absolute rounded-full opacity-30 animate-ping`} style={{backgroundColor: region.color, width: 8 + (region.size * 3), height: 8 + (region.size * 3)}}></div>
                              <div className="absolute rounded-full opacity-60" style={{backgroundColor: region.color, width: 4 + (region.size * 1.5), height: 4 + (region.size * 1.5)}}></div>
                              <div className="w-1.5 h-1.5 bg-white rounded-full relative z-10" style={{boxShadow: `0 0 10px 2px ${region.glow}`}}></div>
                           </div>
                           <span className="absolute top-full mt-1 text-[8px] whitespace-nowrap bg-[#020617]/90 text-slate-100 px-1.5 py-0.5 rounded border opacity-0 group-hover:opacity-100 transition-opacity z-20 shadow-lg font-medium" style={{borderColor: region.glow}}>
                              {region.name}
                           </span>
                        </div>
                     ))}
                  </div>
                  
                  <div className="w-[110px] shrink-0 bg-slate-900/50 rounded-lg border border-slate-800 p-2 flex flex-col justify-center items-center text-center">
                     <span className="text-[8px] text-slate-400 font-bold mb-1">INFO STASIUN</span>
                     <span className="text-[10px] font-bold text-white mb-2 leading-tight" style={{color: selectedMapInfo.color}}>{selectedMapInfo.name}</span>
                     <div className="flex flex-col gap-1.5 w-full">
                        <div className="bg-slate-800/80 p-1.5 rounded border border-slate-700">
                           <span className="text-[8px] text-slate-400 block">Produksi Aktif</span>
                           <span className="text-sm font-bold text-green-400">{selectedMapInfo.active}</span>
                        </div>
                        <div className="bg-slate-800/80 p-1.5 rounded border border-slate-700">
                           <span className="text-[8px] text-slate-400 block">Non-Aktif</span>
                           <span className="text-sm font-bold text-orange-400">{selectedMapInfo.inactive}</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </DashboardPanel>

      {/* 4. Digital Newsroom */}
      <DashboardPanel number="4" title="Digital Newsroom" className="xl:col-span-4">
         <div className="flex flex-col gap-4 h-full">
            <div className="bg-[#0F172A] p-4 rounded-lg border border-[#1E293B] flex items-center justify-between">
               <div>
                  <span className="text-[10px] text-slate-400 block mb-1 font-semibold tracking-wide">BERITA HARI INI</span>
                  <div className="flex items-end gap-1">
                     <span className="text-4xl font-bold text-blue-400">{metrics.newsroom.hari_ini}</span>
                  </div>
               </div>
               <div className="w-12 h-12 bg-blue-900/30 rounded-full flex items-center justify-center border border-blue-500/30">
                  <PlayCircle size={24} className="text-blue-400" />
               </div>
            </div>
            
            <div className="flex-1 w-full flex flex-col items-center justify-center relative min-h-[140px] bg-[#0F172A] border border-[#1E293B] rounded-lg p-2">
               <span className="text-[10px] text-slate-400 font-bold tracking-widest absolute top-2 left-2 uppercase">Tren Produksi Program per Bulan</span>
               <div className="w-full h-full mt-4">
                  <ResponsiveContainer width="100%" height="100%">
                     <ComposedChart data={metrics.newsroom.trend_bulanan && metrics.newsroom.trend_bulanan.length > 0 ? metrics.newsroom.trend_bulanan : Array(12).fill({name: '-', Volume: 0, Target: 20})} margin={{top: 10, right: 10, left: -25, bottom: 0}}>
                        <defs>
                           <linearGradient id="colorVolume" x1="0" y1="0" x2="0" y2="1">
                              <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.5}/>
                              <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                           </linearGradient>
                        </defs>
                        <XAxis dataKey="name" stroke="#475569" fontSize={9} tickLine={false} axisLine={false} />
                        <YAxis stroke="#475569" fontSize={9} tickLine={false} axisLine={false} />
                        <Tooltip cursor={{stroke: '#1e293b', strokeWidth: 1, strokeDasharray: '5 5'}} contentStyle={{backgroundColor: '#0f172a', border: '1px solid #1e293b', borderRadius: '8px', fontSize: '10px'}} itemStyle={{color: '#fff'}}/>
                        <Area type="monotone" dataKey="Volume" stroke="#3b82f6" strokeWidth={3} fillOpacity={1} fill="url(#colorVolume)" activeDot={{r: 6, fill: '#f59e0b', stroke: '#fff', strokeWidth: 2}} name="Volume Produksi" />
                        <Line type="monotone" dataKey="Target" stroke="#ef4444" strokeWidth={1.5} strokeDasharray="5 5" dot={false} name="Target Bulanan" />
                     </ComposedChart>
                  </ResponsiveContainer>
               </div>
            </div>
            
            <div className="bg-red-950/40 p-4 rounded-lg border border-red-900/50 flex items-center gap-4 mt-auto">
               <div className="w-12 h-12 rounded-full bg-red-900/50 flex items-center justify-center text-red-500">
                  <AlertTriangle size={24} className="animate-pulse" />
               </div>
               <div>
                 <span className="text-[10px] text-red-300 block font-semibold tracking-wide">BREAKING NEWS</span>
                 <div className="flex items-baseline gap-1">
                   <span className="text-3xl font-bold text-red-400">{metrics.newsroom.breaking_news}</span>
                 </div>
               </div>
            </div>
            <div className="bg-[#0F172A] p-4 rounded-lg border border-[#1E293B] flex items-center justify-between mt-auto">
               <span className="text-sm text-slate-300 font-medium">Total Seluruh Berita</span>
               <span className="text-xl font-bold text-white">{metrics.newsroom.total}</span>
            </div>
         </div>
      </DashboardPanel>

      {/* 5. Distribusi Konten */}
      <DashboardPanel number="5" title="Distribusi Konten" className="xl:col-span-4">
         <div className="flex flex-col h-full items-center justify-between">
            <div className="w-full flex justify-between items-start mb-2">
               <div>
                  <h2 className="font-extrabold text-slate-300 text-sm flex items-center gap-2">
                     Polar Area: Distribusi Siaran (Jam)
                  </h2>
                  <p className="text-[9px] text-slate-500 mt-1">Proporsi alokasi berdasarkan waktu harian</p>
               </div>
               <span className="text-[8px] font-bold bg-amber-900/30 text-amber-500 px-2 py-1 rounded shadow-sm border border-amber-900/50">
                  Polar Area
               </span>
            </div>
            
            <div className="flex-1 w-full flex flex-col mt-2 items-center min-h-[220px]">
               <div className="w-full h-full max-h-[220px]">
                 <PolarArea 
                   data={{
                     labels: ['Pagi (06-10)', 'Siang (10-15)', 'Sore (15-18)', 'Prime (18-22)', 'Malam (22-06)'],
                     datasets: [{
                       data: metrics.distribusi.polar && metrics.distribusi.polar.length > 0 ? metrics.distribusi.polar : [4, 5, 3, 8, 4],
                       backgroundColor: [
                         'rgba(59, 130, 246, 0.7)',
                         'rgba(16, 185, 129, 0.7)',
                         'rgba(245, 158, 11, 0.7)',
                         'rgba(0, 51, 153, 0.85)',
                         'rgba(139, 92, 246, 0.7)'
                       ],
                       borderWidth: 2,
                       borderColor: '#0F172A'
                     }]
                   }}
                   options={{
                     responsive: true,
                     maintainAspectRatio: false,
                     plugins: {
                       legend: { 
                         position: 'right', 
                         labels: { color: '#94a3b8', font: { size: 9, family: "'Inter', sans-serif" } } 
                       },
                       tooltip: {
                         backgroundColor: '#0f172a',
                         borderColor: '#1e293b',
                         borderWidth: 1,
                         callbacks: {
                           label: function(context) {
                             return ` ${context.label}: ${context.raw} Jam`;
                           }
                         }
                       }
                     },
                     scales: {
                       r: { 
                         grid: { color: '#1e293b' }, 
                         ticks: { display: false } 
                       }
                     }
                   }}
                 />
               </div>
            </div>

            <div className="mt-4 pt-3 border-t border-slate-800 text-[9px] text-slate-400 flex justify-between items-center bg-slate-900/50 p-2 rounded-xl w-full">
               <span className="flex items-center">
                 <strong className="text-blue-400 mr-1">Insight:</strong> Prime Time & Siang mendominasi siaran.
               </span>
            </div>
         </div>
      </DashboardPanel>

      {/* 6. Manajemen Penugasan */}
      <DashboardPanel number="6" title="Manajemen Penugasan" className="xl:col-span-4">
         <div className="grid grid-cols-3 gap-2 mb-3">
            <div className="bg-slate-800 p-2 rounded-lg text-center">
               <span className="text-[9px] text-slate-400 block mb-1">Direncanakan</span>
               <span className="text-xl font-bold text-white">{metrics.penugasan.direncanakan}</span>
            </div>
            <div className="bg-blue-900/30 border border-blue-900/50 p-2 rounded-lg text-center">
               <span className="text-[9px] text-blue-400 block mb-1">Diproses</span>
               <span className="text-xl font-bold text-blue-400">{metrics.penugasan.diproses}</span>
            </div>
            <div className="bg-green-900/30 border border-green-900/50 p-2 rounded-lg text-center">
               <span className="text-[9px] text-green-400 block mb-1">Selesai</span>
               <span className="text-xl font-bold text-green-400">{metrics.penugasan.selesai}</span>
            </div>
         </div>
         <span className="text-[10px] text-slate-400 font-bold mb-1 block tracking-widest uppercase">Persentase Jenis Program</span>
         <div className="w-full h-[150px] mb-3 relative flex flex-col items-center justify-center">
            <ResponsiveContainer width="100%" height="100%">
               <PieChart>
                  <Pie
                     data={PENUGASAN_PROGRAM_DATA}
                     cx="50%" cy="50%"
                     innerRadius={30}
                     outerRadius={65}
                     dataKey="value"
                     stroke="#0F172A"
                     strokeWidth={2}
                     labelLine={false}
                     label={renderCustomizedLabel}
                  >
                     {PENUGASAN_PROGRAM_DATA.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={entry.color} />
                     ))}
                  </Pie>
                  <Tooltip contentStyle={{backgroundColor: '#0f172a', border: '1px solid #1e293b', borderRadius: '8px', fontSize: '10px'}} itemStyle={{color: '#fff'}}/>
               </PieChart>
            </ResponsiveContainer>
            <div className="grid grid-cols-2 gap-x-2 gap-y-1 w-full px-2">
               {PENUGASAN_PROGRAM_DATA.map((entry, index) => (
                   <div key={index} className="flex items-center gap-1 text-[8px] text-slate-300">
                      <span className="w-2 h-2 rounded-sm" style={{backgroundColor: entry.color}}></span> {entry.name}
                   </div>
               ))}
            </div>
         </div>
         <span className="text-[10px] text-slate-400 font-bold mb-2 block tracking-widest">DETAIL LAPORAN MONITORING</span>
         <div className="flex-1 overflow-x-auto bg-[#0F172A] rounded border border-[#1E293B]">
            <table className="w-full text-left text-xs">
               <thead className="bg-slate-800/50 text-slate-400 text-[9px] uppercase tracking-wider">
                  <tr>
                     <th className="p-2 font-medium">Judul Tugas</th>
                     <th className="p-2 font-medium">Tim Bertugas</th>
                     <th className="p-2 font-medium text-center">Status</th>
                  </tr>
               </thead>
               <tbody className="divide-y divide-[#1E293B] text-slate-200">
                  {(metrics.penugasan.latest.length > 0 ? metrics.penugasan.latest : [{judul_tugas: 'Belum ada data', tim_bertugas: '-', status: '-'}]).map((t, i) => {
                     let statusColor = 'bg-slate-800 text-slate-400';
                     if (t.status === 'Selesai') statusColor = 'bg-emerald-900/40 text-emerald-400 border border-emerald-900/50';
                     if (t.status === 'Diproses') statusColor = 'bg-blue-900/40 text-blue-400 border border-blue-900/50';
                     if (t.status === 'Direncanakan') statusColor = 'bg-purple-900/40 text-purple-400 border border-purple-900/50';
                     
                     return (
                        <tr key={i} className="hover:bg-slate-800/30 transition-colors">
                           <td className="p-2 font-medium text-slate-300 truncate max-w-[150px]" title={t.judul_tugas}>{t.judul_tugas}</td>
                           <td className="p-2 text-slate-400 text-[10px] truncate max-w-[100px]" title={t.tim_bertugas}>{t.tim_bertugas || '-'}</td>
                           <td className="p-2 text-center">
                              <span className={`text-[9px] px-2 py-0.5 rounded-full whitespace-nowrap ${statusColor}`}>{t.status}</span>
                           </td>
                        </tr>
                     );
                  })}
               </tbody>
            </table>
         </div>
      </DashboardPanel>

      {/* 7. Kalender Produksi */}
      <DashboardPanel number="7" title="Kalender Produksi" className="xl:col-span-12">
         <div className="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
            <div className="col-span-1 flex flex-col justify-center gap-4">
               <div className="bg-[#0F172A] p-4 rounded-xl border border-[#1E293B] flex justify-between items-center">
                  <div>
                     <span className="text-[10px] text-slate-400 font-bold mb-1 block tracking-wider">TOTAL PROGRAM</span>
                     <span className="text-3xl font-black text-white">{metrics.kalender.total}</span>
                  </div>
                  <Calendar size={32} className="text-slate-600"/>
               </div>
               <div className="grid grid-cols-2 gap-3">
                  <div className="bg-blue-900/20 border border-blue-900/50 p-3 rounded-xl flex flex-col items-center justify-center text-center">
                     <span className="text-3xl font-bold text-blue-400 mb-1">{metrics.kalender.berjalan}</span>
                     <span className="text-[10px] text-slate-300 font-semibold tracking-wider">SEDANG BERJALAN</span>
                  </div>
                  <div className="bg-green-900/20 border border-green-900/50 p-3 rounded-xl flex flex-col items-center justify-center text-center">
                     <span className="text-3xl font-bold text-green-400 mb-1">{metrics.kalender.selesai}</span>
                     <span className="text-[10px] text-slate-300 font-semibold tracking-wider">PROGRAM SELESAI</span>
                  </div>
               </div>
            </div>

            <div className="col-span-1 bg-[#0F172A] rounded-xl border border-[#1E293B] p-4 flex flex-col">
               <span className="text-[10px] text-slate-400 font-bold tracking-widest mb-1 block flex items-center justify-between uppercase">
                  Calendar Heatmap Produksi
               </span>
               <p className="text-[9px] text-slate-500 mb-2">Intensitas Kegiatan Produksi Harian (Bulan Ini)</p>
               <div className="flex-1 w-full flex flex-col justify-center">
                  <div className="grid grid-cols-7 gap-1 text-center text-[9px] font-bold text-slate-500 mb-1">
                     <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                  </div>
                  <div className="grid grid-cols-7 gap-1 text-xs">
                     {(metrics.kalender.heatmap_bulanan && metrics.kalender.heatmap_bulanan.length > 0 ? metrics.kalender.heatmap_bulanan : Array(31).fill(0)).map((level, i) => {
                        let bg = 'bg-slate-800/80 text-slate-600 border border-slate-700/50';
                        if (level === 1) bg = 'bg-blue-900/50 text-blue-400 border border-blue-800/60 font-semibold';
                        if (level === 2) bg = 'bg-blue-600/70 text-blue-100 border border-blue-500/80 font-semibold';
                        if (level === 3) bg = 'bg-blue-500 text-white shadow-[0_0_8px_rgba(59,130,246,0.8)] font-bold';

                        return (
                           <div key={i} 
                                className={`h-6 flex items-center justify-center rounded ${bg} text-[9px] transition-transform hover:scale-110 cursor-pointer`}
                                title={`Tanggal ${i + 1}: Level Aktivitas ${level}`}
                           >
                              {i + 1}
                           </div>
                        );
                     })}
                  </div>
                  <div className="mt-2 flex items-center justify-between text-[8px] text-slate-400 border-t border-[#1E293B] pt-2">
                     <span>Rendah</span>
                     <div className="flex items-center gap-1">
                        <span className="w-2.5 h-2.5 bg-slate-800/80 border border-slate-700/50 rounded-[2px]"></span>
                        <span className="w-2.5 h-2.5 bg-blue-900/50 border border-blue-800/60 rounded-[2px]"></span>
                        <span className="w-2.5 h-2.5 bg-blue-600/70 border border-blue-500/80 rounded-[2px]"></span>
                        <span className="w-2.5 h-2.5 bg-blue-500 shadow-[0_0_4px_rgba(59,130,246,0.8)] rounded-[2px]"></span>
                     </div>
                     <span>Tinggi</span>
                  </div>
               </div>
            </div>

            <div className="col-span-1 bg-[#0F172A] rounded-xl border border-[#1E293B] p-4 flex flex-col">
               <span className="text-[10px] text-slate-400 font-bold tracking-widest mb-1 block uppercase">Produksi Program per Stasiun</span>
               <p className="text-[9px] text-slate-500 mb-3">Grafik Batang Horizontal (Top Stasiun)</p>
               <div className="flex-1 w-full min-h-[160px]">
                  <ResponsiveContainer width="100%" height="100%">
                     <BarChart data={metrics.kalender.top_stasiun && metrics.kalender.top_stasiun.length > 0 ? metrics.kalender.top_stasiun : [{nama: 'Belum ada', jumlah: 0}]} layout="vertical" margin={{top: 0, right: 30, left: 10, bottom: 0}}>
                        <XAxis type="number" hide />
                        <YAxis type="category" dataKey="nama" stroke="#475569" fontSize={9} tickLine={false} axisLine={false} width={80} />
                        <Tooltip cursor={{fill: '#1e293b'}} contentStyle={{backgroundColor: '#0f172a', border: '1px solid #1e293b', borderRadius: '8px', fontSize: '10px'}} itemStyle={{color: '#fff'}} />
                        <Bar dataKey="jumlah" radius={[0, 4, 4, 0]} barSize={12}>
                           {metrics.kalender.top_stasiun && metrics.kalender.top_stasiun.map((entry, index) => (
                              <Cell key={`cell-${index}`} fill={index === 0 ? '#f59e0b' : '#3b82f6'} />
                           ))}
                           <LabelList dataKey="jumlah" position="right" fill="#94a3b8" fontSize={9} fontWeight="bold" formatter={(val) => `${val} Prog`} />
                        </Bar>
                     </BarChart>
                  </ResponsiveContainer>
               </div>
            </div>
         </div>
      </DashboardPanel>

    </div>
  </div>
  );
};
export default ExecutiveKPI;
