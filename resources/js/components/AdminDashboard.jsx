import React, { useState } from 'react';
import {
  LineChart, Line, BarChart, Bar, PieChart, Pie, Cell,
  XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer
} from 'recharts';
import { 
  Users, Activity, Calendar as CalendarIcon, FileText, Settings, Video,
  Image as ImageIcon, Share2, UploadCloud, Download, MessageCircle, FolderOpen,
  MapPin, CheckSquare, Target, Clock, AlertTriangle, MonitorPlay, Database
} from 'lucide-react';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];

const AdminDashboard = () => {
  const [activeMenu, setActiveMenu] = useState('executive');

  // Dummy Data
  const productionData = [
    { name: 'Jan', Berita: 400, Program: 240, KMB: 200 },
    { name: 'Feb', Berita: 300, Program: 139, KMB: 221 },
    { name: 'Mar', Berita: 200, Program: 980, KMB: 229 },
    { name: 'Apr', Berita: 278, Program: 390, KMB: 200 },
  ];

  const distributionData = [
    { name: 'YouTube', value: 400 },
    { name: 'Instagram', value: 300 },
    { name: 'TikTok', value: 300 },
    { name: 'Facebook', value: 200 },
  ];

  const renderContent = () => {
    switch (activeMenu) {
      case 'executive':
        return (
          <div className="space-y-6">
            <h2 className="text-2xl font-bold text-slate-100">Executive Dashboard KPI</h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <div className="flex justify-between items-start">
                  <div>
                    <p className="text-sm text-slate-400">Total Produksi Berita</p>
                    <h3 className="text-2xl font-bold text-white mt-1">1,248</h3>
                  </div>
                  <div className="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                    <Video size={24} />
                  </div>
                </div>
                <div className="mt-4 flex items-center text-sm text-emerald-400">
                  <Activity size={16} className="mr-1" />
                  <span>+12% dari target</span>
                </div>
              </div>
              
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <div className="flex justify-between items-start">
                  <div>
                    <p className="text-sm text-slate-400">Arsip Baru</p>
                    <h3 className="text-2xl font-bold text-white mt-1">456</h3>
                  </div>
                  <div className="p-3 bg-purple-500/20 rounded-lg text-purple-400">
                    <FolderOpen size={24} />
                  </div>
                </div>
                <div className="mt-4 flex items-center text-sm text-emerald-400">
                  <Activity size={16} className="mr-1" />
                  <span>+5% minggu ini</span>
                </div>
              </div>

              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <div className="flex justify-between items-start">
                  <div>
                    <p className="text-sm text-slate-400">Penugasan Selesai</p>
                    <h3 className="text-2xl font-bold text-white mt-1">89%</h3>
                  </div>
                  <div className="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                    <CheckSquare size={24} />
                  </div>
                </div>
                <div className="mt-4 flex items-center text-sm text-slate-400">
                  <span>Dari 150 tugas aktif</span>
                </div>
              </div>

              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <div className="flex justify-between items-start">
                  <div>
                    <p className="text-sm text-slate-400">Mitra Aktif</p>
                    <h3 className="text-2xl font-bold text-white mt-1">45</h3>
                  </div>
                  <div className="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                    <Users size={24} />
                  </div>
                </div>
                <div className="mt-4 flex items-center text-sm text-slate-400">
                  <span>Pemerintah & Swasta</span>
                </div>
              </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <h3 className="text-lg font-bold text-white mb-4">Grafik Produksi</h3>
                <div className="h-80">
                  <ResponsiveContainer width="100%" height="100%">
                    <BarChart data={productionData}>
                      <CartesianGrid strokeDasharray="3 3" stroke="#334155" />
                      <XAxis dataKey="name" stroke="#94a3b8" />
                      <YAxis stroke="#94a3b8" />
                      <Tooltip contentStyle={{ backgroundColor: '#1e293b', border: 'none' }} />
                      <Legend />
                      <Bar dataKey="Berita" fill="#3b82f6" />
                      <Bar dataKey="Program" fill="#8b5cf6" />
                    </BarChart>
                  </ResponsiveContainer>
                </div>
              </div>
              
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700">
                <h3 className="text-lg font-bold text-white mb-4">Distribusi Platform</h3>
                <div className="h-80">
                  <ResponsiveContainer width="100%" height="100%">
                    <PieChart>
                      <Pie
                        data={distributionData}
                        cx="50%"
                        cy="50%"
                        innerRadius={80}
                        outerRadius={110}
                        paddingAngle={5}
                        dataKey="value"
                      >
                        {distributionData.map((entry, index) => (
                          <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                        ))}
                      </Pie>
                      <Tooltip contentStyle={{ backgroundColor: '#1e293b', border: 'none' }} />
                      <Legend />
                    </PieChart>
                  </ResponsiveContainer>
                </div>
              </div>
            </div>
          </div>
        );
      case 'archive':
        return (
          <div className="space-y-6">
            <h2 className="text-2xl font-bold text-slate-100">Digital Archive</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700 flex items-center gap-4 cursor-pointer hover:bg-slate-700 transition">
                <div className="p-4 bg-blue-500/20 rounded-xl text-blue-400">
                  <FolderOpen size={32} />
                </div>
                <div>
                  <h3 className="font-bold text-white">Arsip Kabupaten</h3>
                  <p className="text-sm text-slate-400">12 Kabupaten/Kota</p>
                </div>
              </div>
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700 flex items-center gap-4 cursor-pointer hover:bg-slate-700 transition">
                <div className="p-4 bg-purple-500/20 rounded-xl text-purple-400">
                  <FolderOpen size={32} />
                </div>
                <div>
                  <h3 className="font-bold text-white">Arsip Program</h3>
                  <p className="text-sm text-slate-400">Berita, Promo, KMB</p>
                </div>
              </div>
              <div className="bg-slate-800 p-6 rounded-xl border border-slate-700 flex items-center gap-4 cursor-pointer hover:bg-slate-700 transition">
                <div className="p-4 bg-emerald-500/20 rounded-xl text-emerald-400">
                  <FolderOpen size={32} />
                </div>
                <div>
                  <h3 className="font-bold text-white">Arsip Tahunan</h3>
                  <p className="text-sm text-slate-400">2024 - 2026</p>
                </div>
              </div>
            </div>
          </div>
        );
      case 'hybrid':
      case 'newsroom':
      case 'island':
      case 'assignment':
      case 'planning':
        return (
          <div className="bg-slate-800 p-8 rounded-xl border border-slate-700 text-center">
            <MonitorPlay size={48} className="mx-auto text-slate-500 mb-4" />
            <h2 className="text-xl font-bold text-white mb-2">Modul {activeMenu.toUpperCase()}</h2>
            <p className="text-slate-400">Modul ini sedang dalam tahap pengembangan (Fase 3).</p>
          </div>
        );
      default:
        return null;
    }
  };

  return (
    <div className="flex h-screen bg-slate-900 text-slate-200 overflow-hidden font-sans">
      {/* Sidebar */}
      <div className="w-64 bg-slate-800 border-r border-slate-700 flex flex-col">
        <div className="p-6 border-b border-slate-700 flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold">
            <MonitorPlay size={20} />
          </div>
          <div>
            <h1 className="font-bold text-white leading-tight">Admin Hub</h1>
            <p className="text-xs text-blue-400">TOMA Digital Hub</p>
          </div>
        </div>
        
        <div className="flex-1 overflow-y-auto py-4">
          <ul className="space-y-1 px-3">
            <li>
              <button onClick={() => setActiveMenu('executive')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'executive' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <Activity size={18} /> Executive Dashboard
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('archive')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'archive' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <Database size={18} /> Digital Archive
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('hybrid')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'hybrid' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <Share2 size={18} /> Hybrid Broadcasting
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('newsroom')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'newsroom' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <Video size={18} /> Digital Newsroom
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('island')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'island' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <MapPin size={18} /> Island Content
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('assignment')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'assignment' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <Target size={18} /> Digital Assignment
              </button>
            </li>
            <li>
              <button onClick={() => setActiveMenu('planning')} className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${activeMenu === 'planning' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white'}`}>
                <CalendarIcon size={18} /> Digital Planning
              </button>
            </li>
          </ul>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden bg-[#0a0f1c]">
        {/* Topbar */}
        <header className="h-20 border-b border-slate-800 bg-slate-900/50 backdrop-blur flex items-center justify-between px-8 z-10">
          <div>
            <h2 className="text-xl font-bold text-white">Administrator Portal</h2>
            <p className="text-sm text-slate-400">TVRI Stasiun Maluku</p>
          </div>
          <div className="flex items-center gap-4">
            <button className="p-2 text-slate-400 hover:text-white transition">
              <Settings size={20} />
            </button>
            <div className="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold border-2 border-slate-700">
              AD
            </div>
            <form method="POST" action="/logout" className="inline">
              <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''} />
              <button type="submit" className="text-sm text-rose-400 hover:text-rose-300 ml-4 font-medium px-4 py-2 bg-rose-500/10 rounded-lg">
                Log Out
              </button>
            </form>
          </div>
        </header>

        {/* Dynamic Content */}
        <main className="flex-1 overflow-y-auto p-8">
          {renderContent()}
        </main>
      </div>
    </div>
  );
};

export default AdminDashboard;
