import React from 'react';
import NewsroomCRUD from './crud/NewsroomCRUD';

const DigitalNewsroom = ({ activeSubMenu }) => {
  return (
    <div className="animate-in fade-in duration-700">
      <NewsroomCRUD />
    </div>
  );
};

export default DigitalNewsroom;
