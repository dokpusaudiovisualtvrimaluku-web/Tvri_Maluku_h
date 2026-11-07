import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Calendar, X } from 'lucide-react';

const KalenderCRUD = () => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        judul_produksi: '', kategori: 'Program', tanggal_mulai: '', tanggal_selesai: '', lokasi: '', pic: '', status: 'Direncanakan', catatan: ''
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/kalender-produksi');
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
                await axios.put(`/api/kalender-produksi/${editId}`, formData);
                Swal.fire('Berhasil', 'Jadwal diperbarui', 'success');
            } else {
                await axios.post('/api/kalender-produksi', formData);
                Swal.fire('Berhasil', 'Jadwal ditambahkan', 'success');
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
            title: 'Hapus Jadwal?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/kalender-produksi/${id}`);
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.judul_produksi.toLowerCase().includes(search.toLowerCase()) || item.pic?.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Calendar size={24} className="text-blue-500" /> Kalender Produksi
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari produksi..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-blue-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ judul_produksi: '', kategori: 'Program', tanggal_mulai: '', tanggal_selesai: '', lokasi: '', pic: '', status: 'Direncanakan', catatan: '' }); setIsEdit(false); setShowModal(true); }} className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah Jadwal
                    </button>
)}
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table className="w-full text-left text-sm whitespace-nowrap">
                    <thead className="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th className="p-3">Judul Produksi</th>
                            <th className="p-3">Kategori</th>
                            <th className="p-3">Waktu Pelaksanaan</th>
                            <th className="p-3">PIC / Lokasi</th>
                            <th className="p-3">Status</th>
                            {isSuperAdmin && <th className="p-3 text-right">Aksi</th>}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                        {loading ? <tr><td colSpan={isSuperAdmin ? '6' : '5'} className="text-center p-4">Memuat data...</td></tr> : filteredData.length === 0 ? <tr><td colSpan={isSuperAdmin ? '6' : '5'} className="text-center p-4">Belum ada jadwal produksi.</td></tr> :
                        filteredData.map((item) => (
                            <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td className="p-3 font-semibold">{item.judul_produksi}</td>
                                <td className="p-3"><span className="px-2 py-1 text-xs rounded-full bg-slate-200 dark:bg-slate-700">{item.kategori}</span></td>
                                <td className="p-3 text-xs">
                                    {item.tanggal_mulai ? new Date(item.tanggal_mulai).toLocaleDateString() : '-'} s/d <br/>
                                    {item.tanggal_selesai ? new Date(item.tanggal_selesai).toLocaleDateString() : '-'}
                                </td>
                                <td className="p-3">
                                    <div className="text-xs font-semibold">{item.pic || '-'}</div>
                                    <div className="text-xs text-slate-500">{item.lokasi || '-'}</div>
                                </td>
                                <td className="p-3">
                                    <span className={`px-2 py-1 text-xs rounded-full ${item.status === 'Selesai' ? 'bg-green-100 text-green-700' : item.status === 'Berjalan' ? 'bg-blue-100 text-blue-700' : item.status === 'Dibatalkan' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700'}`}>
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
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Jadwal Produksi' : 'Tambah Jadwal Produksi'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Judul Produksi</label>
                                    <input type="text" name="judul_produksi" value={formData.judul_produksi} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Kategori</label>
                                    <select name="kategori" value={formData.kategori} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                        <option>Program</option><option>Berita</option><option>Event</option><option>Talkshow</option>
                                    </select>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" value={formData.tanggal_mulai} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" value={formData.tanggal_selesai} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Lokasi</label>
                                    <input type="text" name="lokasi" value={formData.lokasi} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Penanggung Jawab (PIC)</label>
                                    <input type="text" name="pic" value={formData.pic} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Status</label>
                                <select name="status" value={formData.status} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                    <option>Direncanakan</option><option>Berjalan</option><option>Selesai</option><option>Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Catatan Tambahan</label>
                                <textarea name="catatan" value={formData.catatan} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" rows="2"></textarea>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default KalenderCRUD;
