import { Fields } from "@wpmvc/fields";
import React from "react";
import TextControl from "../custom-field";

const components = {
  custom: TextControl,
  // You can override built-in types too, e.g. text: MyTextField
};

export default function Controls(props) {
  const { fields, attributes, setAttributes } = props;

  return (
    <Fields
      fields={fields}
      attributes={attributes}
      setAttributes={setAttributes}
      components={components}
    />
  );
}
