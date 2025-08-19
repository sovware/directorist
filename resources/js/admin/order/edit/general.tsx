/**
 * WordPress dependencies
 */
import { applyFilters } from "@wordpress/hooks";

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
  cards,
  errors,
  setErrors
}: {
  attributes: any;
  setAttributes: any;
  cards: any;
  errors: object;
  setErrors: any;
}) {
  
  const basicInfoFields = applyFilters("order-basic-info-fields", cards?.basicFields);
  const planStatusFields = applyFilters("order-plan-status-fields", cards?.planFields);

  const renderLeftContent = () => {
    return (
      <>
        <Card title="Basic information" icon={<ElementorIcon />}>
          <Controls
            fields={basicInfoFields}
            attributes={attributes}
            setAttributes={setAttributes}
            errors={errors}
            setErrors={setErrors}
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
            errors={errors}
            setErrors={setErrors}
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
