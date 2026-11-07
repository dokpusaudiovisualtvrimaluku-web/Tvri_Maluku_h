import React from 'react';
import IslandContentCRUD from './crud/IslandContentCRUD';

const IslandContent = ({ activeSubMenu }) => {
  let kategori = 'mitra';
  if (activeSubMenu === 'menu5-cloud') kategori = 'cloud';
  if (activeSubMenu === 'menu5-kurasi') kategori = 'kurasi';

  return (
    <div className="animate-in fade-in duration-700">
      <IslandContentCRUD submenuKategori={kategori} />
    </div>
  );
};

export default IslandContent;
