import React from 'react';

interface ElementorIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const ElementorIcon: React.FC<ElementorIconProps> = ({
	size = 28,
	color = 'black',
	className,
}) => {
	return (
		<svg
			width={size}
			height={size}
			viewBox="0 0 28 28"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			className={className}
		>
			<path
				d="M4.375 4.375V23.625H23.625V4.375H4.375ZM6.125 6.125H21.875V21.875H6.125V6.125ZM9.625 9.625V18.375H11.375V9.625H9.625ZM13.125 9.625V11.375H18.375V9.625H13.125ZM13.125 13.125V14.875H18.375V13.125H13.125ZM13.125 16.625V18.375H18.375V16.625H13.125Z"
				fill={color}
			/>
		</svg>
	);
};

export default ElementorIcon;
