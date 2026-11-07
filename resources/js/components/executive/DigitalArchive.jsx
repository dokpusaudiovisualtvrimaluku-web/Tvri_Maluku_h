import React from 'react';
import ArsipCRUD from './crud/ArsipCRUD';

const DigitalArchive = ({ activeSubMenu }) => {
  return (
    <div className="animate-in fade-in duration-700">
      <ArsipCRUD activeSubMenu={activeSubMenu} />
    </div>
  );
};

export default DigitalArchive;
