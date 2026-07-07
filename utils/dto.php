<?php

namespace Directorist\Utils;

// die( '@here' );

\defined('ABSPATH') || exit;
use ReflectionClass;
/**
 * Abstract class DTO (Data Transfer Object)
 *
 * Provides a base implementation for DTOs that facilitates converting object properties
 * to an associative array and checking if properties are initialized.
 */
abstract class DTO
{
    protected array $exclude_to_array = ['id'];
    /**
     * Get the value of exclude_to_array
     *
     * @return array
     */
    public function get_exclude_to_array() : array
    {
        return $this->exclude_to_array;
    }
    /**
     * Set the value of exclude_to_array
     *
     * @param array $exclude_to_array 
     *
     * @return self
     */
    public function set_exclude_to_array(array $exclude_to_array) : self
    {
        $this->exclude_to_array = $exclude_to_array;
        return $this;
    }
    /**
     * Converts the object properties to an associative array.
     *
     * @return array The associative array of property names and their values.
     */
    public function to_array()
    {
        $reflection = new ReflectionClass($this);
        // Create a reflection class instance for the current object
        $values = [];
        $exclude_to_array = $this->exclude_to_array;
        $exclude_to_array[] = 'exclude_to_array';
        // Loop through each property of the object
        foreach ($reflection->getProperties() as $property) {
            $property_name = $property->getName();
            // Skip property if the property is excluded
            if (\in_array($property_name, $exclude_to_array)) {
                continue;
            }
            // Access the property using reflection
            $prop = $reflection->getProperty($property_name);
            $prop->setAccessible(\true);
            // Check if the property is initialized
            if (!$prop->isInitialized($this)) {
                continue;
            }
            // Use a method to get the property value if it's a boolean
            if ($prop->getType() && 'bool' === $prop->getType()->getName()) {
                $values[$property_name] = $this->{"is_{$property_name}"}();
                continue;
            }
            // Otherwise, use the corresponding getter method
            $values[$property_name] = $this->children_to_array($this->{"get_{$property_name}"}());
        }
        return $values;
        // Return the associative array of properties and their values
    }
    /**
     * Recursively converts DTO properties and their children to an associative array.
     *
     * This method checks if the provided value is an instance of the DTO class or an array
     * and recursively converts all nested DTO instances to arrays. This is useful for 
     * ensuring all properties, including nested DTOs, are properly serialized.
     *
     * @param mixed $value The value to be converted, which could be an object, array, or scalar.
     * @return mixed The converted value, with DTO objects transformed into arrays.
     */
    protected function children_to_array($value)
    {
        if ($value instanceof DTO) {
            return $value->to_array();
        }
        if (!\is_array($value)) {
            return $value;
        }
        return \array_map(function ($item) {
            if ($item instanceof DTO) {
                return $item->to_array();
            }
            return $item;
        }, $value);
    }
    /**
     * Checks if a specific property is initialized.
     *
     * @param string $property The property name to check.
     * @return bool True if the property is initialized, false otherwise.
     */
    public function is_initialized(string $property) : bool
    {
        $reflection = new ReflectionClass($this);
        // Create a reflection class instance for the current object
        // Check if the property exists in the class
        if (!$reflection->hasProperty($property)) {
            return \false;
        }
        // Access the property using reflection
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(\true);
        // Return whether the property is initialized
        return $prop->isInitialized($this);
    }
}
