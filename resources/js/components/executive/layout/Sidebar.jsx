import React, { useState } from 'react';
import { 
  Home, FolderOpen, Radio, Video, Globe, Briefcase, Calendar, 
  ChevronDown, ChevronRight, LayoutDashboard, Target, Users, Settings,
  Map, Activity, Share2, UploadCloud, MonitorPlay, MessageSquare, ShieldAlert
} from 'lucide-react';

const Sidebar = ({ isOpen, activeMenu, setActiveMenu, toggleSidebar }) => {
  const [expandedMenu, setExpandedMenu] = useState('menu1'); // Default expand Menu 1

  const menuItems = [
    {
      id: 'menu1',
      title: 'Executive Dashboard',
      icon: <LayoutDashboard size={18} />,
      subMenus: [
        { id: 'menu1-kpi', title: 'Dashboard KPI', icon: <Target size={16} /> },
        { id: 'menu1-kalender', title: 'Kalender Produksi', icon: <Calendar size={16} /> },
        { id: 'menu1-penugasan', title: 'Data Penugasan', icon: <Briefcase size={16} /> },
        { id: 'menu1-mitra', title: 'Data Jejaring Mitra', icon: <Users size={16} /> },
        { id: 'menu1-newsroom', title: 'Newsroom', icon: <Video size={16} /> },
        { id: 'menu1-distribusi', title: 'Distribusi Antar Unit', icon: <Share2 size={16} /> },
        { id: 'menu1-arsip', title: 'Data Arsip', icon: <FolderOpen size={16} /> },
        { id: 'menu1-rapat', title: 'Rapat Koordinasi', icon: <MessageSquare size={16} /> },
      ]
    },
    {
      id: 'menu2',
      title: 'Digital Archive',
      icon: <FolderOpen size={18} />,
      subMenus: [
        { id: 'menu2-kabupaten', title: 'Arsip Kabupaten', icon: <Map size={16} /> },
        { id: 'menu2-program', title: 'Arsip Program', icon: <MonitorPlay size={16} /> },
        { id: 'menu2-tahunan', title: 'Arsip Tahunan', icon: <Calendar size={16} /> },
      ]
    },
    {
      id: 'menu3',
      title: 'Hybrid Broadcasting',
      icon: <Radio size={18} />,
      subMenus: [
        { id: 'menu3-ig', title: 'Instagram Reels', icon: <Share2 size={16} /> },
        { id: 'menu3-tiktok', title: 'TikTok', icon: <Share2 size={16} /> },
        { id: 'menu3-yt', title: 'YouTube', icon: <Video size={16} /> },
        { id: 'menu3-fb', title: 'Facebook', icon: <Users size={16} /> },
      ]
    },
    {
      id: 'menu4',
      title: 'Digital Newsroom',
      icon: <Video size={18} />,
      subMenus: [
        { id: 'menu4-cloud', title: 'Cloud Newsroom', icon: <UploadCloud size={16} /> },
        { id: 'menu4-materi', title: 'Materi Berita Audio Visual', icon: <FolderOpen size={16} /> },
      ]
    },
    {
      id: 'menu5',
      title: 'Island Content Network',
      icon: <Globe size={18} />,
      subMenus: [
        { id: 'menu5-mitra', title: 'Mitra Konten Publik', icon: <Users size={16} /> },
        { id: 'menu5-cloud', title: 'Cloud Materi Berita', icon: <UploadCloud size={16} /> },
        { id: 'menu5-kurasi', title: 'Kurasi dan Tayangan', icon: <ShieldAlert size={16} /> },
      ]
    },
    {
      id: 'menu6',
      title: 'Digital Assignment',
      icon: <Briefcase size={18} />,
      subMenus: [
        { id: 'menu6-dashboard', title: 'Dashboard Penugasan', icon: <Target size={16} /> },
      ]
    },
    {
      id: 'menu7',
      title: 'Digital Planning',
      icon: <Calendar size={18} />,
      subMenus: [
        { id: 'menu7-kalender', title: 'Kalender Produksi Digital', icon: <Calendar size={16} /> },
        { id: 'menu7-sop', title: 'SOP Perencanaan Digital', icon: <Settings size={16} /> },
      ]
    }
  ];

  const toggleMenu = (menuId) => {
    if (expandedMenu === menuId) {
      setExpandedMenu('');
    } else {
      setExpandedMenu(menuId);
    }
  };

  return (
    <aside className={`bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-all duration-300 flex flex-col ${isOpen ? 'w-72' : 'w-20'}`}>
      {/* Brand */}
      <div className="h-16 flex items-center justify-between px-4 border-b border-slate-200 dark:border-slate-800">
        <div className={`flex items-center gap-3 overflow-hidden ${isOpen ? 'opacity-100' : 'opacity-0 hidden'}`}>
          <span className="font-bold text-lg tracking-wide text-slate-800 dark:text-white whitespace-nowrap">
            Dinas Pekerjaan Umum dan Tata Ruang Provinsi Maluku
          </span>
        </div>
        {!isOpen && (
           <div className="w-full flex justify-center">
              <span className="font-bold text-lg tracking-wide text-blue-600 dark:text-blue-400">
                TVRI
              </span>
           </div>
        )}
      </div>

      {/* Menu List */}
      <div className="flex-1 overflow-y-auto py-4 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-700">
        <ul className="menu w-full p-2 gap-1 text-slate-600 dark:text-slate-300">
          {menuItems.map((menu) => (
            <li key={menu.id}>
              {isOpen ? (
                <>
                  <button 
                    onClick={() => toggleMenu(menu.id)}
                    className={`flex items-center justify-between w-full p-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors ${expandedMenu === menu.id ? 'bg-slate-50 dark:bg-slate-800/50 font-semibold' : ''}`}
                  >
                    <div className="flex items-center gap-3">
                      <span className={`${expandedMenu === menu.id ? 'text-blue-600 dark:text-blue-400' : ''}`}>
                        {menu.icon}
                      </span>
                      <span className="whitespace-nowrap">{menu.title}</span>
                    </div>
                    {expandedMenu === menu.id ? <ChevronDown size={16} /> : <ChevronRight size={16} />}
                  </button>
                  
                  {/* SubMenus */}
                  {expandedMenu === menu.id && (
                    <ul className="mt-1 ml-4 pl-4 border-l border-slate-200 dark:border-slate-700 space-y-1">
                      {menu.subMenus.map((sub) => (
                        <li key={sub.id}>
                          <button
                            onClick={() => setActiveMenu(sub.id)}
                            className={`flex items-center gap-3 w-full p-2 rounded-lg transition-colors text-sm ${
                              activeMenu === sub.id 
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 font-semibold' 
                                : 'hover:bg-slate-100 dark:hover:bg-slate-800'
                            }`}
                          >
                            <span className={activeMenu === sub.id ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500'}>
                              {sub.icon}
                            </span>
                            <span className="whitespace-nowrap">{sub.title}</span>
                          </button>
                        </li>
                      ))}
                    </ul>
                  )}
                </>
              ) : (
                <button 
                  onClick={() => { toggleSidebar(); setExpandedMenu(menu.id); }}
                  className="flex justify-center w-full p-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors mb-2"
                  title={menu.title}
                >
                  <span className={`${expandedMenu === menu.id ? 'text-blue-600 dark:text-blue-400' : ''}`}>
                    {menu.icon}
                  </span>
                </button>
              )}
            </li>
          ))}
        </ul>
      </div>
    </aside>
  );
};

export default Sidebar;
