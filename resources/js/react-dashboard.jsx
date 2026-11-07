import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/ExecutiveDashboard';
import AdminDashboard from './components/AdminDashboard';
import SuperAdminDashboard from './components/SuperAdminDashboard';

const executiveRoot = document.getElementById('react-dashboard-root');
if (executiveRoot) {
    const root = createRoot(executiveRoot);
    root.render(<App />);
}

const adminRoot = document.getElementById('admin-dashboard-root');
if (adminRoot) {
    const root = createRoot(adminRoot);
    root.render(<AdminDashboard />);
}

const superAdminRoot = document.getElementById('super-admin-root');
if (superAdminRoot) {
    const root = createRoot(superAdminRoot);
    root.render(<SuperAdminDashboard />);
}
