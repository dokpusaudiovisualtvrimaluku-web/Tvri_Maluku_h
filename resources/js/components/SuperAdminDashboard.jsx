import React, { useState } from 'react';
import Sidebar from './executive/layout/Sidebar';
import Topbar from './executive/layout/Topbar';
import ExecutiveKPI from './executive/ExecutiveKPI';

import ArsipCRUD from './executive/crud/ArsipCRUD';
import DistribusiCRUD from './executive/crud/DistribusiCRUD';
import KalenderCRUD from './executive/crud/KalenderCRUD';
import MitraCRUD from './executive/crud/MitraCRUD';
import NewsroomCRUD from './executive/crud/NewsroomCRUD';
import PenugasanCRUD from './executive/crud/PenugasanCRUD';
import RapatCRUD from './executive/crud/RapatCRUD';
import SOPCRUD from './executive/crud/SOPCRUD';
import SocmedCRUD from './executive/crud/SocmedCRUD';
import IslandContentCRUD from './executive/crud/IslandContentCRUD';

const SuperAdminDashboard = () => {
  const [activeMenu, setActiveMenu] = useState('menu1-kpi');
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  const renderContent = () => {
    switch(activeMenu) {
      // Menu 1: Executive Dashboard
      case 'menu1-kpi': return <ExecutiveKPI activeSubMenu={activeMenu} />;
      case 'menu1-kalender': return <KalenderCRUD />;
      case 'menu1-penugasan': return <PenugasanCRUD />;
      case 'menu1-mitra': return <MitraCRUD />;
      case 'menu1-newsroom': return <NewsroomCRUD />;
      case 'menu1-distribusi': return <DistribusiCRUD />;
      case 'menu1-arsip': return <ArsipCRUD />;
      case 'menu1-rapat': return <RapatCRUD />;

      // Menu 2: Digital Archive
      case 'menu2-kabupaten':
      case 'menu2-program':
      case 'menu2-tahunan':
        return <ArsipCRUD />;

      // Menu 3: Hybrid Broadcasting
      case 'menu3-ig':
      case 'menu3-tiktok':
      case 'menu3-yt':
      case 'menu3-fb':
        return <SocmedCRUD />;

      // Menu 4: Digital Newsroom
      case 'menu4-cloud':
      case 'menu4-materi':
        return <NewsroomCRUD />;

      // Menu 5: Island Content Network (Updated as per request)
      case 'menu5-mitra':
        return <IslandContentCRUD submenuKategori="mitra" />;
      case 'menu5-cloud':
        return <IslandContentCRUD submenuKategori="cloud" />;
      case 'menu5-kurasi':
        return <IslandContentCRUD submenuKategori="kurasi" />;

      // Menu 6: Digital Assignment
      case 'menu6-dashboard':
        return <PenugasanCRUD />;

      // Menu 7: Digital Planning
      case 'menu7-kalender':
        return <KalenderCRUD />;
      case 'menu7-sop':
        return <SOPCRUD />;

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
      
      <div className="flex-1 flex flex-col h-screen overflow-hidden relative">
        <Topbar toggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 pb-20 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-700">
           {renderContent()}
        </main>
      </div>
    </div>
  );
};

export default SuperAdminDashboard;
