import styled from 'styled-components';

export const ClipboardLabel = styled.div`
	font-size: 14px;
	font-weight: 600;
	margin-bottom: 4px;
	color: var(--wpmvc-gray-900);
`;
export const ClipboardDescription = styled.div`
	font-size: 12px;
	margin-bottom: 16px;
	color: var(--wpmvc-gray-600);
`;

export const ClipboardCopyWrapper = styled.div`
	position: relative;
	min-width: 120px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0 10px;
	border-radius: 2px;
	background-color: var(--wpmvc-gray-50);
`;

export const ActionIcon = styled.span`
	line-height: 0.8;
	min-height: 30px;
	display: flex;
	align-items: center;
	border-left: 1px solid var(--wpmvc-gray-300);
	padding-left: 10px;
	margin-left: 10px;
	cursor: copy;
	svg {
		width: 16px;
		height: 16px;
	}
`;
