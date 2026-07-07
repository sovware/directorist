import React from 'react';

interface AngleRightIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const AngleRightIcon: React.FC<AngleRightIconProps> = ({
	size = 20,
	color = '#4D5761',
	className,
}) => {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={size}
			height={size}
			viewBox="0 0 20 20"
			fill="none"
		>
			<path
				d="M6.125 11.75L9.875 8L6.125 4.25"
				stroke="#4D5761"
				stroke-width="1.5"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>
	);
};

export default AngleRightIcon;
