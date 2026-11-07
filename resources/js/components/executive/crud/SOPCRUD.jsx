import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Settings, X, FileText } from 'lucide-react';

const SOPCRUD = () => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        nomor_sop: '', judul: '', kategori: 'Produksi', dokumen_pdf: '', versi: '1.0', tanggal_berlaku: '', riwayat_revisi: ''
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/sop-perencanaan');
            setData(res.data);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => { fetchData(); }, []);
    const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            if (isEdit) {
                await axios.put(`/api/sop-perencanaan/${editId}`, formData);
                Swal.fire('Berhasil', 'Data SOP diperbarui', 'success');
            } else {
                await axios.post('/api/sop-perencanaan', formData);
                Swal.fire('Berhasil', 'Data SOP ditambahkan', 'success');
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
            title: 'Hapus SOP?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/sop-perencanaan/${id}`);
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.judul.toLowerCase().includes(search.toLowerCase()) || item.nomor_sop.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Settings size={24} className="text-orange-500" /> SOP Perencanaan Digital
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari SOP..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-orange-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ nomor_sop: '', judul: '', kategori: 'Produksi', dokumen_pdf: '', versi: '1.0', tanggal_berlaku: '', riwayat_revisi: '' }); setIsEdit(false); setShowModal(true); }} className="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah SOP
                    </button>
)}
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {loading ? <div className="col-span-3 text-center py-8">Memuat data SOP...</div> : filteredData.length === 0 ? <div className="col-span-3 text-center py-8 text-slate-500">Belum ada dokumen SOP yang diunggah.</div> :
                filteredData.map((item) => (
                    <div key={item.id} className="border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex flex-col hover:border-orange-500/50 transition-colors bg-slate-50 dark:bg-slate-800/30">
                        <div className="flex justify-between items-start mb-2">
                            <span className="text-xs font-bold text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-2 py-1 rounded">{item.nomor_sop}</span>
                            <div className="flex gap-1">
                                <button onClick={() => handleEdit(item)} className="p-1 text-blue-500 hover:bg-blue-100 rounded"><Edit size={14}/></button>
                                <button onClick={() => handleDelete(item.id)} className="p-1 text-red-500 hover:bg-red-100 rounded"><Trash2 size={14}/></button>
                            </div>
                        </div>
                        <h3 className="font-bold text-slate-800 dark:text-white mb-1">{item.judul}</h3>
                        <p className="text-xs text-slate-500 mb-4 flex-1">Kategori: {item.kategori}</p>
                        
                        <div className="flex justify-between items-center text-xs border-t dark:border-slate-700 pt-3">
                            <span className="text-slate-500">Versi: {item.versi}</span>
                            <span className="text-slate-500">Berlaku: {item.tanggal_berlaku}</span>
                        </div>
                    </div>
                ))}
            </div>

            {showModal && (
                <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-xl w-full max-w-2xl overflow-hidden shadow-xl">
                        <div className="flex justify-between items-center p-4 border-b dark:border-slate-700">
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit SOP' : 'Tambah SOP Baru'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Nomor SOP</label>
                                    <input type="text" name="nomor_sop" value={formData.nomor_sop} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Kategori</label>
                                    <select name="kategori" value={formData.kategori} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        <option>Produksi</option><option>Editing</option><option>Distribusi</option><option>Live Streaming</option><option>Arsip</option><option>Media Sosial</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Judul SOP</label>
                                <input type="text" name="judul" value={formData.judul} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Versi</label>
                                    <input type="text" name="versi" value={formData.versi} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Tanggal Berlaku</label>
                                    <input type="date" name="tanggal_berlaku" value={formData.tanggal_berlaku} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Riwayat Revisi</label>
                                <textarea name="riwayat_revisi" value={formData.riwayat_revisi} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" rows="2"></textarea>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Simpan SOP</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default SOPCRUD;
