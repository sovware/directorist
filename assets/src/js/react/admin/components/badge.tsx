import React from 'react';
import styled from 'styled-components';

// Define the variant types
export type BadgeVariant =
	// Semantic
	| 'default'
	| 'info'
	| 'warning'
	| 'success'
	| 'error'
	// Status-named
	| 'expired'
	| 'paid'
	| 'active'
	| 'pending'
	| 'failed'
	| 'cancelled'
	| 'refunded'
	| 'unpaid'
	| 'trialing'
	| 'paused';

// Styled component with proper TypeScript interface
const BadgeStyle = styled.div<{ $variant?: BadgeVariant }>`
	display: inline-flex;
	align-items: center;
	padding: 6px 8px;
	border-radius: 2px;
	font-size: 12px;
	font-weight: 400;
	line-height: 1;
	text-align: center;
	white-space: nowrap;
	vertical-align: baseline;

	/* Variant-specific styles */
	${({ $variant }) => {
		switch ($variant) {
			case 'info':
			case 'trialing':
				return `
					background-color: var(--wpmvc-primary-100);
					color: var(--wpmvc-primary-500);
				`;
			case 'refunded':
				return `
					background-color: #e6f4ff;
					color: #1677ff;
				`;
			case 'warning':
			case 'pending':
			case 'unpaid':
			case 'paused':
				return `
					background-color: var(--wpmvc-warning-100);
					color: var(--wpmvc-warning-500);
				`;
			case 'success':
			case 'paid':
			case 'active':
				return `
					background-color: var(--wpmvc-success-100);
					color: var(--wpmvc-success-500);
				`;
			case 'error':
			case 'failed':
			case 'cancelled':
				return `
					background-color: var(--wpmvc-error-100);
					color: var(--wpmvc-error-500);
				`;
			case 'expired':
				return `
					background-color: #F0E4D9;
					color: #AD7F58;
				`;
			default:
				return `
					background-color: #f0f0f0;
					color: #2f2f2f;
				`;
		}
	}}
`;

const BadgeContent = styled.div`
	display: flex;
	align-items: center;
	gap: 4px;
`;

// Props interface
interface BadgeProps {
	children: React.ReactNode;
	className?: string;
	variant?: BadgeVariant;
}

export default function Badge({
	children,
	className,
	variant = 'default',
}: BadgeProps) {
	return (
		<BadgeStyle $variant={variant} className={className}>
			<BadgeContent>{children}</BadgeContent>
		</BadgeStyle>
	);
}
