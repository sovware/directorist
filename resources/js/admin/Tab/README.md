# Directorist Tab Component

A flexible and accessible tab component built using WordPress Gutenberg's `TabPanel` component and styled with styled-components. This component is written in TypeScript and provides a clean, modern interface for organizing content into tabs with support for both horizontal and vertical orientations.

## Features

- ✅ Built with WordPress Gutenberg components
- ✅ Written in TypeScript with full type safety
- ✅ Styled with styled-components for better maintainability
- ✅ Support for horizontal and vertical orientations
- ✅ Accessible keyboard navigation
- ✅ Responsive design
- ✅ Customizable styling through styled-components
- ✅ Internationalization support
- ✅ TypeScript-friendly props with proper interfaces
- ✅ Callback support for tab changes

## Installation

The component is already included in the Directorist plugin. Make sure you have the required dependencies:

```json
{
  "dependencies": {
    "@wordpress/components": "^latest",
    "@wordpress/element": "^latest",
    "@wordpress/i18n": "^latest",
    "styled-components": "^latest"
  },
  "devDependencies": {
    "@types/react": "^latest",
    "typescript": "^latest"
  }
}
```

## Basic Usage

```tsx
import Tab from './Tab';
import type { TabItem } from './Tab';

const MyComponent: React.FC = () => {
  const tabs: TabItem[] = [
    {
      id: 'general',
      title: 'General Settings',
      content: <div>General settings content here</div>
    },
    {
      id: 'advanced',
      title: 'Advanced Settings',
      content: <div>Advanced settings content here</div>
    }
  ];

  return (
    <Tab 
      tabs={tabs}
      onTabChange={(tabName: string) => console.log('Tab changed to:', tabName)}
    />
  );
};
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `tabs` | `TabItem[]` | `[]` | Array of tab objects |
| `className` | `string` | `''` | Additional CSS classes |
| `onTabChange` | `(tabName: string) => void` | `undefined` | Callback when tab changes |
| `orientation` | `'horizontal' \| 'vertical'` | `'horizontal'` | Tab orientation |
| `disabled` | `boolean` | `false` | Whether tabs are disabled |

## TypeScript Interfaces

### TabItem Interface

```tsx
interface TabItem {
    id: string;                    // Required: Unique identifier
    title: string;                 // Required: Display title
    content: React.ReactNode;      // Required: Tab content
    disabled?: boolean;            // Optional: Disable this tab
}
```

### TabProps Interface

```tsx
interface TabProps {
    tabs: TabItem[];                                    // Required: Array of tabs
    className?: string;                                  // Optional: CSS classes
    onTabChange?: (tabName: string) => void;            // Optional: Change callback
    orientation?: 'horizontal' | 'vertical';            // Optional: Tab orientation
    disabled?: boolean;                                  // Optional: Disable all tabs
}
```

### TabPanelTab Interface

```tsx
interface TabPanelTab {
    name: string;        // Tab identifier
    title: string;       // Tab display title
    className: string;   // CSS class name
    disabled: boolean;   // Tab disabled state
}
```

## Examples

### Horizontal Tabs (Default)

```tsx
import Tab from './Tab';
import type { TabItem } from './Tab';

const HorizontalTabs: React.FC = () => {
  const tabs: TabItem[] = [
    {
      id: 'overview',
      title: 'Overview',
      content: (
        <div>
          <h3>Overview</h3>
          <p>This is the overview content.</p>
        </div>
      )
    },
    {
      id: 'details',
      title: 'Details',
      content: (
        <div>
          <h3>Details</h3>
          <p>This is the details content.</p>
        </div>
      )
    }
  ];

  return <Tab tabs={tabs} />;
};
```

### Vertical Tabs

```tsx
import Tab from './Tab';
import type { TabItem } from './Tab';

const VerticalTabs: React.FC = () => {
  const tabs: TabItem[] = [
    {
      id: 'profile',
      title: 'Profile',
      content: <div>Profile settings content</div>
    },
    {
      id: 'security',
      title: 'Security',
      content: <div>Security settings content</div>
    }
  ];

  return (
    <Tab 
      tabs={tabs}
      orientation="vertical"
    />
  );
};
```

### With Callback

```tsx
import Tab from './Tab';
import type { TabItem } from './Tab';

const TabsWithCallback: React.FC = () => {
  const handleTabChange = (tabName: string): void => {
    // Handle tab change
    console.log('Active tab:', tabName);
    
    // You can trigger API calls, update state, etc.
    if (tabName === 'settings') {
      // Load settings data
    }
  };

  const tabs: TabItem[] = [
    {
      id: 'dashboard',
      title: 'Dashboard',
      content: <div>Dashboard content</div>
    },
    {
      id: 'settings',
      title: 'Settings',
      content: <div>Settings content</div>
    }
  ];

  return (
    <Tab 
      tabs={tabs}
      onTabChange={handleTabChange}
    />
  );
};
```

### With Custom Styling

```tsx
import Tab from './Tab';
import styled from 'styled-components';
import type { TabItem } from './Tab';

const CustomStyledTabs: React.FC = () => {
  const tabs: TabItem[] = [
    {
      id: 'basic',
      title: 'Basic',
      content: <div>Basic content</div>
    },
    {
      id: 'premium',
      title: 'Premium',
      content: <div>Premium content</div>
    }
  ];

  return (
    <Tab 
      tabs={tabs}
      className="my-custom-tabs"
    />
  );
};
```

## Styling with Styled Components

The component uses styled-components for all styling, which provides several benefits:

1. **CSS-in-JS**: All styles are co-located with the component
2. **Dynamic styling**: Easy to create theme variations
3. **Better maintainability**: No need to manage separate CSS files
4. **Scoped styles**: Styles are automatically scoped to the component
5. **TypeScript support**: Full type safety for styled components

### Available Styled Components

- `TabContainer` - Main container wrapper
- `StyledTabPanel` - Styled TabPanel component
- `TabContent` - Individual tab content wrapper
- `ExampleTabContent` - Special styling for example tabs
- `VerticalTabContent` - Special styling for vertical tabs

### Customizing Styles

You can customize the appearance by extending the styled components:

```tsx
import styled from 'styled-components';
import { TabContainer } from './Tab';

const CustomTabContainer = styled(TabContainer)`
  margin: 40px 0;
  border: 2px solid #e74c3c;
  border-radius: 8px;
`;

// Or override specific styles
const CustomTabPanel = styled(StyledTabPanel)`
  .components-tab-panel__tabs-item button.is-active {
    background: #e74c3c;
    color: white;
  }
`;
```

### Theme Support

The component can easily be adapted to support themes:

```tsx
const ThemedTabPanel = styled(StyledTabPanel)`
  background: ${props => props.theme.background || '#fff'};
  border-color: ${props => props.theme.borderColor || '#c3c4c7'};
  
  .components-tab-panel__tabs-item button.is-active {
    border-bottom-color: ${props => props.theme.primaryColor || '#2271b1'};
  }
`;
```

## TypeScript Benefits

### Type Safety
- Compile-time error checking
- IntelliSense and autocomplete support
- Refactoring safety
- Better documentation through types

### Interface Exports
```tsx
import type { TabProps, TabItem, TabPanelTab } from './Tab';

// Use types in your components
const MyTabs: TabProps = {
  tabs: [],
  orientation: 'vertical'
};
```

### Generic Usage
```tsx
// The component is fully typed
<Tab 
  tabs={tabs} // TypeScript will ensure tabs is TabItem[]
  onTabChange={(tabName: string) => {}} // TypeScript ensures correct signature
  orientation="horizontal" // TypeScript ensures valid values
/>
```

## Accessibility

The component follows WordPress accessibility guidelines:

- Proper ARIA labels and roles
- Keyboard navigation support
- Focus management
- Screen reader compatibility

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- WordPress minimum requirements
- Responsive design for mobile devices

## Troubleshooting

### Common Issues

1. **Tabs not rendering**: Ensure `tabs` array is not empty
2. **TypeScript errors**: Check that all required props are provided
3. **Styling issues**: Check if styled-components is properly installed
4. **Orientation not working**: Verify `orientation` prop value is correct

### Debug Mode

Enable console logging to debug tab changes:

```tsx
<Tab 
  tabs={tabs}
  onTabChange={(tabName: string) => {
    console.log('Tab changed:', tabName);
    console.log('Available tabs:', tabs);
  }}
/>
```

## Contributing

When contributing to this component:

1. Follow WordPress coding standards
2. Maintain TypeScript type safety
3. Test with different tab configurations
4. Ensure accessibility compliance
5. Update documentation for new features
6. Use styled-components for any new styling needs

## License

This component is part of the Directorist plugin and follows the same license terms.
