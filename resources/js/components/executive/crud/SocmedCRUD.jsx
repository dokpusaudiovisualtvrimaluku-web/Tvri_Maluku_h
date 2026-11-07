import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Radio, X } from 'lucide-react';

const SocmedCRUD = ({ platform }) => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        platform: platform || 'IG', caption: '', hashtag: '', jadwal_publish: '', status: 'Scheduled', viewer: 0, likes: 0, share: 0, comment: 0
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/socmed-publish');
            // Filter by platform if prop is passed
            const platformData = platform ? res.data.filter(item => item.platform === platform) : res.data;
            setData(platformData);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => { 
        fetchData(); 
        setFormData(prev => ({ ...prev, platform: platform || 'IG' }));
    }, [platform]);

    const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            if (isEdit) {
                await axios.put(`/api/socmed-publish/${editId}`, formData);
                Swal.fire('Berhasil', 'Konten diperbarui', 'success');
            } else {
                await axios.post('/api/socmed-publish', formData);
                Swal.fire('Berhasil', 'Konten dijadwalkan', 'success');
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
            title: 'Hapus Konten?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/socmed-publish/${id}`);
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.caption?.toLowerCase().includes(search.toLowerCase()) || item.hashtag?.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Radio size={24} className="text-pink-500" /> Konten {platform || 'Media Sosial'}
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari konten..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-pink-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ platform: platform || 'IG', caption: '', hashtag: '', jadwal_publish: '', status: 'Scheduled', viewer: 0, likes: 0, share: 0, comment: 0 }); setIsEdit(false); setShowModal(true); }} className="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah Konten
                    </button>
)}
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table className="w-full text-left text-sm whitespace-nowrap">
                    <thead className="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th className="p-3">Jadwal Publish</th>
                            <th className="p-3">Caption & Hashtag</th>
                            <th className="p-3">Statistik</th>
                            <th className="p-3">Status</th>
                            {isSuperAdmin && <th className="p-3 text-right">Aksi</th>}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                        {loading ? <tr><td colSpan={isSuperAdmin ? '5' : '4'} className="text-center p-4">Memuat data...</td></tr> : filteredData.length === 0 ? <tr><td colSpan={isSuperAdmin ? '5' : '4'} className="text-center p-4">Belum ada konten dijadwalkan.</td></tr> :
                        filteredData.map((item) => (
                            <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td className="p-3 font-semibold">{item.jadwal_publish ? new Date(item.jadwal_publish).toLocaleString() : '-'}</td>
                                <td className="p-3">
                                    <div className="truncate w-48 text-xs">{item.caption || '-'}</div>
                                    <div className="text-xs text-blue-500">{item.hashtag || '-'}</div>
                                </td>
                                <td className="p-3 text-xs">
                                    👁 {item.viewer} | ❤️ {item.likes} | 💬 {item.comment} | 🔁 {item.share}
                                </td>
                                <td className="p-3">
                                    <span className={`px-2 py-1 text-xs rounded-full ${item.status === 'Published' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700'}`}>
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
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Konten' : 'Jadwalkan Konten'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            {!platform && (
                                <div>
                                    <label className="block text-sm font-medium mb-1">Platform</label>
                                    <select name="platform" value={formData.platform} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        <option>IG</option><option>TikTok</option><option>YouTube</option><option>Facebook</option>
                                    </select>
                                </div>
                            )}
                            <div>
                                <label className="block text-sm font-medium mb-1">Caption</label>
                                <textarea name="caption" value={formData.caption} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" rows="3"></textarea>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Hashtag (contoh: #news #viral)</label>
                                <input type="text" name="hashtag" value={formData.hashtag} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Jadwal Publish</label>
                                    <input type="datetime-local" name="jadwal_publish" value={formData.jadwal_publish} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Status</label>
                                    <select name="status" value={formData.status} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        <option>Scheduled</option><option>Published</option><option>Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default SocmedCRUD;
