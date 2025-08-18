/**
 * WordPress dependencies
 */
import { Fill } from '@wordpress/components';
import { useCallback, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import { FieldsType } from "@wpmvc/fields/build-types/types/field";
import React from "react";

/**
 * Internal dependencies
 */
import { useAttributes } from '@wpmvc/dashboard';
import Tab from '../../Tab';
import Feature from './feature.tsx';
import General from './general.tsx';
import Plan from './plan.tsx';

const editOrderInitialValues = {
    plan_name: '',
    description: 'active',
    directory_type: 'restaurant',
    listing_count: 10,
    is_listing_unlimited: false,
    featured_listing_count: 1,
    is_featured_listing_unlimited: false,
    plan_visibility: 'hidden',
    should_validate: false,
    validationErrors: {}
}

interface ExtendedFieldType extends FieldsType {
    [key: string]: any; // Allow additional properties
  }

export default function Edit(){
    const [activeTab, setActiveTab] = useState('general');
    const [attributes, setAttributes] = useAttributes({ ...editOrderInitialValues });
    const [isSubmitting, setIsSubmitting] = useState(false);

    // console.log(attributes);
    

    

    // Handle form submission
    const handleSubmit = useCallback(async (e: React.FormEvent) => {
        e.preventDefault();
        setAttributes({ should_validate: true });
    }, [attributes]);

    // console.log(activeTab);
    

    return(
        <form onSubmit={handleSubmit}>
            <Fill name="wpmvc-header">
                <Tab
                    className='tab-menu'
                    tabs={[
                        {
                            name: 'general',
                            title: 'General Info'
                        },
                        {
                            name: 'feature',
                            title: 'Feature Configuration'
                        },
                        {
                            name: 'plan',
                            title: 'Plan Settings'
                        }
                    ]}
                    onActiveTab={setActiveTab} 
                />
            </Fill>
		
			{
				activeTab === 'general' && (
					<General 
						attributes={attributes} 
						setAttributes={setAttributes}
					/>
				)
			}
			{
				activeTab === 'feature' && (
					<Feature 
						attributes={attributes} 
						setAttributes={setAttributes}
					/>
				)
			}
			{
				activeTab === 'plan' && (
					<Plan 
						attributes={attributes} 
						setAttributes={setAttributes}
					/>
				)
			}

            <button 
                type="submit" 
                disabled={isSubmitting}
                style={{
                    padding: '10px 20px',
                    backgroundColor: isSubmitting ? '#ccc' : '#007cba',
                    color: 'white',
                    border: 'none',
                    borderRadius: '4px',
                    cursor: isSubmitting ? 'not-allowed' : 'pointer',
                    fontSize: '16px'
                }}
            >
                {isSubmitting ? 'Submitting...' : 'Submit'}
            </button>
		</form>
    )
}