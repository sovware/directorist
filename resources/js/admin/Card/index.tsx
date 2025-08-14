import React from 'react';
import styled from 'styled-components';

// TypeScript Interfaces
interface CardProps {
    title: string;
    icon?: React.ReactNode;
    children?: React.ReactNode;
    className?: string;
}

// Styled Components
const CardContainer = styled.div`
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 24px;
    margin-bottom: 20px;
`;

const CardHeader = styled.div`
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
`;

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
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
`;

const CardContent = styled.div`
    min-height: 20px;
`;

// Main Card Component
export default function Card({ 
    title, 
    icon, 
    children, 
    className,
}: CardProps): React.ReactElement {
    return (
        <CardContainer className={className}>
            <CardHeader>
                {icon && <CardIcon>{icon}</CardIcon>}
                <CardTitle>{title}</CardTitle>
            </CardHeader>
            <CardContent>
                { children }
            </CardContent>
        </CardContainer>
    );
}

// Export types for external use
export type { CardProps };

