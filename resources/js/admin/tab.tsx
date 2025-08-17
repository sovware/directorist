import { TabPanel } from '@wordpress/components';
import React from 'react';
import styled from 'styled-components';

interface TabProps {
    tabs: Array<{
        name: string;
        title: string;
        content?: React.ReactNode;
    }>;
    className?: string;
    onActiveTab?: (tabName: string) => void;
}

// Styled Components
const StyledTabPanel = styled(TabPanel)`
    background: #fff;
    height: 100%;

    .components-tab-panel__tabs {
        display: flex;
        margin: 0;
        padding: 0;
        height: 100%;
        list-style: none;
    }

    .components-tab-panel__tabs-item {
        margin: 0;
        padding: 0 12px;
        height: 100% !important;
    }

    /* Responsive Design */
    @media (max-width: 782px) {
        .components-tab-panel__tabs {
            flex-direction: column;
        }
    }
`;

/**
 * Tab Component using WordPress Gutenberg TabPanel
 * 
 * @param props Component props
 * @returns React component
 */
export default function Tab({
    tabs = [],
    className = '',
    onActiveTab,
}: TabProps): React.ReactElement | null {
    const handleTabSelect = (tabName: string) => {
        if (onActiveTab) {
            onActiveTab(tabName);
        }
    };

    return (
        <StyledTabPanel
            className={className}
            onSelect={handleTabSelect}
            tabs={tabs.map(tab => ({
                name: tab.name,
                title: tab.title,
                className: `tab-${tab.name}`
            }))}
        >
            {(tab) => {
                const currentTab = tabs.find(t => t.name === tab.name);
                return currentTab?.content || null;
            }}
        </StyledTabPanel>
    );
}

// Export types for external use
export type { TabProps };
