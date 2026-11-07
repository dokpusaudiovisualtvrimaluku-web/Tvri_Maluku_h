import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { 
  Plus, Edit, Trash2, Globe, FileText, Video, Link as LinkIcon, Download, Play, Eye
} from 'lucide-react';

const IslandContentCRUD = ({ submenuKategori }) => {
  const [data, setData] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingId, setEditingId] = useState(null);
  
  // Form State
  const [judul, setJudul] = useState('');
  const [deskripsi, setDeskripsi] = useState('');
  const [tautanLink, setTautanLink] = useState('');
  const [fileVideo, setFileVideo] = useState(null);
  const [filePdf, setFilePdf] = useState(null);

  const isSuperAdmin = window.authUser?.roles?.includes('Super Admin');

  const getSubmenuTitle = () => {
    if (submenuKategori === 'mitra') return 'Mitra Konten Publik';
    if (submenuKategori === 'cloud') return 'Cloud Materi Berita';
    if (submenuKategori === 'kurasi') return 'Kurasi dan Tayangan';
    return 'Island Content Network';
  };

  useEffect(() => {
    fetchData();
  }, [submenuKategori]);

  const fetchData = async () => {
    try {
      const response = await axios.get('/api/island-content-networks?kategori=' + submenuKategori);
      setData(response.data);
    } catch (error) {
      console.error('Error fetching data', error);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('kategori_submenu', submenuKategori);
    formData.append('judul', judul);
    formData.append('deskripsi', deskripsi);
    formData.append('tautan_link', tautanLink);
    if (fileVideo) formData.append('file_video', fileVideo);
    if (filePdf) formData.append('file_pdf', filePdf);

    try {
      if (editingId) {
        // Axios doesn't support PUT with FormData cleanly without method spoofing
        formData.append('_method', 'PUT');
        await axios.post('/api/island-content-networks/' + editingId, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        await axios.post('/api/island-content-networks', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }
      setIsModalOpen(false);
      resetForm();
      fetchData();
    } catch (error) {
      console.error('Error submitting', error);
      alert('Gagal mengunggah file. Pastikan ukuran file sesuai dan format benar.');
    }
  };

  const handleEdit = (item) => {
    setEditingId(item.id);
    setJudul(item.judul);
    setDeskripsi(item.deskripsi || '');
    setTautanLink(item.tautan_link || '');
    setFileVideo(null); // Reset file input
    setFilePdf(null);   // Reset file input
    setIsModalOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Yakin ingin menghapus data ini?')) {
      try {
        await axios.delete('/api/island-content-networks/' + id);
        fetchData();
      } catch (error) {
        console.error('Error deleting', error);
      }
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setJudul('');
    setDeskripsi('');
    setTautanLink('');
    setFileVideo(null);
    setFilePdf(null);
  };

  return (
    <div className="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 animate-in fade-in duration-500">
      <div className="flex justify-between items-center mb-6">
        <div>
          <h2 className="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <Globe className="text-blue-500" /> 
            {getSubmenuTitle()}
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">Kelola dan lihat data multimedia (Video, PDF, Link)</p>
        </div>
        {isSuperAdmin && (
          <button 
            onClick={() => { resetForm(); setIsModalOpen(true); }}
            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors text-sm font-medium"
          >
            <Plus size={16} /> Tambah Data
          </button>
        )}
      </div>

      <div className="overflow-x-auto">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
              <th className="p-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Judul</th>
              <th className="p-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Deskripsi</th>
              <th className="p-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Multimedia</th>
              {isSuperAdmin && <th className="p-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>}
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-200 dark:divide-slate-700">
            {data.length === 0 ? (
              <tr>
                <td colSpan={isSuperAdmin ? "4" : "3"} className="p-8 text-center text-slate-500 dark:text-slate-400">
                  Tidak ada data tersedia.
                </td>
              </tr>
            ) : (
              data.map((item) => (
                <tr key={item.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                  <td className="p-4 font-medium text-slate-800 dark:text-slate-200">{item.judul}</td>
                  <td className="p-4 text-slate-600 dark:text-slate-400 text-sm">{item.deskripsi}</td>
                  <td className="p-4">
                     <div className="flex justify-center items-center gap-3">
                        {item.file_video ? (
                          <a href={item.file_video} target="_blank" rel="noreferrer" title="Tonton Video" className="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                            <Video size={16} />
                          </a>
                        ) : <span className="p-2 text-slate-300 dark:text-slate-600"><Video size={16} /></span>}
                        
                        {item.file_pdf ? (
                          <a href={item.file_pdf} target="_blank" rel="noreferrer" title="Lihat PDF" className="p-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                            <FileText size={16} />
                          </a>
                        ) : <span className="p-2 text-slate-300 dark:text-slate-600"><FileText size={16} /></span>}

                        {item.tautan_link ? (
                          <a href={item.tautan_link} target="_blank" rel="noreferrer" title="Buka Tautan" className="p-2 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full hover:bg-green-200 dark:hover:bg-green-900/50 transition">
                            <LinkIcon size={16} />
                          </a>
                        ) : <span className="p-2 text-slate-300 dark:text-slate-600"><LinkIcon size={16} /></span>}
                     </div>
                  </td>
                  {isSuperAdmin && (
                    <td className="p-4 text-right">
                      <div className="flex justify-end gap-2">
                        <button onClick={() => handleEdit(item)} className="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-slate-800 rounded transition-colors">
                          <Edit size={16} />
                        </button>
                        <button onClick={() => handleDelete(item.id)} className="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-slate-800 rounded transition-colors">
                          <Trash2 size={16} />
                        </button>
                      </div>
                    </td>
                  )}
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Modal Form */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
          <div className="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-700">
            <div className="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
              <h3 className="text-lg font-bold text-slate-800 dark:text-white">
                {editingId ? 'Edit Data' : 'Tambah Data'}
              </h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                ?
              </button>
            </div>
            
            <div className="p-6 overflow-y-auto">
              <form id="islandForm" onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul *</label>
                  <input type="text" required value={judul} onChange={e => setJudul(e.target.value)}
                    className="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-white p-2.5 border outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                    placeholder="Masukkan judul materi"
                  />
                </div>
                
                <div>
                  <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                  <textarea value={deskripsi} onChange={e => setDeskripsi(e.target.value)} rows="3"
                    className="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-white p-2.5 border outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Tambahkan deskripsi singkat..."
                  ></textarea>
                </div>

                <div className="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700 space-y-4">
                   <h4 className="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 mb-2">Multimedia Pendukung</h4>
                   
                   <div>
                     <label className="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1 flex items-center gap-1"><Video size={12}/> File Video (MP4)</label>
                     <input type="file" accept="video/mp4,video/mkv,video/avi" onChange={e => setFileVideo(e.target.files[0])}
                       className="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400"
                     />
                     {editingId && <span className="text-[10px] text-slate-400 mt-1 block">* Kosongkan jika tidak ingin mengubah video sebelumnya</span>}
                   </div>

                   <div>
                     <label className="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1 flex items-center gap-1"><FileText size={12}/> File PDF Dokumen</label>
                     <input type="file" accept="application/pdf" onChange={e => setFilePdf(e.target.files[0])}
                       className="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/30 dark:file:text-red-400"
                     />
                     {editingId && <span className="text-[10px] text-slate-400 mt-1 block">* Kosongkan jika tidak ingin mengubah PDF sebelumnya</span>}
                   </div>

                   <div>
                     <label className="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1 flex items-center gap-1"><LinkIcon size={12}/> Tautan Eksternal (Opsional)</label>
                     <input type="url" value={tautanLink} onChange={e => setTautanLink(e.target.value)}
                       className="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white p-2 text-sm border outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                       placeholder="https://youtube.com/..."
                     />
                   </div>
                </div>
              </form>
            </div>
            
            <div className="p-6 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-900/20">
              <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                Batal
              </button>
              <button type="submit" form="islandForm" className="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                Simpan Data
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default IslandContentCRUD;
