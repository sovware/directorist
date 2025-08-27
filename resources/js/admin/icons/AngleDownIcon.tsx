import React from "react";

interface AngleDownIconProps {
  size?: number | string;
  color?: string;
  className?: string;
}

const AngleDownIcon: React.FC<AngleDownIconProps> = ({
  size = 12,
  color = "#3E62F5",
  className,
}) => {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" width={size} height={size} viewBox="0 0 12 12" fill="none">
<path d="M1.5 0.75L6 5.25L10.5 0.75" stroke={color} stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
  );
};

export default AngleDownIcon;