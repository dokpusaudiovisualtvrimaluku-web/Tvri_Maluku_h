import React, { useEffect, useState } from 'react';
import { Menu, Bell, Search, Sun, Moon, LogOut, User, Settings } from 'lucide-react';

const Topbar = ({ toggleSidebar }) => {
  const [theme, setTheme] = useState('dark');

  useEffect(() => {
    // Check initial theme from html class
    if (document.documentElement.classList.contains('dark')) {
      setTheme('dark');
    } else {
      setTheme('light');
    }
  }, []);

  const toggleTheme = () => {
    if (theme === 'dark') {
      document.documentElement.classList.remove('dark');
      setTheme('light');
    } else {
      document.documentElement.classList.add('dark');
      setTheme('dark');
    }
  };

  const handleLogout = (e) => {
    e.preventDefault();
    document.getElementById('logout-form').submit();
  };

  return (
    <header className="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 z-10 transition-colors duration-300">
      <div className="flex items-center gap-4">
        <button 
          onClick={toggleSidebar}
          className="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors"
        >
          <Menu size={20} />
        </button>
        
        <div className="hidden md:flex items-center relative">
          <Search size={18} className="absolute left-3 text-slate-400" />
          <input 
            type="text" 
            placeholder="Pencarian Global..." 
            className="pl-10 pr-4 py-2 w-64 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-700 dark:text-slate-200"
          />
        </div>
      </div>

      <div className="flex items-center gap-2 md:gap-4">
        <button 
          onClick={toggleTheme}
          className="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors"
        >
          {theme === 'dark' ? <Sun size={20} /> : <Moon size={20} />}
        </button>

        <div className="relative">
          <button className="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
            <div className="indicator">
              <span className="indicator-item badge badge-error badge-xs"></span> 
              <Bell size={20} />
            </div>
          </button>
        </div>

        <div className="dropdown dropdown-end">
          <div tabIndex={0} role="button" className="flex items-center gap-3 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer ml-2">
            <div className="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
              {window.authUser?.name ? window.authUser.name.substring(0, 2).toUpperCase() : 'U'}
            </div>
            <div className="hidden md:block text-left">
              <p className="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-none">{window.authUser?.name || 'Guest'}</p>
              <p className="text-[10px] text-slate-500 dark:text-slate-400 capitalize">{window.authUser?.roles?.[0]?.replace('_', ' ') || 'User'}</p>
            </div>
          </div>
          <ul tabIndex={0} className="dropdown-content z-[1] menu p-2 shadow-lg bg-white dark:bg-slate-800 rounded-box w-52 mt-4 border border-slate-200 dark:border-slate-700">
            <li><a className="text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700"><User size={16}/> Profil Saya</a></li>
            <li><a className="text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700"><Settings size={16}/> Pengaturan</a></li>
            <div className="divider my-1"></div>
            <li><a onClick={handleLogout} className="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20"><LogOut size={16}/> Logout</a></li>
          </ul>
        </div>
      </div>
      
      <form id="logout-form" action="/logout" method="POST" className="hidden">
        <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''} />
      </form>
    </header>
  );
};

export default Topbar;
