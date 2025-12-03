# Conditional Logic - Quick Reference Guide

## 🎯 What It Does

Conditional Logic allows form fields to **show or hide automatically** based on other field values.

**Example:** Show "Menu" field only when "Category" is "Restaurant"

---

## 🚀 Quick Start

### For Backend Developers

#### Add to Field Template (2 steps)

**Step 1:** Add at top of template file:

```php
<?php
$listing_form = directorist()->listing_form;
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes($data);
?>
```

**Step 2:** Add to wrapper div:

```php
<div class="directorist-form-group" <?php echo $conditional_logic_attr; ?>>
    <!-- Your field content -->
</div>
```

**That's it!** ✨

---

## 📋 Conditional Logic Structure

```php
$conditional_logic = [
    'enabled' => true,              // Must be true
    'action'  => 'show',            // 'show' or 'hide'
    'groups'  => [
        [
            'operator'   => 'AND',  // 'AND' or 'OR'
            'conditions' => [
                [
                    'field'    => 'category',     // Field key
                    'operator' => 'is',           // See operators below
                    'value'    => 'Restaurant'    // Value to compare
                ]
            ]
        ]
    ]
];
```

---

## 🔧 Supported Operators

| Operator                | Usage           | Example                          |
| ----------------------- | --------------- | -------------------------------- |
| `is`                    | Exact match     | Category is "Restaurant"         |
| `is not`                | Not equal       | Category is not "Hotel"          |
| `contains`              | Contains text   | Description contains "delicious" |
| `does not contain`      | Doesn't contain | Title doesn't contain "test"     |
| `empty`                 | Field is empty  | Phone is empty                   |
| `not empty`             | Field has value | Email is not empty               |
| `greater than`          | Number >        | Price > 100                      |
| `less than`             | Number <        | Price < 50                       |
| `greater than or equal` | Number >=       | Price >= 100                     |
| `less than or equal`    | Number <=       | Price <= 50                      |
| `starts with`           | Text starts     | Title starts with "Best"         |
| `ends with`             | Text ends       | Title ends with "2024"           |

---

## 🎨 Logic Rules

### Groups = OR Logic

If **ANY** group matches → field shows/hides

### Conditions in Group

- **AND** = ALL conditions must match
- **OR** = ANY condition must match

### Example:

```
Group 1: (Category = Restaurant AND Type = Fine Dining)
Group 2: (Category = Cafe)

Result: Show field if Group 1 OR Group 2 matches
        = (Restaurant AND Fine Dining) OR (Cafe)
```

---

## 📁 File Locations

| What            | Where                                                                   |
| --------------- | ----------------------------------------------------------------------- |
| Helper Function | `includes/model/ListingForm.php` → `get_conditional_logic_attributes()` |
| Frontend Module | `assets/src/js/global/components/conditional-logic.js`                  |
| Main Form JS    | `assets/src/js/global/add-listing.js`                                   |
| Field Templates | `templates/listing-form/fields/` or `custom-fields/`                    |

---

## 🔍 Common Examples

### Show field when category is Restaurant

```php
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
```

### Show field when price > 100

```php
'groups' => [
    [
        'operator' => 'AND',
        'conditions' => [
            [
                'field' => 'price',
                'operator' => 'greater than',
                'value' => '100'
            ]
        ]
    ]
]
```

### Hide field when category is empty

```php
'action' => 'hide',  // Hide instead of show
'groups' => [
    [
        'operator' => 'AND',
        'conditions' => [
            [
                'field' => 'category',
                'operator' => 'empty',
                'value' => ''
            ]
        ]
    ]
]
```

### Multiple conditions (ALL must match)

```php
'groups' => [
    [
        'operator' => 'AND',
        'conditions' => [
            ['field' => 'category', 'operator' => 'is', 'value' => 'Restaurant'],
            ['field' => 'type', 'operator' => 'is', 'value' => 'Fine Dining']
        ]
    ]
]
```

### Multiple conditions (ANY can match)

```php
'groups' => [
    [
        'operator' => 'OR',
        'conditions' => [
            ['field' => 'category', 'operator' => 'is', 'value' => 'Restaurant'],
            ['field' => 'category', 'operator' => 'is', 'value' => 'Cafe']
        ]
    ]
]
```

### Multiple groups (OR logic)

```php
'groups' => [
    // Group 1
    [
        'operator' => 'AND',
        'conditions' => [
            ['field' => 'category', 'operator' => 'is', 'value' => 'Restaurant']
        ]
    ],
    // Group 2 (if Group 1 doesn't match, try this)
    [
        'operator' => 'AND',
        'conditions' => [
            ['field' => 'category', 'operator' => 'is', 'value' => 'Cafe']
        ]
    ]
]
// Result: Show if Restaurant OR Cafe
```

---

## ✅ Checklist

### Backend

- [ ] Added `get_conditional_logic_attributes($data)` call
- [ ] Added `<?php echo $conditional_logic_attr; ?>` to wrapper div
- [ ] Conditional logic is in `$data['options']['conditional_logic']`
- [ ] `enabled` is `true`
- [ ] Field key matches in conditions

### Frontend (if adding new field type)

- [ ] Field selector added to `mapFieldKeyToSelector()`
- [ ] Field value can be retrieved correctly

---

## 🐛 Troubleshooting

| Problem                  | Solution                          |
| ------------------------ | --------------------------------- |
| Field not showing/hiding | Check `enabled: true`             |
| Field always hidden      | Check condition field key matches |
| Condition not working    | Verify field value format matches |
| Invalid JSON error       | Check JSON structure is valid     |
| Field not detected       | Add to `mapFieldKeyToSelector()`  |

---

## 💡 Pro Tips

1. **Test in browser console:** Inspect `data-conditional-logic` attribute
2. **Use AND for strict rules:** All conditions must match
3. **Use OR groups for flexibility:** Multiple ways to show field
4. **Field keys are case-sensitive:** "Category" ≠ "category"
5. **Empty values:** Use `empty` operator, not `is` with empty string

---

**Need more details?** See `CONDITIONAL_LOGIC_REFACTORING.md`

