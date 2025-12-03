# Conditional Logic Refactoring Documentation

## Overview

This document explains the refactoring changes made to the Conditional Logic feature implementation. The main goal was to **separate conditional logic code into a dedicated module** for better code organization, maintainability, and reusability.

---

## What Changed?

### Before

- All conditional logic JavaScript code was in `add-listing.js` (over 2300+ lines)
- Code was duplicated and hard to maintain
- Functions were tightly coupled with the main file

### After

- Conditional logic code is now in a separate module: `components/conditional-logic.js`
- `add-listing.js` imports and uses the module (reduced to ~1530 lines)
- Clean, modular architecture
- No code duplication
- Removed all debug console.log statements

---

## 📁 File Structure

```
assets/src/js/global/
├── add-listing.js                    # Main form handler (imports conditional logic)
└── components/
    └── conditional-logic.js          # Standalone conditional logic module
```

---

## 🔧 Technical Changes

### Files Modified

1. **`assets/src/js/global/add-listing.js`**
   - Removed ~800 lines of duplicate conditional logic code
   - Added imports from `conditional-logic.js`
   - Added wrapper functions to bind dependencies (jQuery, getWrapper)
   - Removed all debug console.log statements

2. **`assets/src/js/global/components/conditional-logic.js`** (NEW/REFACTORED)
   - Contains all conditional logic evaluation functions
   - Exports functions as ES6 modules
   - Removed all debug console.log statements
   - Kept console.error for error handling

---

## 👨‍💻 For Frontend Developers

### How Conditional Logic Works

The conditional logic feature allows form fields to **show or hide dynamically** based on values of other fields.

#### Example:

```javascript
// If "category" field equals "Restaurant", show "menu" field
// If "category" field equals "Hotel", show "rooms" field
```

### Architecture

```
┌─────────────────────────────────────┐
│   add-listing.js                    │
│   (Main Form Handler)               │
│                                     │
│   ┌─────────────────────────────┐  │
│   │ Imports from:               │  │
│   │ conditional-logic.js        │  │
│   └─────────────────────────────┘  │
│                                     │
│   - Wraps functions with            │
│     jQuery & getWrapper            │
│   - Initializes on page load        │
│   - Listens for field changes       │
└─────────────────────────────────────┘
           │
           │ uses
           ▼
┌─────────────────────────────────────┐
│   conditional-logic.js              │
│   (Pure Logic Module)               │
│                                     │
│   - Evaluates conditions            │
│   - Gets field values               │
│   - Shows/hides fields              │
│   - No jQuery dependency            │
│   - Reusable functions              │
└─────────────────────────────────────┘
```

### Key Functions (Frontend)

#### 1. **Field Value Retrieval**

```javascript
getFieldValue(fieldKey, $);
```

- Gets the current value of any form field
- Handles different field types (text, select, checkbox, Select2, etc.)
- Returns value in a consistent format

#### 2. **Condition Evaluation**

```javascript
evaluateCondition(condition, fieldValue);
```

- Evaluates a single condition (e.g., "equals", "contains", "greater than")
- Supports multiple operators (is, is not, contains, empty, etc.)
- Returns true/false

#### 3. **Conditional Logic Evaluation**

```javascript
evaluateConditionalLogic(conditionalLogic, getFieldValueFn);
```

- Evaluates all condition groups
- Groups are combined with **OR** logic (if ANY group matches, show field)
- Conditions within a group use **AND/OR** based on group operator
- Returns true/false (should field be shown?)

#### 4. **Apply Conditional Logic**

```javascript
applyConditionalLogic($fieldWrapper, evaluateConditionalLogicFn, $);
```

- Shows or hides a field based on evaluation result
- Enables/disables form inputs
- Handles TinyMCE editors

### How to Use (For Frontend Devs)

#### Adding Conditional Logic to a New Field

**1. Backend provides data attributes:**

```html
<div
  class="directorist-form-group"
  data-conditional-logic='{"enabled":true,"groups":[...]}'
  data-field-key="menu"
>
  <!-- Field content -->
</div>
```

**2. Frontend automatically handles it:**

- The code in `add-listing.js` automatically:
  - Finds all fields with `data-conditional-logic`
  - Evaluates conditions on page load
  - Re-evaluates when dependent fields change

#### Extending Field Support

To add support for a new field type, update `mapFieldKeyToSelector()`:

```javascript
// In conditional-logic.js
function mapFieldKeyToSelector(fieldKey) {
  const fieldKeyMap = {
    // ... existing mappings
    my_new_field: '[name="my_new_field"], #my_new_field',
  };
  return fieldKeyMap[fieldKey] || null;
}
```

#### Debugging

**Error Handling:**

- Check browser console for `console.error` messages
- All errors are logged with context

**Common Issues:**

1. **Field not showing/hiding:**
   - Check `data-conditional-logic` attribute exists
   - Verify JSON is valid
   - Check field key matches condition field name

2. **Field value not detected:**
   - Check field selector in `mapFieldKeyToSelector()`
   - Verify field name/id matches

### Code Examples

#### Testing Conditional Logic Manually

```javascript
// In browser console
const conditionalLogic = {
  enabled: true,
  action: "show",
  groups: [
    {
      operator: "AND",
      conditions: [
        {
          field: "category",
          operator: "is",
          value: "Restaurant",
        },
      ],
    },
  ],
};

// Get evaluation result
const shouldShow = evaluateConditionalLogicFn(conditionalLogic);
console.log("Should show:", shouldShow);
```

---

## 👨‍💼 For Backend Developers

### How Conditional Logic Data is Passed

Backend PHP code passes conditional logic configuration to frontend via **HTML data attributes**.

### PHP Structure

The conditional logic configuration structure:

```php
$conditional_logic = [
    'enabled' => true,           // Enable/disable feature
    'action'  => 'show',         // 'show' or 'hide'
    'groups'  => [               // Array of condition groups
        [
            'operator'   => 'AND',  // 'AND' or 'OR' within group
            'conditions' => [
                [
                    'field'    => 'category',      // Field key
                    'operator' => 'is',            // Operator
                    'value'    => 'Restaurant'     // Value to compare
                ],
                // More conditions...
            ]
        ],
        // More groups...
    ]
];
```

### Where to Add Conditional Logic Attributes

All field templates use the helper function:

```php
// In any field template (e.g., description.php, category.php, etc.)
<?php
$listing_form = directorist()->listing_form;
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes($data);
?>

<div class="directorist-form-group" <?php echo $conditional_logic_attr; ?>>
    <!-- Field content -->
</div>
```

### Helper Function

**Location:** `includes/model/ListingForm.php`

**Function:** `get_conditional_logic_attributes($data)`

**What it does:**

- Extracts conditional logic from `$data['conditional_logic_data']` or `$data['options']['conditional_logic']`
- Validates the configuration
- Returns HTML attributes: `data-conditional-logic` and `data-field-key`
- Returns empty string if conditional logic is disabled/not found

**Example usage:**

```php
$data = [
    'field_key' => 'description',
    'options' => [
        'conditional_logic' => [
            'enabled' => true,
            'action' => 'show',
            'groups' => [
                [
                    'operator' => 'AND',
                    'conditions' => [
                        [
                            'field' => 'category',
                            'operator' => 'is',
                            'value' => 'Restaurant'
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$attr = $listing_form->get_conditional_logic_attributes($data);
// Returns: 'data-conditional-logic="..." data-field-key="description"'
```

### Field Templates Already Updated

All preset and custom field templates have been updated to include conditional logic attributes:

**Preset Fields:**

- `templates/listing-form/fields/description.php`
- `templates/listing-form/fields/category.php`
- `templates/listing-form/fields/tag.php`
- `templates/listing-form/fields/title.php`
- ... (all preset fields)

**Custom Fields:**

- `templates/listing-form/custom-fields/text.php`
- `templates/listing-form/custom-fields/textarea.php`
- `templates/listing-form/custom-fields/select.php`
- `templates/listing-form/custom-fields/checkbox.php`
- ... (all custom field types)

### Adding Conditional Logic to a New Field Template

**Step 1:** Add the helper call at the top:

```php
<?php
$listing_form = directorist()->listing_form;
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes($data);
?>
```

**Step 2:** Add attributes to the wrapper div:

```php
<div class="directorist-form-group" <?php echo $conditional_logic_attr; ?>>
    <!-- Your field content -->
</div>
```

That's it! The frontend will automatically handle the conditional logic.

### Conditional Logic Operators

Supported operators for conditions:

| Operator                | Description       | Example                            |
| ----------------------- | ----------------- | ---------------------------------- |
| `is`                    | Exact match       | `category` is `Restaurant`         |
| `is not`                | Not equal         | `category` is not `Hotel`          |
| `contains`              | Contains text     | `description` contains `delicious` |
| `does not contain`      | Doesn't contain   | `title` does not contain `test`    |
| `empty`                 | Field is empty    | `phone` is empty                   |
| `not empty`             | Field has value   | `email` is not empty               |
| `greater than`          | Number comparison | `price` > `100`                    |
| `less than`             | Number comparison | `price` < `50`                     |
| `greater than or equal` | Number comparison | `price` >= `100`                   |
| `less than or equal`    | Number comparison | `price` <= `50`                    |
| `starts with`           | Text starts with  | `title` starts with `Best`         |
| `ends with`             | Text ends with    | `title` ends with `2024`           |

### Group Logic

**Groups are combined with OR:**

- If **ANY** group's conditions match → field is shown/hidden

**Conditions within a group:**

- Use `operator: "AND"` → **ALL** conditions must match
- Use `operator: "OR"` → **ANY** condition must match

**Example:**

```php
$conditional_logic = [
    'enabled' => true,
    'action' => 'show',
    'groups' => [
        // Group 1: Show if category is Restaurant AND type is Fine Dining
        [
            'operator' => 'AND',
            'conditions' => [
                ['field' => 'category', 'operator' => 'is', 'value' => 'Restaurant'],
                ['field' => 'type', 'operator' => 'is', 'value' => 'Fine Dining']
            ]
        ],
        // Group 2: OR show if category is Cafe
        [
            'operator' => 'AND',
            'conditions' => [
                ['field' => 'category', 'operator' => 'is', 'value' => 'Cafe']
            ]
        ]
    ]
];
// Result: Field shows if (Restaurant AND Fine Dining) OR (Cafe)
```

### Common Backend Tasks

#### 1. Enable Conditional Logic for a Field

In the admin builder, the conditional logic is configured via Vue component (`form-fields/conditional-logic-field.js`). The data is stored in field options:

```php
$field['options']['conditional_logic'] = [
    'enabled' => true,
    // ... configuration
];
```

#### 2. Programmatically Set Conditional Logic

```php
// In your PHP code
$field_data = [
    'field_key' => 'menu',
    'options' => [
        'conditional_logic' => [
            'enabled' => true,
            'action' => 'show',
            'groups' => [
                [
                    'operator' => 'AND',
                    'conditions' => [
                        [
                            'field' => 'category',
                            'operator' => 'is',
                            'value' => 'Restaurant'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
```

#### 3. Debug Conditional Logic

Check the HTML output:

```html
<!-- Check if data attribute exists -->
<div
  class="directorist-form-group"
  data-conditional-logic='{"enabled":true,...}'
  data-field-key="menu"
></div>
```

If the attribute is missing:

- Check `$data['options']['conditional_logic']` exists
- Verify `enabled` is `true`
- Ensure helper function is called in template

---

## 🚀 Future Development Guidelines

### Adding New Operators

**1. Backend (PHP):**
Add operator to admin builder options in `form-fields/conditional-logic-field.js`

**2. Frontend (JavaScript):**
Add case in `evaluateCondition()` function:

```javascript
// In conditional-logic.js
function evaluateCondition(condition, fieldValue) {
  // ... existing code
  switch (operator) {
    // ... existing cases
    case "my_new_operator":
      // Your logic here
      return /* true/false */;
  }
}
```

### Adding New Field Types

**1. Update Field Mapping:**

```javascript
// In conditional-logic.js - mapFieldKeyToSelector()
const fieldKeyMap = {
  // ... existing
  my_field_type: '[name="my_field_type"], #my_field_type',
};
```

**2. Update Value Retrieval:**
If needed, add special handling in `getFieldValue()`:

```javascript
// In conditional-logic.js - getFieldValue()
if (fieldKey === "my_field_type") {
  // Special handling for your field type
  // Return value in consistent format
}
```

### Testing

**Manual Testing:**

1. Set up conditional logic in admin
2. Check frontend form behavior
3. Verify field shows/hides correctly
4. Test all operators

**Debugging:**

- Use browser DevTools to inspect `data-conditional-logic` attribute
- Check console for errors (console.error messages)
- Verify field keys match between condition and target field

---

## 📝 Key Points to Remember

### For Everyone

1. **Conditional logic is automatic** - Once data attributes are set, frontend handles everything
2. **Groups use OR logic** - If ANY group matches, field is shown/hidden
3. **Field keys must match** - The `field` in conditions must match the `data-field-key` attribute
4. **JSON must be valid** - Invalid JSON will cause errors

### For Frontend Devs

1. **Module is reusable** - Can import `conditional-logic.js` in other files if needed
2. **Functions are pure** - No jQuery dependency in the module itself
3. **Error handling** - All errors are logged with console.error

### For Backend Devs

1. **Use helper function** - Always use `get_conditional_logic_attributes($data)`
2. **Check data structure** - Conditional logic must be in `options.conditional_logic`
3. **Enable flag** - Must have `enabled: true` for it to work

---

## 🔍 File Locations Summary

### JavaScript Files

- **Main form handler:** `assets/src/js/global/add-listing.js`
- **Conditional logic module:** `assets/src/js/global/components/conditional-logic.js`
- **Admin builder helpers:** `assets/src/js/admin/vue/mixins/helpers.js`

### PHP Files

- **Helper function:** `includes/model/ListingForm.php` (method: `get_conditional_logic_attributes()`)
- **Field templates:** `templates/listing-form/fields/` (preset fields)
- **Custom field templates:** `templates/listing-form/custom-fields/` (custom fields)

---

## ✅ Checklist for Adding Conditional Logic to New Field

### Backend Checklist

- [ ] Field template includes `get_conditional_logic_attributes($data)` call
- [ ] Wrapper div has `<?php echo $conditional_logic_attr; ?>` attribute
- [ ] Conditional logic data is in `$data['options']['conditional_logic']`
- [ ] `enabled` flag is set to `true`

### Frontend Checklist

- [ ] Field key is mapped in `mapFieldKeyToSelector()` (if needed)
- [ ] Field value can be retrieved by `getFieldValue()`
- [ ] Field selector matches actual HTML field name/id

---

## 📞 Support

If you encounter issues:

1. Check browser console for errors
2. Verify data attributes in HTML
3. Check field key matches
4. Review this documentation

---

**Last Updated:** [Current Date]
**Refactored By:** [Development Team]

