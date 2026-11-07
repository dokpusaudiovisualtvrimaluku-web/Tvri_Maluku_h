import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Plus, Edit, Trash2, Search, Video, X } from 'lucide-react';

const NewsroomCRUD = () => {
    const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [formData, setFormData] = useState({
        judul: '', reporter: '', cameraman: '', editor: '', lokasi: '', durasi: '', status: 'Draft'
    });
    const [isEdit, setIsEdit] = useState(false);
    const [editId, setEditId] = useState(null);

    const fetchData = async () => {
        try {
            const res = await axios.get('/api/berita-newsroom');
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
                await axios.put(`/api/berita-newsroom/${editId}`, formData);
                Swal.fire('Berhasil', 'Berita diperbarui', 'success');
            } else {
                await axios.post('/api/berita-newsroom', formData);
                Swal.fire('Berhasil', 'Berita ditambahkan', 'success');
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
            title: 'Hapus Berita?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#3b82f6', confirmButtonText: 'Ya, Hapus!'
        });
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/berita-newsroom/${id}`);
                Swal.fire('Terhapus!', 'Berita berhasil dihapus.', 'success');
                fetchData();
            } catch (error) { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
        }
    };

    const filteredData = data.filter(item => item.judul.toLowerCase().includes(search.toLowerCase()) || item.reporter?.toLowerCase().includes(search.toLowerCase()));

    return (
        <div className="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 animate-in fade-in">
            <div className="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <Video size={24} className="text-red-500" /> Digital Newsroom
                    </h2>
                </div>
                <div className="flex gap-2 w-full md:w-auto">
                    <div className="relative flex-1 md:w-64">
                        <Search size={18} className="absolute left-3 top-2.5 text-slate-400" />
                        <input type="text" placeholder="Cari berita..." value={search} onChange={(e) => setSearch(e.target.value)} className="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-800 dark:border-slate-700 focus:outline-none focus:border-red-500" />
                    </div>
                    {isSuperAdmin && (
<button onClick={() => { setFormData({ judul: '', reporter: '', cameraman: '', editor: '', lokasi: '', durasi: '', status: 'Draft' }); setIsEdit(false); setShowModal(true); }} className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <Plus size={18} /> Tambah Berita
                    </button>
)}
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table className="w-full text-left text-sm whitespace-nowrap">
                    <thead className="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th className="p-3">Judul Berita</th>
                            <th className="p-3">Tim Liputan</th>
                            <th className="p-3">Lokasi / Durasi</th>
                            <th className="p-3">Status</th>
                            {isSuperAdmin && <th className="p-3 text-right">Aksi</th>}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                        {loading ? <tr><td colSpan={isSuperAdmin ? '5' : '4'} className="text-center p-4">Memuat data...</td></tr> : filteredData.length === 0 ? <tr><td colSpan={isSuperAdmin ? '5' : '4'} className="text-center p-4">Belum ada berita.</td></tr> :
                        filteredData.map((item) => (
                            <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td className="p-3 font-semibold">{item.judul}</td>
                                <td className="p-3">
                                    <div className="text-xs">Rep: {item.reporter || '-'}</div>
                                    <div className="text-xs">Cam: {item.cameraman || '-'}</div>
                                    <div className="text-xs">Ed: {item.editor || '-'}</div>
                                </td>
                                <td className="p-3 text-xs">
                                    <div>{item.lokasi || '-'}</div>
                                    <div className="text-slate-500">{item.durasi || '-'}</div>
                                </td>
                                <td className="p-3">
                                    <span className={`px-2 py-1 text-xs rounded-full ${item.status === 'Sudah Tayang' ? 'bg-green-100 text-green-700' : item.status === 'Siap Tayang' ? 'bg-blue-100 text-blue-700' : item.status === 'Editing' ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-700'}`}>
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
                            <h3 className="text-lg font-bold">{isEdit ? 'Edit Berita' : 'Tambah Berita Baru'}</h3>
                            <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-slate-600"><X size={20}/></button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-4 space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-1">Judul Berita</label>
                                <input type="text" name="judul" value={formData.judul} onChange={handleChange} required className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                            </div>
                            <div className="grid grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Reporter</label>
                                    <input type="text" name="reporter" value={formData.reporter} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Cameraman</label>
                                    <input type="text" name="cameraman" value={formData.cameraman} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Editor</label>
                                    <input type="text" name="editor" value={formData.editor} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-1">Lokasi Liputan</label>
                                    <input type="text" name="lokasi" value={formData.lokasi} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-1">Durasi</label>
                                    <input type="text" name="durasi" value={formData.durasi} onChange={handleChange} placeholder="00:00" className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700" />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-1">Status Berita</label>
                                <select name="status" value={formData.status} onChange={handleChange} className="w-full p-2 border rounded dark:bg-slate-800 dark:border-slate-700">
                                    <option>Draft</option><option>Editing</option><option>Siap Tayang</option><option>Sudah Tayang</option>
                                </select>
                            </div>
                            <div className="flex justify-end gap-2 pt-4 border-t dark:border-slate-700">
                                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 border rounded text-slate-600 hover:bg-slate-50">Batal</button>
                                <button type="submit" className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Simpan Berita</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};
export default NewsroomCRUD;
