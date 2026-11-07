import React from 'react';
import SocmedCRUD from './crud/SocmedCRUD';

const HybridBroadcasting = ({ activeSubMenu }) => {
  // Map menu to platform string
  const getPlatform = () => {
    switch (activeSubMenu) {
      case 'menu3-ig': return 'IG';
      case 'menu3-tiktok': return 'TikTok';
      case 'menu3-yt': return 'YouTube';
      case 'menu3-fb': return 'Facebook';
      default: return 'IG';
    }
  };

  return (
    <div className="animate-in fade-in duration-700">
      <SocmedCRUD platform={getPlatform()} />
    </div>
  );
};

export default HybridBroadcasting;
