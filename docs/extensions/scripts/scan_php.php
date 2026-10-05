<?php
// Tokenize only: never load or execute inspected plugin code.
$files = json_decode(stream_get_contents(STDIN), true);
$result = [];
foreach ($files as $file) {
    $tokens = token_get_all(file_get_contents($file));
    $out = ['symbols' => [], 'calls' => []];
    $count = count($tokens);
    for ($i = 0; $i < $count; $i++) {
        $t = $tokens[$i];
        if (!is_array($t)) continue;
        if (in_array($t[0], [T_FUNCTION, T_CLASS, T_INTERFACE, T_TRAIT], true)) {
            for ($j = $i + 1; $j < $count; $j++) {
                $n = $tokens[$j];
                if ($n === '(' || $n === '{') break;
                if (is_array($n) && $n[0] === T_STRING) {
                    $out['symbols'][] = ['name' => $n[1], 'line' => $t[2], 'kind' => token_name($t[0])]; break;
                }
            }
        }
        if ($t[0] !== T_STRING) continue;
        $previous = $i - 1;
        while ($previous >= 0 && is_array($tokens[$previous]) && in_array($tokens[$previous][0], [T_WHITESPACE,T_COMMENT,T_DOC_COMMENT],true)) $previous--;
        if ($previous >= 0 && is_array($tokens[$previous]) && $tokens[$previous][0] === T_FUNCTION) continue;
        $name = $t[1];
        $wanted = preg_match('/^(add_action|add_filter|do_action|apply_filters|register_.*|get_.*meta|update_.*meta|add_.*meta|delete_.*meta|get_.*option|update_.*option|add_.*option|delete_.*option|get_directorist.*option|wp_.*schedule.*|wp_clear_scheduled_hook|wp_remote_.*|class_exists|function_exists|defined|is_plugin_active|is_plugin_active_for_network|set_transient|get_transient|delete_transient|dbDelta|create|table|insert|update|delete|save|where|whereIn|join|select)$/i', $name);
        if (!$wanted) continue;
        $j = $i + 1;
        while ($j < $count && is_array($tokens[$j]) && in_array($tokens[$j][0], [T_WHITESPACE,T_COMMENT,T_DOC_COMMENT],true)) $j++;
        if ($j >= $count || $tokens[$j] !== '(') continue;
        $args = []; $arg = ''; $depth = 1;
        for ($j++; $j < $count; $j++) {
            $n = $tokens[$j];
            if (is_array($n) && in_array($n[0], [T_COMMENT,T_DOC_COMMENT],true)) continue;
            $v = is_array($n) ? $n[1] : $n;
            if (!is_array($n) && in_array($v,['(','[','{'],true)) $depth++;
            if (!is_array($n) && in_array($v,[')',']','}'],true)) $depth--;
            if ($depth === 0) { $args[] = trim($arg); break; }
            if ($v === ',' && $depth === 1) { $args[] = trim($arg); $arg = ''; } else $arg .= $v;
        }
        // Only keys/callbacks/guards, never store option values or credentials.
        $limit = in_array($name,['add_action','add_filter','register_rest_route'],true) ? 2 : 1;
        if (preg_match('/_(post|user|term|comment)_meta$/',$name)) $limit = 2;
        $args = array_slice($args, 0, $limit);
        if (in_array($name, ['insert','update','delete','save','create'],true)) {
            $first = $args[0] ?? '';
            preg_match_all('/[\"\']([A-Za-z0-9_-]+)[\"\']\s*=>/', $first, $keys);
            $args = ['write keys: '.implode(', ',array_unique($keys[1])).' (values omitted; inspect source for dynamic keys)'];
        }
        $args = array_map(fn($a) => substr(preg_replace('/\s+/', ' ', $a),0,240),$args);
        $out['calls'][] = ['name'=>$name,'line'=>$t[2],'args'=>$args];
    }
    $result[$file] = $out;
}
echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
