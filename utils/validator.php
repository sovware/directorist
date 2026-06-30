<?php

namespace Directorist\Utils;

\defined("ABSPATH") || exit;

use WP_REST_Request;

class RequestValidator {
    public WP_REST_Request $wp_rest_request;
    public Mime $mime;
    protected array $explode_rules;
    public array $errors = [];
    protected array $available_rules = [
        'required',
        'email',
        'max',
        'min',
        'string',
        'integer',
        'numeric',
        'uuid',
        'url',
        'file',
        'mimes',
        // file types
        'array',
        'boolean',
        'mac_address',
    ];
    use DateTime;
    public function __construct(WP_REST_Request $wp_rest_request, Mime $mime)
    {
        $this->wp_rest_request = $wp_rest_request;
        $this->mime = $mime;
    }
    public function validate(array $rules, bool $throw_errors = \true)
    {
        foreach ($rules as $input_name => $rule) {
            $explode_rules = \explode('|', $rule);
            $this->explode_rules = $explode_rules;
            foreach ($explode_rules as $explode_rule) {
                static::validate_rule($input_name, $explode_rule);
            }
        }
        if ($throw_errors && !empty($this->errors)) {
            throw (new Exception('', 422))->set_messages($this->errors);
        }
        return $this->errors;
    }
    public function is_fail()
    {
        return !empty($this->errors);
    }
    protected function validate_rule(string $input_name, string $rule)
    {
        $rule_explode = \explode(':', $rule, 2);
        $method = "{$rule_explode[0]}_validator";
        if (\method_exists(static::class, $method)) {
            static::$method($input_name, isset($rule_explode[1]) ? $rule_explode[1] : null);
        } else {
            throw new \Exception("{$rule_explode[0]} rule not found");
        }
    }
    protected function required_validator(string $input_name)
    {
        if ($this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $files = $this->wp_rest_request->get_file_params();
        if (!empty($files[$input_name])) {
            return;
        }
        $this->set_error($input_name, 'required', [':attribute'], [$input_name]);
    }
    protected function string_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $value = $this->wp_rest_request->get_param($input_name);
        if (\is_string($value)) {
            return;
        }
        $this->set_error($input_name, 'string', [':attribute'], [$input_name]);
    }
    protected function max_validator(string $input_name, int $max)
    {
        if ($this->wp_rest_request->has_param($input_name)) {
            $value = $this->wp_rest_request->get_param($input_name);
            if (\in_array('numeric', $this->explode_rules) || \in_array('integer', $this->explode_rules)) {
                $value = \intval($value);
                if ($value > $max) {
                    $message_key = 'max.numeric';
                }
            } elseif (\in_array('array', $this->explode_rules)) {
                if (!\is_array($value) || \count($value) > $max) {
                    $message_key = 'max.array';
                }
            } else {
                if (!\is_string($value) || \strlen($value) > $max) {
                    $message_key = 'max.string';
                }
            }
            if (!empty($message_key)) {
                $this->set_error($input_name, $message_key, [':attribute', ':max'], [$input_name, $max]);
            }
        } elseif (\in_array('file', $this->explode_rules)) {
            $files = $this->wp_rest_request->get_file_params();
            if (empty($files[$input_name]['size'])) {
                return;
            }
            $file_size = $files[$input_name]['size'] / 1024;
            //KB
            if ($file_size <= $max) {
                return;
            }
            $this->set_error($input_name, 'max.file', [':attribute', ':max'], [$input_name, $max]);
        }
    }
    protected function min_validator(string $input_name, int $min)
    {
        if ($this->wp_rest_request->has_param($input_name)) {
            $value = $this->wp_rest_request->get_param($input_name);
            if (\in_array('numeric', $this->explode_rules) || \in_array('integer', $this->explode_rules)) {
                $value = \intval($value);
                if ($value < $min) {
                    $message_key = 'min.numeric';
                }
            } elseif (\in_array('array', $this->explode_rules)) {
                if (!\is_array($value) || \count($value) < $min) {
                    $message_key = 'min.array';
                }
            } else {
                if (!\is_string($value) || \strlen($value) < $min) {
                    $message_key = 'min.string';
                }
            }
            if (!empty($message_key)) {
                $this->set_error($input_name, $message_key, [':attribute', ':min'], [$input_name, $min]);
            }
        } elseif (\in_array('file', $this->explode_rules)) {
            $files = $this->wp_rest_request->get_file_params();
            if (empty($files[$input_name]['size'])) {
                return;
            }
            $file_size = $files[$input_name]['size'] / 1024;
            //KB
            if ($file_size >= $min) {
                return;
            }
            $this->set_error($input_name, 'min.file', [':attribute', ':min'], [$input_name, $min]);
        }
    }
    protected function boolean_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || \is_bool($this->wp_rest_request->get_param($input_name))) {
            return;
        }
        $this->set_error($input_name, 'boolean', [':attribute'], [$input_name]);
    }
    protected function uuid_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || wp_is_uuid($this->wp_rest_request->get_param($input_name))) {
            return;
        }
        $this->set_error($input_name, 'uuid', [':attribute'], [$input_name]);
    }
    protected function url_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || \filter_var($this->wp_rest_request->get_param($input_name), \FILTER_VALIDATE_URL)) {
            return;
        }
        $this->set_error($input_name, 'url', [':attribute'], [$input_name]);
    }
    protected function mac_address_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $value = $this->wp_rest_request->get_param($input_name);
        if (\is_string($value) && \preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value)) {
            return;
        }
        $this->set_error($input_name, 'mac_address', [':attribute'], [$input_name]);
    }
    protected function email_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $value = $this->wp_rest_request->get_param($input_name);
        if (\is_string($value) && is_email($value)) {
            return;
        }
        $this->set_error($input_name, 'email', [':attribute'], [$input_name]);
    }
    protected function array_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || \is_array($this->wp_rest_request->get_param($input_name))) {
            return;
        }
        $this->set_error($input_name, 'array', [':attribute'], [$input_name]);
    }
    protected function numeric_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || \is_numeric($this->wp_rest_request->get_param($input_name))) {
            return;
        }
        $this->set_error($input_name, 'numeric', [':attribute'], [$input_name]);
    }
    protected function integer_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name) || \is_int($this->wp_rest_request->get_param($input_name))) {
            return;
        }
        $this->set_error($input_name, 'integer', [':attribute'], [$input_name]);
    }
    protected function file_validator(string $input_name)
    {
        $files = $this->wp_rest_request->get_file_params();
        if (!isset($files[$input_name])) {
            return;
        }
        if (!empty($files[$input_name]['size'])) {
            return;
        }
        $this->set_error($input_name, 'file', [':attribute'], [$input_name]);
    }
    public function confirmed_validator(string $input_name)
    {
        $value1 = $this->wp_rest_request->get_param($input_name);
        $value2 = $this->wp_rest_request->get_param("{$input_name}_confirmation");
        if ($value1 === $value2) {
            return;
        }
        $this->set_error($input_name, 'confirmed', [':attribute'], [$input_name]);
    }
    protected function mimes_validator(string $input_name, string $mimes)
    {
        $files = $this->wp_rest_request->get_file_params();
        if (empty($files[$input_name]) || $this->mime->validate($files[$input_name], $mimes)) {
            return;
        }
        $this->set_error($input_name, 'mimes', [':attribute', ':values'], [$input_name, $mimes]);
    }
    protected function json_validator(string $input_name)
    {
        if (!$this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $value = $this->wp_rest_request->get_param($input_name);
        if (\is_string($value)) {
            \json_decode($value);
            if (\json_last_error() === \JSON_ERROR_NONE) {
                return;
            }
        }
        $this->set_error($input_name, 'json', [':attribute'], [$input_name]);
    }
    protected function accepted_validator(string $input_name, string $items)
    {
        if (!$this->wp_rest_request->has_param($input_name)) {
            return;
        }
        $value = $this->wp_rest_request->get_param($input_name);
        $item_array = \explode(',', $items);
        if (\in_array($value, $item_array)) {
            return;
        }
        $this->set_error($input_name, 'accepted', [':attribute', ':value'], [$input_name, $items]);
    }
    private function set_error(string $input_name, string $rule, array $keys, array $values)
    {
        $message = $this->get_message($rule);
        $message = (string) \str_replace($keys, $values, $message);
        $this->errors[$input_name][] = $message;
    }
    protected function get_message($key)
    {
        $keys = \explode('.', $key);
        $messages = $this->messages();
        foreach ($keys as $key) {
            if (!isset($messages[$key])) {
                return null;
            }
            $messages = $messages[$key];
        }
        return $messages;
    }
    protected function messages()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Validation Language Lines
            |--------------------------------------------------------------------------
            |
            | The following language lines contain the default error messages used by
            | the validator class. Some of these rules have multiple versions such
            | as the size rules. Feel free to tweak each of these messages here.
            |
            */
            'accepted' => 'The :attribute must be one of :value.',
            'accepted_if' => 'The :attribute must be accepted when :other is :value.',
            'active_url' => 'The :attribute is not a valid URL.',
            'after' => 'The :attribute must be a date after :date.',
            'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
            'alpha' => 'The :attribute must only contain letters.',
            'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
            'alpha_num' => 'The :attribute must only contain letters and numbers.',
            'array' => 'The :attribute must be an array.',
            'before' => 'The :attribute must be a date before :date.',
            'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
            'between' => ['numeric' => 'The :attribute must be between :min and :max.', 'file' => 'The :attribute must be between :min and :max kilobytes.', 'string' => 'The :attribute must be between :min and :max characters.', 'array' => 'The :attribute must have between :min and :max items.'],
            'boolean' => 'The :attribute field must be true or false.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'current_password' => 'The password is incorrect.',
            'date' => 'The :attribute is not a valid date.',
            'date_equals' => 'The :attribute must be a date equal to :date.',
            'date_format' => 'The :attribute does not match the format :format.',
            'declined' => 'The :attribute must be declined.',
            'declined_if' => 'The :attribute must be declined when :other is :value.',
            'different' => 'The :attribute and :other must be different.',
            'digits' => 'The :attribute must be :digits digits.',
            'digits_between' => 'The :attribute must be between :min and :max digits.',
            'dimensions' => 'The :attribute has invalid image dimensions.',
            'distinct' => 'The :attribute field has a duplicate value.',
            'email' => 'The :attribute must be a valid email address.',
            'ends_with' => 'The :attribute must end with one of the following: :values.',
            'enum' => 'The selected :attribute is invalid.',
            'exists' => 'The selected :attribute is invalid.',
            'file' => 'The :attribute must be a file.',
            'filled' => 'The :attribute field must have a value.',
            'gt' => ['numeric' => 'The :attribute must be greater than :value.', 'file' => 'The :attribute must be greater than :value kilobytes.', 'string' => 'The :attribute must be greater than :value characters.', 'array' => 'The :attribute must have more than :value items.'],
            'gte' => ['numeric' => 'The :attribute must be greater than or equal to :value.', 'file' => 'The :attribute must be greater than or equal to :value kilobytes.', 'string' => 'The :attribute must be greater than or equal to :value characters.', 'array' => 'The :attribute must have :value items or more.'],
            'image' => 'The :attribute must be an image.',
            'in' => 'The selected :attribute is invalid.',
            'in_array' => 'The :attribute field does not exist in :other.',
            'integer' => 'The :attribute must be an integer.',
            'ip' => 'The :attribute must be a valid IP address.',
            'ipv4' => 'The :attribute must be a valid IPv4 address.',
            'ipv6' => 'The :attribute must be a valid IPv6 address.',
            'json' => 'The :attribute must be a valid JSON string.',
            'lt' => ['numeric' => 'The :attribute must be less than :value.', 'file' => 'The :attribute must be less than :value kilobytes.', 'string' => 'The :attribute must be less than :value characters.', 'array' => 'The :attribute must have less than :value items.'],
            'lte' => ['numeric' => 'The :attribute must be less than or equal to :value.', 'file' => 'The :attribute must be less than or equal to :value kilobytes.', 'string' => 'The :attribute must be less than or equal to :value characters.', 'array' => 'The :attribute must not have more than :value items.'],
            'mac_address' => 'The :attribute must be a valid MAC address.',
            'max' => ['numeric' => 'The :attribute must not be greater than :max.', 'file' => 'The :attribute must not be greater than :max kilobytes.', 'string' => 'The :attribute must not be greater than :max characters.', 'array' => 'The :attribute must not have more than :max items.'],
            'mimes' => 'The :attribute must be a file of type: :values.',
            'min' => ['numeric' => 'The :attribute must be at least :min.', 'file' => 'The :attribute must be at least :min kilobytes.', 'string' => 'The :attribute must be at least :min characters.', 'array' => 'The :attribute must have at least :min items.'],
            'multiple_of' => 'The :attribute must be a multiple of :value.',
            'not_in' => 'The selected :attribute is invalid.',
            'not_regex' => 'The :attribute format is invalid.',
            'numeric' => 'The :attribute must be a number.',
            'password' => 'The password is incorrect.',
            'present' => 'The :attribute field must be present.',
            'prohibited' => 'The :attribute field is prohibited.',
            'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
            'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
            'prohibits' => 'The :attribute field prohibits :other from being present.',
            'regex' => 'The :attribute format is invalid.',
            'required' => 'The :attribute field is required.',
            'required_array_keys' => 'The :attribute field must contain entries for: :values.',
            'required_if' => 'The :attribute field is required when :other is :value.',
            'required_unless' => 'The :attribute field is required unless :other is in :values.',
            'required_with' => 'The :attribute field is required when :values is present.',
            'required_with_all' => 'The :attribute field is required when :values are present.',
            'required_without' => 'The :attribute field is required when :values is not present.',
            'required_without_all' => 'The :attribute field is required when none of :values are present.',
            'same' => 'The :attribute and :other must match.',
            'size' => ['numeric' => 'The :attribute must be :size.', 'file' => 'The :attribute must be :size kilobytes.', 'string' => 'The :attribute must be :size characters.', 'array' => 'The :attribute must contain :size items.'],
            'starts_with' => 'The :attribute must start with one of the following: :values.',
            'string' => 'The :attribute must be a string.',
            'timezone' => 'The :attribute must be a valid timezone.',
            'unique' => 'The :attribute has already been taken.',
            'uploaded' => 'The :attribute failed to upload.',
            'url' => 'The :attribute must be a valid URL.',
            'uuid' => 'The :attribute must be a valid UUID.',
        ];
    }
}
