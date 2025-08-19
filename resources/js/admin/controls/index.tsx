import { Fields } from "@wpmvc/fields";
import React from "react";
import Text from "./custom-field";
import Radio from "./radio";

const components = {
  text: Text,
  n_radio: Radio,
  // clipboard: Clipboard,
  // number: Number,
  // checkbox: Checkbox
  // You can override built-in types too, e.g. text: MyTextField
};

export default function Controls(props) {
  const { fields, attributes, setAttributes, errors, setErrors } = props;
  console.log('control', errors);
  
  return (
    <Fields
      fields={fields}
      attributes={attributes}
      setAttributes={setAttributes}
      components={components}
      errors={errors}
      setErrors={setErrors}
      // validationTrigger={validationTrigger}
    />
  );
}
