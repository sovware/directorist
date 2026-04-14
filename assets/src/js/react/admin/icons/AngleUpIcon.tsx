import React from 'react';

interface AngleUpIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const AngleUpIcon: React.FC<AngleUpIconProps> = ({
	size = 18,
	color = '#3E62F5',
	className,
}) => {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={size}
			height={size}
			viewBox="0 0 18 18"
			fill="none"
		>
			<path
				d="M13.5 11.25L9 6.75L4.5 11.25"
				stroke={color}
				stroke-width="1.5"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>
	);
};

export default AngleUpIcon;
