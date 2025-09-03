import React from 'react';

interface DocIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const DocIcon: React.FC<DocIconProps> = ({
	size = 14,
	color = '#4D5761',
	className,
}) => {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={size}
			height={size}
			viewBox="0 0 14 16"
			fill="none"
		>
			<path
				fill-rule="evenodd"
				clip-rule="evenodd"
				d="M2 0H12C12.5304 0 13.0391 0.210714 13.4142 0.585786C13.7893 0.960859 14 1.46957 14 2V14C14 14.5304 13.7893 15.0391 13.4142 15.4142C13.0391 15.7893 12.5304 16 12 16H2C1.46957 16 0.960859 15.7893 0.585786 15.4142C0.210714 15.0391 0 14.5304 0 14V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0ZM12 1.5H2C1.86739 1.5 1.74021 1.55268 1.64645 1.64645C1.55268 1.74021 1.5 1.86739 1.5 2V14C1.5 14.1326 1.55268 14.2598 1.64645 14.3536C1.74021 14.4473 1.86739 14.5 2 14.5H12C12.1326 14.5 12.2598 14.4473 12.3536 14.3536C12.4473 14.2598 12.5 14.1326 12.5 14V2C12.5 1.86739 12.4473 1.74021 12.3536 1.64645C12.2598 1.55268 12.1326 1.5 12 1.5ZM3.5 3.5H10.5V5H3.5V3.5ZM10.5 7H3.5V8.5H10.5V7ZM3.5 10.5H10.5V12H3.5V10.5Z"
				fill={color}
			/>
		</svg>
	);
};

export default DocIcon;
