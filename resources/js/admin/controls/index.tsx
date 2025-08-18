import { Fields } from "@wpmvc/fields";
import React from "react";
import Clipboard from "../clipboard";
import TextControl from "../custom-field";
import Radio from "../radio";

const components = {
  custom: TextControl,
  n_radio: Radio,
  clipboard: Clipboard
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
