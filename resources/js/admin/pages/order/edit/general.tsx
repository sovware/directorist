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
import Card from "../../../card.tsx";
import Controls from "../../../controls/index.tsx";
import ElementorIcon from "../../../icons/elementorIcon.tsx";
import Layout from "./layout.tsx";

// Extended interface to support validation properties
interface ExtendedFieldType extends FieldsType {
  [key: string]: any; // Allow additional properties
}

export default function General({ attributes, setAttributes }) {
  const basicFields: ExtendedFieldType = {
    custom: {
      type: 'custom',
      label: 'Custom Field',
      description: 'This is a custom field.',
      // Validation props that the custom field component will use
      validation: {
        required: true,
        minLength: 3,
        maxLength: 50,
      },
      help_text: 'Enter a custom value between 3-50 characters (letters, numbers, spaces only)',
      invalid_key: 'custom_invalid',
      onChange: (data: any) => {
        console.log('Custom field changed:', data.value);
      }
    },
    plan_name: {
      type: "text",
      label: __("What’s the name of your plan?", "directorist"),
      description: __(
        "This is the name of your plan that will be displayed to the users.",
        "directorist",
      ),
    },
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
