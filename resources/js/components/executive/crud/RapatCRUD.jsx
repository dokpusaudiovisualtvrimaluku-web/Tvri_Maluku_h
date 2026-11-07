import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, MessageSquare, X } from 'lucide-react';

const RapatCRUD = () => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        jadwal_rapat: '', agenda: '', notulen: '', keputusan: '', tindak_lanjut: ''
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/rapat-koordinasi');
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
                await axios.put(`/api/rapat-koordinasi/${editId}`, formData);
                Swal.fire('Berhasil', 'Data rapat diperbarui', 'success');
            } else {
                await axios.post('/api/rapat-koordinasi', formData);
                Swal.fire('Berhasil', 'Data rapat ditambahkan', 'success');
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
            title: 'Hapus Rapat?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/rapat-koordinasi/${id}`);
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.agenda.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <MessageSquare size={24} className="text-purple-500" /> Rapat Koordinasi
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari rapat..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-purple-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ jadwal_rapat: '', agenda: '', notulen: '', keputusan: '', tindak_lanjut: '' }); setIsEdit(false); setShowModal(true); }} className="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah
                    </button>
)}
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table className="w-full text-left text-sm whitespace-nowrap">
                    <thead className="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th className="p-3">Jadwal Rapat</th>
                            <th className="p-3">Agenda</th>
                            <th className="p-3">Notulen / Keputusan</th>
                            {isSuperAdmin && <th className="p-3 text-right">Aksi</th>}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                        {loading ? <tr><td colSpan="4" className="text-center p-4">Memuat data...</td></tr> : filteredData.length === 0 ? <tr><td colSpan="4" className="text-center p-4">Belum ada rapat.</td></tr> :
                        filteredData.map((item) => (
                            <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td className="p-3 font-semibold">{item.jadwal_rapat}</td>
                                <td className="p-3">{item.agenda}</td>
                                <td className="p-3">
                                    <div className="truncate w-64 text-xs">{item.keputusan || '-'}</div>
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
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Rapat' : 'Jadwalkan Rapat'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-1">Jadwal Rapat (Waktu)</label>
                                <input type="datetime-local" name="jadwal_rapat" value={formData.jadwal_rapat} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Agenda Utama</label>
                                <input type="text" name="agenda" value={formData.agenda} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Keputusan</label>
                                <textarea name="keputusan" value={formData.keputusan} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" rows="2"></textarea>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Tindak Lanjut</label>
                                <textarea name="tindak_lanjut" value={formData.tindak_lanjut} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" rows="2"></textarea>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default RapatCRUD;
