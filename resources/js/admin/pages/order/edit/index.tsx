/**
 * WordPress dependencies
 */
import { Fill } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * External dependencies
 */
import React from "react";

/**
 * Internal dependencies
 */
import { useAttributes } from '@wpmvc/dashboard';
import Tab from '../../../Tab';
import Feature from './feature.tsx';
import General from './general.tsx';
import Plan from './plan.tsx';

const editOrderInitialValues = {
    plan_name: 'Basic',
    description: 'active',
    directory_type: 'restaurant',
    listing_count: 10,
    plan_visibility: 'hidden'
}

export default function Edit(){
    const [activeTab, setActiveTab] = useState('general');
    const [attributes, setAttributes] = useAttributes({ editOrderInitialValues });

    return(
        <form action="">
            <Fill name="wpmvc-header">
                <Tab
                    className='tab-menu'
                    tabs={[
                        {
                            name: 'general',
                            title: 'General ino'
                        },
                        {
                            name: 'feature',
                            title: 'Feature Configuration'
                        },
                        {
                            name: 'plan',
                            title: 'Plan settings'
                        }
                    ]}
                    onActiveTab={setActiveTab} 
                />
            </Fill>
		
			{
				activeTab === 'general' && <General attributes={attributes} setAttributes={setAttributes} />
			}
			{
				activeTab === 'feature' && <Feature />
			}
			{
				activeTab === 'plan' && <Plan />
			}
		</form>
    )
}