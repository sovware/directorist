# Vue.js Conditional Logic Changes - Detailed Documentation

## Overview

This document explains all Vue.js-related changes made during the Conditional Logic refactoring. The Vue.js code is used in the **Admin Form Builder** to allow administrators to configure conditional logic rules visually.

---

## 📁 Vue Files Involved

### 1. **`assets/src/js/admin/vue/mixins/helpers.js`**

- **Purpose:** Shared helper methods for Vue components
- **Key Change:** Updated `evaluateConditionalLogic()` to use **OR logic between groups**

### 2. **`assets/src/js/admin/vue/mixins/form-fields/conditional-logic-field.js`**

- **Purpose:** Vue component/mixin for the conditional logic field builder UI
- **Status:** Already existed, no major changes needed

### 3. **`assets/src/js/admin/vue/modules/Field_List_Component.vue`**

- **Purpose:** Displays fields in the form builder
- **Usage:** Uses `evaluateConditionalLogic()` to show/hide fields in admin preview

---

## 🔧 What Changed and Why

### Change #1: Group Logic Updated (OR between groups)

**File:** `assets/src/js/admin/vue/mixins/helpers.js`

**Location:** `evaluateConditionalLogic()` method (line ~544)

#### Before:

```javascript
// Groups were combined with AND logic (incorrect)
let result =
  groupResults.length > 0
    ? groupResults.every((result) => result === true) // ❌ ALL groups must match
    : true;
```

#### After:

```javascript
// Groups are combined with OR logic (correct)
let result =
  groupResults.length > 0
    ? groupResults.some((result) => result === true) // ✅ ANY group can match
    : true;
```

#### Why This Change?

**Problem:**

- Previously, if you had multiple condition groups, **ALL** groups had to match for the field to show
- This was incorrect behavior - users expected **ANY** group to match (OR logic)

**Example of the Issue:**

```
Group 1: Category = "Restaurant"
Group 2: Category = "Cafe"

Old behavior: Field shows ONLY if BOTH Restaurant AND Cafe (impossible!)
New behavior: Field shows if Restaurant OR Cafe ✅
```

**Solution:**

- Changed from `.every()` (AND) to `.some()` (OR)
- Now matches the frontend behavior
- Aligns with user expectations and common form builder patterns

---

## 📋 Detailed Code Explanation

### 1. `evaluateConditionalLogic()` Method

**Location:** `helpers.js` line 544

**Purpose:** Evaluates conditional logic rules in the admin form builder preview

**How it works:**

```javascript
evaluateConditionalLogic(conditionalLogic, rootFields) {
    // Step 1: Check if conditional logic is enabled
    if (!conditionalLogic || !conditionalLogic.enabled) {
        return true; // Always show if disabled
    }

    // Step 2: Validate groups structure
    if (!conditionalLogic.groups || !Array.isArray(conditionalLogic.groups)) {
        return true; // Always show if no groups
    }

    // Step 3: Evaluate each group
    let groupResults = [];
    for (let group of conditionalLogic.groups) {
        // Evaluate conditions within the group
        let conditionResults = [];
        for (let condition of group.conditions) {
            // Get field value and evaluate condition
            let fieldValue = this.getFieldValueForCondition(rootFields, condition.field);
            let conditionResult = this.evaluateCondition(condition, fieldValue);
            conditionResults.push(conditionResult);
        }

        // Combine conditions based on group operator (AND/OR)
        let groupResult = false;
        if (group.operator === 'OR') {
            groupResult = conditionResults.some((result) => result === true); // ANY condition
        } else {
            groupResult = conditionResults.every((result) => result === true); // ALL conditions
        }
        groupResults.push(groupResult);
    }

    // Step 4: Combine groups with OR logic (CHANGED HERE)
    let result = groupResults.length > 0
        ? groupResults.some((result) => result === true)  // ✅ ANY group matches
        : true;

    // Step 5: Apply show/hide action
    if (conditionalLogic.action === 'hide') {
        return !result;
    }
    return result;
}
```

**Key Points:**

- **Groups use OR:** If ANY group matches → field shows/hides
- **Conditions in group:** Use AND/OR based on `group.operator`
- **Action:** `show` or `hide` determines final result

---

### 2. `getFieldValueForCondition()` Method

**Location:** `helpers.js` line 626

**Purpose:** Gets field value from the form builder's field data structure

**How it works:**

```javascript
getFieldValueForCondition(rootFields, fieldKey) {
    if (!rootFields || !fieldKey) {
        return null;
    }

    // Try to get the field value
    if (typeof rootFields[fieldKey] !== 'undefined') {
        let field = rootFields[fieldKey];

        // If field is an object with a value property, use that
        if (this.isObject(field) && typeof field.value !== 'undefined') {
            return field.value;
        }

        // Otherwise use the field itself if it's a primitive value
        if (typeof field !== 'object') {
            return field;
        }
    }

    return null;
}
```

**Why This Exists:**

- Admin form builder stores field values differently than frontend
- Fields can be objects with `.value` property or primitive values
- This method normalizes the value extraction

---

### 3. `evaluateCondition()` Method

**Location:** `helpers.js` line 655

**Purpose:** Evaluates a single condition (e.g., "category is Restaurant")

**Supported Operators:**

- `is`, `is not` - Exact match
- `contains`, `does not contain` - Text contains
- `empty`, `not empty` - Check if field has value
- `greater than`, `less than`, etc. - Number comparisons
- `starts with`, `ends with` - Text patterns

**Example:**

```javascript
evaluateCondition(
  { field: "category", operator: "is", value: "Restaurant" },
  "Restaurant", // Current field value
);
// Returns: true
```

---

### 4. `evaluateArrayCondition()` Method

**Location:** `helpers.js` line 753

**Purpose:** Handles conditions for multi-select fields (arrays)

**Why Needed:**

- Category field can have multiple values: `['Restaurant', 'Cafe']`
- Need special handling to check if array contains a value

**Example:**

```javascript
evaluateArrayCondition(
  ["Restaurant", "Cafe"], // Field value (array)
  "Restaurant", // Condition value
  "is", // Operator
);
// Returns: true (array contains 'Restaurant')
```

---

### 5. `isEmpty()` Method

**Location:** `helpers.js` line 829

**Purpose:** Checks if a value is empty (null, undefined, empty string, or empty array)

**Usage:**

```javascript
isEmpty(null); // true
isEmpty(""); // true
isEmpty([]); // true
isEmpty("text"); // false
isEmpty(["item"]); // false
```

---

## 🎨 Conditional Logic Field Component

**File:** `assets/src/js/admin/vue/mixins/form-fields/conditional-logic-field.js`

**Purpose:** Provides the UI for building conditional logic rules in the admin

### Key Features:

1. **Toggle Enable/Disable**

   ```javascript
   toggleEnabled() {
       this.localValue.enabled = !this.localValue.enabled;
       this.updateValue();
   }
   ```

2. **Add/Remove Groups**

   ```javascript
   addGroup() {
       this.localValue.groups.push(this.createEmptyGroup());
   }

   removeGroup(groupIndex) {
       // Prevents removing last group
   }
   ```

3. **Add/Remove Conditions**

   ```javascript
   addCondition(groupIndex) {
       this.localValue.groups[groupIndex].conditions.push(
           this.createEmptyCondition()
       );
   }
   ```

4. **Get Available Fields**
   ```javascript
   getFieldsFromRoot() {
       // Traverses Vue component tree to find form builder
       // Returns list of fields that can be used in conditions
   }
   ```

### Data Structure:

```javascript
localValue: {
    enabled: false,        // Enable/disable conditional logic
    action: 'show',        // 'show' or 'hide'
    groups: [              // Array of condition groups
        {
            operator: 'AND',  // 'AND' or 'OR' within group
            conditions: [
                {
                    field: '',      // Field key
                    operator: 'is', // Operator
                    value: ''       // Value to compare
                }
            ]
        }
    ]
}
```

---

## 🔄 How It Works in Admin

### Step-by-Step Flow:

1. **User Opens Field Options**
   - Admin clicks on a field in form builder
   - Options window opens

2. **User Configures Conditional Logic**
   - Clicks "Conditional Logic" tab
   - Toggles "Enable Conditional Logic"
   - Adds condition groups
   - Sets conditions (field, operator, value)

3. **Preview Updates**
   - `Field_List_Component.vue` uses `evaluateConditionalLogic()`
   - Fields show/hide in real-time based on conditions
   - Admin can see how fields will behave

4. **Save Configuration**
   - Conditional logic data saved to field options
   - Passed to frontend via PHP templates

---

## 📊 Comparison: Admin vs Frontend

| Aspect            | Admin (Vue.js)               | Frontend (JavaScript)        |
| ----------------- | ---------------------------- | ---------------------------- |
| **Purpose**       | Configure rules              | Execute rules                |
| **Context**       | Form builder preview         | User-facing form             |
| **Data Source**   | `rootFields` object          | DOM form fields              |
| **Evaluation**    | `evaluateConditionalLogic()` | `evaluateConditionalLogic()` |
| **Group Logic**   | OR (`.some()`)               | OR (`.some()`) ✅            |
| **File Location** | `helpers.js`                 | `conditional-logic.js`       |

**Key Difference:**

- **Admin:** Uses Vue component data (`rootFields`)
- **Frontend:** Reads from actual HTML form fields

**Same Logic:**

- Both use OR logic between groups
- Both support same operators
- Both handle arrays the same way

---

## 🛠️ For Vue Developers

### Using `evaluateConditionalLogic()` in Your Component

**Step 1:** Import the helpers mixin

```javascript
import helpers from "@/admin/vue/mixins/helpers";

export default {
  mixins: [helpers],
  // ... your component
};
```

**Step 2:** Use in your component

```javascript
methods: {
    shouldShowField(field) {
        // Get conditional logic from field options
        const conditionalLogic = field.options?.conditional_logic;

        if (!conditionalLogic) {
            return true; // Always show if no conditional logic
        }

        // Get all field values (rootFields)
        const rootFields = this.getAllFieldValues();

        // Evaluate
        return this.evaluateConditionalLogic(conditionalLogic, rootFields);
    },

    getAllFieldValues() {
        // Return object with field_key as keys and values
        // Example: { category: 'Restaurant', price: '100' }
        return this.fields.reduce((acc, field) => {
            acc[field.widget_key] = field.value || field.default_value;
            return acc;
        }, {});
    }
}
```

### Example: Show/Hide Field in List

```vue
<template>
  <div v-if="shouldShowField(field)">
    <!-- Field content -->
  </div>
</template>

<script>
export default {
  mixins: [helpers],
  props: {
    field: Object,
  },
  methods: {
    shouldShowField(field) {
      const conditionalLogic = field.options?.conditional_logic;
      if (!conditionalLogic || !conditionalLogic.enabled) {
        return true;
      }

      const rootFields = this.getRootFields();
      return this.evaluateConditionalLogic(conditionalLogic, rootFields);
    },

    getRootFields() {
      // Get all field values from form builder
      return this.$parent.active_widget_fields || {};
    },
  },
};
</script>
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Field Not Showing/Hiding in Admin Preview

**Problem:** Conditional logic works on frontend but not in admin preview

**Solution:**

- Check `rootFields` contains the field values
- Verify field keys match between condition and actual fields
- Check `evaluateConditionalLogic()` is being called

**Debug:**

```javascript
// Add console.log in evaluateConditionalLogic
console.log("Conditional Logic:", conditionalLogic);
console.log("Root Fields:", rootFields);
console.log("Result:", result);
```

### Issue 2: Group Logic Not Working

**Problem:** Multiple groups not working correctly

**Solution:**

- Verify using `.some()` for groups (OR logic)
- Check group structure is correct
- Ensure groups array is not empty

### Issue 3: Array Fields Not Working

**Problem:** Category field (multi-select) conditions not working

**Solution:**

- Check `evaluateArrayCondition()` is being called
- Verify field value is actually an array
- Check array comparison logic

---

## 📝 Code Changes Summary

### Changed Files:

1. **`helpers.js`** - Line 606-609

   ```javascript
   // Changed from:
   groupResults.every((result) => result === true); // AND

   // To:
   groupResults.some((result) => result === true); // OR
   ```

### Unchanged Files (but important):

1. **`conditional-logic-field.js`** - UI component (no changes needed)
2. **`Field_List_Component.vue`** - Uses `evaluateConditionalLogic()` (no changes needed)

---

## ✅ Testing Checklist

### Admin Form Builder:

- [ ] Enable conditional logic on a field
- [ ] Add multiple groups
- [ ] Verify ANY group matching shows field (OR logic)
- [ ] Test AND/OR within groups
- [ ] Test with array fields (category)
- [ ] Test with empty/not empty operators
- [ ] Test with number comparisons
- [ ] Verify preview updates in real-time

### Integration:

- [ ] Admin configuration matches frontend behavior
- [ ] Same conditional logic works in both admin and frontend
- [ ] Field keys are consistent between admin and frontend

---

## 🚀 Future Enhancements

### Potential Improvements:

1. **Visual Preview**
   - Show condition evaluation in real-time
   - Highlight which conditions are matching

2. **Field Type Detection**
   - Automatically suggest operators based on field type
   - Show appropriate value input (dropdown for select fields)

3. **Validation**
   - Warn about circular dependencies
   - Validate field keys exist

4. **Performance**
   - Debounce evaluation for large forms
   - Cache evaluation results

---

## 📚 Related Documentation

- **Frontend Implementation:** `CONDITIONAL_LOGIC_REFACTORING.md`
- **Quick Reference:** `CONDITIONAL_LOGIC_QUICK_REFERENCE.md`
- **PHP Implementation:** See main refactoring doc

---

## 💡 Key Takeaways

1. **Groups use OR logic** - ANY group matching shows/hides field
2. **Conditions in group** - Use AND/OR based on `group.operator`
3. **Admin and Frontend** - Use same logic, different data sources
4. **Array handling** - Special logic for multi-select fields
5. **Real-time preview** - Admin can see how fields will behave

---

**Last Updated:** [Current Date]
**Changed By:** [Development Team]

