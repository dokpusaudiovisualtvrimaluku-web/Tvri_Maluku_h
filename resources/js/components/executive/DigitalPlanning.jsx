import React from 'react';
import SOPCRUD from './crud/SOPCRUD';

const DigitalPlanning = ({ activeSubMenu }) => {
  return (
    <div className="animate-in fade-in duration-700">
      {activeSubMenu === 'menu7-sop' ? (
          <SOPCRUD />
      ) : (
          <div className="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
            <h1 className="text-2xl font-bold text-slate-800 dark:text-white mb-4">Digital Planning</h1>
            <p className="text-slate-600 dark:text-slate-400">
              Saat ini menampilkan: <span className="font-semibold text-blue-600 dark:text-blue-400">{activeSubMenu}</span>
            </p>
            <div className="mt-8 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl h-64 flex items-center justify-center">
              <p className="text-slate-500">Konten {activeSubMenu} (Kalender) akan ditampilkan di sini.</p>
            </div>
          </div>
      )}
    </div>
  );
};

export default DigitalPlanning;
