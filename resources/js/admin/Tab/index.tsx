import { TabPanel } from '@wordpress/components';
import { useState } from '@wordpress/element';
import React from 'react';
import styled from 'styled-components';

// TypeScript Interfaces
interface TabItem {
    id: string;
    title: string;
    content: React.ReactNode;
    disabled?: boolean;
}

interface TabProps {
    tabs: TabItem[];
    className?: string;
    onTabChange?: (tabName: string) => void;
    orientation?: 'horizontal' | 'vertical';
    disabled?: boolean;
}

interface TabPanelTab {
    name: string;
    title: string;
    className: string;
    disabled: boolean;
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

    .components-tab-panel__tabs-item button {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #50575e;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        margin: 0;
        padding: 12px 20px;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .components-tab-panel__tabs-item button:hover {
        background: #f0f0f1;
        color: #1d2327;
    }

    .components-tab-panel__tabs-item button.is-active {
        background: #fff;
        border-bottom-color: #2271b1;
        color: #1d2327;
        font-weight: 600;
    }

    .components-tab-panel__tabs-item button:focus {
        box-shadow: 0 0 0 1px #2271b1;
        outline: 2px solid transparent;
    }

    .components-tab-panel__tabs-item button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .components-tab-panel__tabs-item button:disabled:hover {
        background: transparent;
        color: #50575e;
    }

    /* Vertical Tab Styles */
    &[data-orientation="vertical"] .components-tab-panel__tabs {
        flex-direction: column;
        border-bottom: none;
        border-right: 1px solid #c3c4c7;
        min-width: 200px;
    }

    &[data-orientation="vertical"] .components-tab-panel__tabs-item button {
        border-bottom: none;
        border-right: 3px solid transparent;
        text-align: left;
        width: 100%;
    }

    &[data-orientation="vertical"] .components-tab-panel__tabs-item button.is-active {
        border-right-color: #2271b1;
    }

    &[data-orientation="vertical"] .components-tab-panel__tabs-item button:hover {
        border-right-color: #72aee6;
    }

    /* Responsive Design */
    @media (max-width: 782px) {
        .components-tab-panel__tabs {
            flex-direction: column;
        }
        
        .components-tab-panel__tabs-item button {
            border-bottom: 1px solid #c3c4c7;
            border-right: none;
            text-align: left;
            width: 100%;
        }
        
        .components-tab-panel__tabs-item button.is-active {
            border-bottom-color: #2271b1;
            border-right-color: transparent;
        }
    }
`;

const TabContent = styled.div`
    padding: 20px;
    min-height: 200px;

    h3 {
        margin-top: 0;
        margin-bottom: 16px;
        font-size: 18px;
        font-weight: 600;
        color: #1d2327;
    }

    p {
        margin-bottom: 16px;
        line-height: 1.6;
        color: #50575e;
    }

    @media (max-width: 782px) {
        padding: 15px;
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
    onTabChange = null,
    orientation = 'horizontal',
    disabled = false 
}: TabProps): React.ReactElement | null {
    const [activeTab, setActiveTab] = useState<string>(tabs.length > 0 ? tabs[0].id : '');

    const handleTabSelect = (tabName: string): void => {
        setActiveTab(tabName);
        if (onTabChange && typeof onTabChange === 'function') {
            onTabChange(tabName);
        }
    };

    // If no tabs provided, return null
    if (!tabs || tabs.length === 0) {
        return null;
    }

    const mapTabsToTabPanel = (): TabPanelTab[] => {
        return tabs.map(tab => ({
            name: tab.id,
            title: tab.title,
            className: `tab-${tab.id}`,
            disabled: tab.disabled || false
        }));
    };

    const renderTabContent = (tab: { name: string }): React.ReactNode => {
        const currentTab = tabs.find(t => t.id === tab.name);
        
        return (
            <TabContent className="tab-content">
                {currentTab?.content || null}
            </TabContent>
        );
    };

    return (
        <StyledTabPanel
            className={className}
            activeClass="is-active"
            orientation={orientation}
            disabled={disabled}
            onSelect={handleTabSelect}
            tabs={mapTabsToTabPanel()}
        >
            {renderTabContent}
        </StyledTabPanel>
    );
}

// Export types for external use
export type { TabItem, TabPanelTab, TabProps };
