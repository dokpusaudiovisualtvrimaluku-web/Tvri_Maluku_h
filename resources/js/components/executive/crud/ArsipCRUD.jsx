import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Archive, X, FileText, Image, Video, Music, Folder, ArrowLeft } from 'lucide-react';

const FOLDERS = {
    'menu2-kabupaten': ['Ambon', 'Maluku Tengah', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Tanimbar', 'Maluku Barat Daya'],
    'menu2-program': ['Program', 'Berita', 'KMB', 'Promo', 'Dokumenter', 'Talkshow'],
    'menu2-tahunan': ['2024', '2025', '2026', '2027']
};

const ArsipCRUD = ({ activeSubMenu }) => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [activeFolder, setActiveFolder] = useState(null);
    const [formData, setFormData] = useState({
        judul: '', kategori_lokasi: 'Ambon', kategori_program: 'Berita', tahun: new Date().getFullYear(), jenis_file: 'Video', tags: ''
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const isFolderView = activeSubMenu && activeSubMenu.startsWith('menu2-');
    const folderType = activeSubMenu;

    useEffect(() => {
        setActiveFolder(null); // Reset folder when menu changes
    }, [activeSubMenu]);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/arsip-digital');
            setData(res.data);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => { fetchData(); }, []);
    
    const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

    const handleAddClick = () => {
        let defaultData = { judul: '', kategori_lokasi: 'Ambon', kategori_program: 'Berita', tahun: new Date().getFullYear(), jenis_file: 'Video', tags: '' };
        
        // Auto-fill based on active folder
        if (activeFolder) {
            if (folderType === 'menu2-kabupaten') defaultData.kategori_lokasi = activeFolder;
            if (folderType === 'menu2-program') defaultData.kategori_program = activeFolder;
            if (folderType === 'menu2-tahunan') defaultData.tahun = activeFolder;
        }

        setFormData(defaultData);
        setIsEdit(false);
        setShowModal(true);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            if (isEdit) {
                await axios.put(`/api/arsip-digital/${editId}`, formData);
                Swal.fire('Berhasil', 'Arsip diperbarui', 'success');
            } else {
                await axios.post('/api/arsip-digital', formData);
                Swal.fire('Berhasil', 'Arsip ditambahkan', 'success');
            }
            setShowModal(false);
            fetchData();
        } catch (error) {
            Swal.fire('Error', 'Gagal menyimpan data', 'error');
        }
    };

    const handleEdit = (item) => {
        setFormData(item);
        setEditId(item.id);
        setIsEdit(true);
        setShowModal(true);
    };

    const handleDelete = async (id) => {
        const result = await Swal.fire({
            title: 'Hapus Arsip?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/arsip-digital/${id}`);
                Swal.fire('Terhapus!', 'Arsip berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const getFileIcon = (jenis) => {
        switch(jenis) {
            case 'Video': return <Video size={16} className="text-red-500" />;
            case 'Audio': return <Music size={16} className="text-purple-500" />;
            case 'Foto': return <Image size={16} className="text-green-500" />;
            default: return <FileText size={16} className="text-blue-500" />;
        }
    };

    // Filter logic
    let filteredData = data;
    if (activeFolder) {
        if (folderType === 'menu2-kabupaten') {
            filteredData = filteredData.filter(item => item.kategori_lokasi === activeFolder);
        } else if (folderType === 'menu2-program') {
            filteredData = filteredData.filter(item => item.kategori_program === activeFolder);
        } else if (folderType === 'menu2-tahunan') {
            filteredData = filteredData.filter(item => item.tahun.toString() === activeFolder.toString());
        }
    }
    filteredData = filteredData.filter(item => item.judul.toLowerCase().includes(search.toLowerCase()) || (item.tags && item.tags.toLowerCase().includes(search.toLowerCase())));

    const renderFolderGrid = () => {
        const folders = FOLDERS[folderType] || [];
        return (
            <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-6">
                {folders.map(folder => {
                    // Count items in this folder
                    let count = 0;
                    if (folderType === 'menu2-kabupaten') count = data.filter(d => d.kategori_lokasi === folder).length;
                    if (folderType === 'menu2-program') count = data.filter(d => d.kategori_program === folder).length;
                    if (folderType === 'menu2-tahunan') count = data.filter(d => d.tahun.toString() === folder.toString()).length;

                    return (
                        <button 
                            key={folder} 
                            onClick={() => setActiveFolder(folder)}
                            className="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 hover:border-emerald-500 transition-all cursor-pointer group"
                        >
                            <Folder size={48} className="text-emerald-500 mb-3 group-hover:scale-110 transition-transform" />
                            <span className="font-semibold text-slate-800 dark:text-white text-center text-sm">{folder}</span>
                            <span className="text-xs text-slate-500 mt-1">{count} File</span>
                        </button>
                    )
                })}
            </div>
        );
    };

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Archive size={24} className="text-emerald-500" /> 
                        {isFolderView && !activeFolder ? (
                            <span>{folderType === 'menu2-kabupaten' ? 'Arsip Berdasarkan Kabupaten' : folderType === 'menu2-program' ? 'Arsip Berdasarkan Program' : 'Arsip Berdasarkan Tahun'}</span>
                        ) : activeFolder ? (
                            <span>Folder: {activeFolder}</span>
                        ) : (
                            <span>Semua Arsip Digital</span>
                        )}
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    {(!isFolderView || activeFolder) && (
                        <div className="relative flex-1 md:w-64">
                            <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                            <input type="text" placeholder="Cari arsip..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-emerald-500" />
                        </div>
                    )}
                    {activeFolder && (
                        <button onClick={() => setActiveFolder(null)} className="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <ArrowLeft size={18} /> Kembali
                        </button>
                    )}
                    {isSuperAdmin && (
<button onClick={handleAddClick} className="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Upload Arsip
                    </button>
)}
                </div>
            </div>

            {/* Content Area */}
            {isFolderView && !activeFolder ? (
                renderFolderGrid()
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    {loading ? <div className="col-span-3 text-center py-8">Memuat arsip...</div> : filteredData.length === 0 ? <div className="col-span-3 text-center py-8 text-slate-500">Belum ada arsip digital di kategori ini.</div> :
                    filteredData.map((item) => (
                        <div key={item.id} className="border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex flex-col hover:border-emerald-500/50 transition-colors bg-slate-50 dark:bg-slate-800/30">
                            <div className="flex justify-between items-start mb-2">
                                <div className="flex items-center gap-2">
                                    {getFileIcon(item.jenis_file)}
                                    <span className="text-xs font-bold text-slate-600 dark:text-slate-400">{item.jenis_file}</span>
                                </div>
                                <div className="flex gap-1">
                                    <button onClick={() => handleEdit(item)} className="p-1 text-blue-500 hover:bg-blue-100 rounded"><Edit size={14}/></button>
                                    <button onClick={() => handleDelete(item.id)} className="p-1 text-red-500 hover:bg-red-100 rounded"><Trash2 size={14}/></button>
                                </div>
                            </div>
                            <h3 className="font-bold text-slate-800 dark:text-white mb-2">{item.judul}</h3>
                            <div className="flex flex-wrap gap-1 mb-4 flex-1">
                                <span className="text-[10px] bg-slate-200 dark:bg-slate-700 px-2 py-0.5 rounded-full">{item.kategori_lokasi}</span>
                                <span className="text-[10px] bg-slate-200 dark:bg-slate-700 px-2 py-0.5 rounded-full">{item.kategori_program}</span>
                                <span className="text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 px-2 py-0.5 rounded-full">{item.tahun}</span>
                            </div>
                            {item.tags && (
                                <div className="text-xs text-slate-500 pt-2 border-t dark:border-slate-700 truncate">
                                    Tags: {item.tags}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-xl w-full max-w-2xl overflow-hidden shadow-xl">
                        <div className="flex justify-between items-center p-4 border-b dark:border-slate-700">
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Arsip' : 'Upload Arsip Digital'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-1">Judul Arsip</label>
                                <input type="text" name="judul" value={formData.judul} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Kabupaten/Kota</label>
                                    <select name="kategori_lokasi" value={formData.kategori_lokasi} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        {FOLDERS['menu2-kabupaten'].map(kab => <option key={kab} value={kab}>{kab}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Kategori Program</label>
                                    <select name="kategori_program" value={formData.kategori_program} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        {FOLDERS['menu2-program'].map(prog => <option key={prog} value={prog}>{prog}</option>)}
                                    </select>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Jenis File</label>
                                    <select name="jenis_file" value={formData.jenis_file} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        <option>Video</option><option>Audio</option><option>Foto</option><option>Dokumen</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Tahun Produksi</label>
                                    <input type="number" name="tahun" value={formData.tahun} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Tags / Keyword (Pisahkan dengan koma)</label>
                                <input type="text" name="tags" value={formData.tags} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" placeholder="contoh: liputan, gubernur, ambon" />
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Simpan Arsip</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default ArsipCRUD;
