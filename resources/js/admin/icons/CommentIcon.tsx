import React from 'react';

interface CommentIconProps {
	size?: number | string;
	color?: string;
	className?: string;
}

const CommentIcon: React.FC<CommentIconProps> = ({
	size = 14,
	color = '#4D5761',
	className,
}) => {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width={size}
			height={size}
			viewBox="0 0 14 14"
			fill="none"
		>
			<path
				fill-rule="evenodd"
				clip-rule="evenodd"
				d="M2.57612 10.8568L1.58594 11.848V1.58594H12.4193V10.8568H2.57612ZM3.09427 12.1068H12.8359C13.2962 12.1068 13.6693 11.7337 13.6693 11.2734V1.16927C13.6693 0.709037 13.2962 0.335938 12.8359 0.335938H1.16927C0.709037 0.335938 0.335938 0.709037 0.335938 1.16927V13.2732C0.335938 13.517 0.470296 13.7409 0.685396 13.8557C0.941863 13.9924 1.25761 13.9454 1.46304 13.7397L3.09427 12.1068ZM10.3359 5.33591H3.66927V4.08591H10.3359V5.33591ZM3.66927 8.66927H7.83594V7.41927H3.66927V8.66927Z"
				fill={color}
			/>
		</svg>
	);
};

export default CommentIcon;
