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

export default function General({ 
  attributes, 
  setAttributes,
}: {
  attributes: any;
  setAttributes: any;
}) {
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
      validation: {
        required: true,
        min_length: 3,
        max_length: 50,
      },
    },
    description: {
      type: "text",
      label: __("Short Description", "directorist")
    },
    n_radio: {
      type: "n_radio",
      label: __("Select directory type", "directorist"),
      variation: 'boxed-right',
      options: [
        { label: 'Jobs', value: 'jobs', icon: <ElementorIcon />, renderRadio: ([option, props] )=> <>Hello</> },
        { label: 'Restaurant', value: 'restaurant', icon: <ElementorIcon />, renderRadio: ([option, props])=> <>Hello</> },
      ],
    },
    listing_count: {
      type: "number",
      label: __("How many listings for this package?", "directorist"),
      min: 1,
      max: 100,
      step: 1,
      defaultValue: 10,
    },
    listing_unlimited: {
      type: "checkbox",
      label: __("Or Mark as Unlimited", "directorist"),
    },
    featured_listing_count: {
      type: "number",
      label: __("Number of featured listing.", "directorist"),
      min: 1,
      max: 100,
      step: 1,
      defaultValue: 10,
    },
    featured_listing_unlimited: {
      type: "checkbox",
      label: __("Or Mark as Unlimited", "directorist"),
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
      type: "text",
      label: __("Embed plan", "directorist"),
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
