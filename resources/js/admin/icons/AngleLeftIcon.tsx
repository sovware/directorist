import React from 'react';

interface AngleLeftIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const AngleLeftIcon: React.FC<AngleLeftIconProps> = ({
	size = 20,
	color = '#4D5761',
	className,
}) => {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" width={size} height={size} viewBox="0 0 20 20" fill="none">
<path d="M12.5 5L7.5 10L12.5 15" stroke={color} stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
	);
};

export default AngleLeftIcon;
