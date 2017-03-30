<?php
/**
 * Return array as text in js class format
 * @author shakibonline <shakiba_9@yahoo.com>
 */

/**
 * @param $array
 * @param string $title
 * @param bool $head
 * @return string
 */
function rt($array, $title = null, $head = true)
{
    $ref = 'ref';
    $text = '';
    if ($head) {
        $text = "[$ref]";
        $text .= "\n";
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if ($title != null) {
                $key = $title.'.'.$key;
            }
            $text .= rt($value , $key, false);
        } else {
            if (is_bool($value)) {
                $value = ($value) ? 'true' : 'false';
            }
            if ($title != '')
                $text .= $ref . '.'.$title.'.'.$key.'= '.$value.PHP_EOL;
            else
                $text .= $ref . '.'.$key.'= '.$value.PHP_EOL;

        }
    }
    return $text;
}
