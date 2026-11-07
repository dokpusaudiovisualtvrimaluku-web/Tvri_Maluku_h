import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Share2, X } from 'lucide-react';

const DistribusiCRUD = () => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        konten: '', tujuan: '', status: 'Pending'
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/distribusi-konten');
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
                await axios.put(`/api/distribusi-konten/${editId}`, formData);
                Swal.fire('Berhasil', 'Data diperbarui', 'success');
            } else {
                await axios.post('/api/distribusi-konten', formData);
                Swal.fire('Berhasil', 'Data ditambahkan', 'success');
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
            title: 'Hapus Distribusi?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/distribusi-konten/${id}`);
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.konten.toLowerCase().includes(search.toLowerCase()) || item.tujuan.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Share2 size={24} className="text-cyan-500" /> Distribusi Konten
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari distribusi..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-cyan-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ konten: '', tujuan: '', status: 'Pending' }); setIsEdit(false); setShowModal(true); }} className="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah Distribusi
                    </button>
)}
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table className="w-full text-left text-sm whitespace-nowrap">
                    <thead className="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th className="p-3">Nama Konten</th>
                            <th className="p-3">Tujuan Distribusi</th>
                            <th className="p-3">Status</th>
                            {isSuperAdmin && <th className="p-3 text-right">Aksi</th>}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                        {loading ? <tr><td colSpan={isSuperAdmin ? '4' : '3'} className="text-center p-4">Memuat data...</td></tr> : filteredData.length === 0 ? <tr><td colSpan={isSuperAdmin ? '4' : '3'} className="text-center p-4">Belum ada distribusi.</td></tr> :
                        filteredData.map((item) => (
                            <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td className="p-3 font-semibold">{item.konten}</td>
                                <td className="p-3">{item.tujuan}</td>
                                <td className="p-3">
                                    <span className={`px-2 py-1 text-xs rounded-full ${item.status === 'Diterima' ? 'bg-green-100 text-green-700' : item.status === 'Dikirim' ? 'bg-blue-100 text-blue-700' : item.status === 'Diproses' ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-700'}`}>
                                        {item.status}
                                    </span>
                                </td>
                                {isSuperAdmin && (
<td className="p-3 text-right">
                                    <button onClick={() => handleEdit(item)} className="p-1 text-blue-500 hover:bg-blue-50 rounded"><Edit size={16}/></button>
                                    <button onClick={() => handleDelete(item.id)} className="p-1 text-red-500 hover:bg-red-50 rounded ml-1"><Trash2 size={16}/></button>
                                </td>
)}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {showModal && (
                <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-xl w-full max-w-2xl overflow-hidden shadow-xl">
                        <div className="flex justify-between items-center p-4 border-b dark:border-slate-700">
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Distribusi' : 'Tambah Distribusi'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-1">Nama Konten</label>
                                <input type="text" name="konten" value={formData.konten} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Tujuan / Unit Kerja</label>
                                <input type="text" name="tujuan" value={formData.tujuan} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Status Distribusi</label>
                                <select name="status" value={formData.status} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                    <option>Pending</option><option>Diproses</option><option>Dikirim</option><option>Diterima</option>
                                </select>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default DistribusiCRUD;
