import React from 'react';
import styled from 'styled-components';

// TypeScript Interfaces
interface CardProps {
	title: string;
	icon?: React.ReactNode;
	headerAction?: React.ReactNode;
	children?: React.ReactNode;
	footer?: React.ReactNode;
	className?: string;
}

// Styled Components
const CardContainer = styled.div`
	background: #fff;
	border: 1px solid var(--directorist-color-light);
	border-radius: 8px;
	box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
	&:not(:last-child) {
		margin-bottom: 24px;
	}
`;

const CardHeader = styled.div`
	display: flex;
	align-items: center;
	padding: 20px 32px;
	border-bottom: 1px solid var(--color-light);
`;

const CardFooter = styled.div``;

const CardIcon = styled.div`
	width: 24px;
	height: 24px;
	margin-right: 12px;
	color: #64748b;
	display: flex;
	align-items: center;
	justify-content: center;
`;

const CardTitle = styled.h3`
	margin: 0;
	font-size: 16px;
	font-weight: 600;
	color: var(--color-gray-900);
`;

const CardContent = styled.div`
	min-height: 20px;
	padding: 24px 32px;
`;

const CardHeaderText = styled.div``;

const CardHeaderAction = styled.div``;

// Main Card Component
export default function Card({
	title,
	icon,
	headerAction,
	children,
	footer,
	className,
}: CardProps): React.ReactElement {
	return (
		<CardContainer className={className}>
			<CardHeader>
				<CardHeaderText>
					{icon && <CardIcon>{icon}</CardIcon>}
					<CardTitle>{title}</CardTitle>
				</CardHeaderText>
				{headerAction && headerAction}
			</CardHeader>
			<CardContent>{children}</CardContent>
			<CardFooter>{footer}</CardFooter>
		</CardContainer>
	);
}

// Export types for external use
export type { CardProps };
