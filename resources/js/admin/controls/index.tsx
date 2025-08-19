import { Fields } from "@wpmvc/fields";
import React from "react";
import Checkbox from "./checkbox";
import Clipboard from "./clipboard";
import TextControl from "./custom-field";
import Number from "./number";
import Radio from "./radio";

const components = {
  text: TextControl,
  n_radio: Radio,
  clipboard: Clipboard,
  number: Number,
  checkbox: Checkbox
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
