import React from 'react';
import styled from 'styled-components';

interface LayoutProps {
	views: {
		leftContent?: React.ReactNode;
		rightContent?: React.ReactNode;
	};
}

const Container = styled.div`
	display: flex;
	flex-direction: row;
	gap: 20px;
	padding: 24px;
`;

const LeftContent = styled.div`
	width: 80%;
`;

const RightContent = styled.div`
	width: 20%;
`;

export default function Layout({ views }: LayoutProps) {
	const { leftContent, rightContent } = views;
	return (
		<Container>
			<LeftContent>{leftContent}</LeftContent>
			<RightContent>{rightContent}</RightContent>
		</Container>
	);
}
