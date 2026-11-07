import React, { useState } from 'react';
import ExecutiveKPI from './executive/ExecutiveKPI';
import DigitalArchive from './executive/DigitalArchive';
import HybridBroadcasting from './executive/HybridBroadcasting';
import DigitalNewsroom from './executive/DigitalNewsroom';
import IslandContent from './executive/IslandContent';
import DigitalAssignment from './executive/DigitalAssignment';
import DigitalPlanning from './executive/DigitalPlanning';
import Sidebar from './executive/layout/Sidebar';
import Topbar from './executive/layout/Topbar';

const ExecutiveDashboard = () => {
  const [activeMenu, setActiveMenu] = useState('menu1-kpi');
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  const renderContent = () => {
    switch(activeMenu) {
      case 'menu1-kpi':
      case 'menu1-kalender':
      case 'menu1-penugasan':
      case 'menu1-mitra':
      case 'menu1-newsroom':
      case 'menu1-distribusi':
      case 'menu1-arsip':
      case 'menu1-rapat':
        return <ExecutiveKPI activeSubMenu={activeMenu} />;
      case 'menu2-kabupaten':
      case 'menu2-program':
      case 'menu2-tahunan':
        return <DigitalArchive activeSubMenu={activeMenu} />;
      case 'menu3-ig':
      case 'menu3-tiktok':
      case 'menu3-yt':
      case 'menu3-fb':
        return <HybridBroadcasting activeSubMenu={activeMenu} />;
      case 'menu4-cloud':
      case 'menu4-materi':
        return <DigitalNewsroom activeSubMenu={activeMenu} />;
      case 'menu5-mitra':
      case 'menu5-cloud':
      case 'menu5-kurasi':
        return <IslandContent activeSubMenu={activeMenu} />;
      case 'menu6-dashboard':
        return <DigitalAssignment activeSubMenu={activeMenu} />;
      case 'menu7-kalender':
      case 'menu7-sop':
        return <DigitalPlanning activeSubMenu={activeMenu} />;
      default:
        return <ExecutiveKPI activeSubMenu="menu1-kpi" />;
    }
  };

  return (
    <div className="flex h-screen bg-slate-100 dark:bg-slate-900 overflow-hidden font-sans text-slate-800 dark:text-slate-200 transition-colors duration-300">
      <Sidebar 
        isOpen={isSidebarOpen} 
        activeMenu={activeMenu} 
        setActiveMenu={setActiveMenu} 
        toggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)} 
      />
      
      <div className={`flex-1 flex flex-col transition-all duration-300 overflow-hidden`}>
        <Topbar toggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)} />
        
        <main className="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-[#020617]">
          <div className="p-4 md:p-6 mx-auto w-full max-w-screen-2xl">
            {renderContent()}
          </div>
        </main>
      </div>
    </div>
  );
};

export default ExecutiveDashboard;
