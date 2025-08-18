/**
 * WordPress dependencies
 */
import { applyFilters } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";

/**
 * External dependencies
 */
import { FieldsType } from "@wpmvc/fields/build-types/types/field";
import React from "react";

/**
 * Internal dependencies
 */
import Card from "../../card.tsx";
import Controls from "../../controls/index.tsx";
import ElementorIcon from "../../icons/elementorIcon.tsx";
import Layout from "./layout.tsx";

// Extended interface to support validation properties
interface ExtendedFieldType extends FieldsType {
  [key: string]: any; // Allow additional properties
}

export default function General({ attributes, setAttributes }) {
  const basicFields: ExtendedFieldType = {
    // custom: {
    //   type: 'custom',
    //   label: 'Custom Field',
    //   description: 'This is a custom field.',
    //   // Validation props that the custom field component will use
    //   validation: {
    //     required: true,
    //     min_length: 3,
    //     max_length: 50,
    //   },
    //   invalid_key: 'custom_invalid',
    //   onChange: (data: any) => {
    //     console.log('Custom field changed:', data.value);
    //   }
    // },
    plan_name: {
      type: "text",
      label: __("What’s the name of your plan?", "directorist"),
      description: __(
        "This is the name of your plan that will be displayed to the users.",
        "directorist",
      ),
    },
    n_radio: {
      type: "n_radio",
      label: __("Select directory type", "directorist"),
      variation: 'boxed-left',
      options: [
        { label: 'Jobs', value: 'jobs', icon: <ElementorIcon /> },
        { label: 'Restaurant', value: 'restaurant', icon: <ElementorIcon /> },
      ],
    }
  };
  const planFields: FieldsType = {
    plan_visibility: {
      type: "select",
      label: __("Visibility", "directorist"),
      options: [
        { label: __("Hidden from all plan", "directorist"), value: "hidden" },
        { label: __("Visible to all plan", "directorist"), value: "visible" },
      ],
      isMulti: false,
    },
    clipboard: {
      type: "clipboard",
      label: __("Embed plan", "directorist"),
      // text: "Hello world"
      text: __("[directorist_pricing_plans id=241]", "directorist"), 
      description: __(
        "Easily embed this plan anywhere with this shortcode",
        "directorist",
      ),
    }
  };
  const basicInfoFields = applyFilters("order-basic-info-fields", basicFields);
  const planStatusFields = applyFilters("order-plan-status-fields", planFields);

  const renderLeftContent = () => {
    return (
      <>
        <Card title="Basic information" icon={<ElementorIcon />}>
          <Controls
            fields={basicInfoFields}
            attributes={attributes}
            setAttributes={setAttributes}
          />
        </Card>
      </>
    );
  };

  const renderRightContent = () => {
    return (
      <>
        <Card title="Plan Status">
          <Controls
            fields={planStatusFields}
            attributes={attributes}
            setAttributes={setAttributes}
          />
        </Card>
      </>
    );
  };

  return (
    <Layout
      views={{
        leftContent: renderLeftContent(),
        rightContent: renderRightContent(),
      }}
    />
  );
}
