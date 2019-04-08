<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

define('ELEMENT_CONTENT_ONLY', true);
define('ELEMENT_PRESERVE_TAGS', false);

function value_in($element_name, $xml, $content_only = true) {
    if ($xml == false) {
        return false;
    }
    $found = preg_match('#<'.$element_name.'(?:\s+[^>]+)?>(.*?)'.
            '</'.$element_name.'>#s', $xml, $matches);
    if ($found != false) {
        if ($content_only) {
            return $matches[1];  //ignore the enclosing tags
        } else {
            return $matches[0];  //return the full pattern match
        }
    }
    // No match found: return false.
    return false;
}

function element_set($element_name, $xml, $content_only = false) {
    if ($xml == false) {
        return false;
    }
    $found = preg_match_all('#<'.$element_name.'(?:\s+[^>]+)?>' .
            '(.*?)</'.$element_name.'>#s',
            $xml, $matches, PREG_PATTERN_ORDER);
    if ($found != false) {
        if ($content_only) {
            return $matches[1];  //ignore the enlosing tags
        } else {
            return $matches[0];  //return the full pattern match
        }
    }
    // No match found: return false.
    return false;
}

function element_attributes($element_name, $xml) {
    if ($xml == false) {
        return false;
    }
    // Grab the string of attributes inside an element tag.
    $found = preg_match('#<'.$element_name.
            '\s+([^>]+(?:"|\'))\s?/?>#',
            $xml, $matches);
    if ($found == 1) {
        $attribute_array = array();
        $attribute_string = $matches[1];
        // Match attribute-name attribute-value pairs.
        $found = preg_match_all(
                '#([^\s=]+)\s*=\s*(\'[^<\']*\'|"[^<"]*")#',
                $attribute_string, $matches, PREG_SET_ORDER);
        if ($found != 0) {
            // Create an associative array that matches attribute
            // names to attribute values.
            foreach ($matches as $attribute) {
                $attribute_array[$attribute[1]] =
                        substr($attribute[2], 1, -1);
            }
            return $attribute_array;
        }
    }
    // Attributes either weren't found, or couldn't be extracted
    // by the regular expression.
    return false;
}

        function php_multisort($data,$keys) {
                // List As Columns
                foreach ($data as $key => $row) {
                        foreach ($keys as $k){
                                $cols[$k['key']][$key] = $row[$k['key']];
                        }
                }
                // List original keys
                $idkeys=array_keys($data);
                // Sort Expression
                $i=0;  $sort="";
                foreach ($keys as $k){
                        if($i>0){$sort.=',';}
                        $sort.='$cols[\''.$k['key'].'\']';
                        //$sort.='$cols['.$k['key'].']';
                        if($k['sort']){$sort.=',SORT_'.strtoupper($k['sort']);}
                        if($k['type']){$sort.=',SORT_'.strtoupper($k['type']);}
                        $i++;
                }
                $sort.=',$idkeys';
                // Sort Funct
                $sort='array_multisort('.$sort.');';
                eval($sort);
                // Rebuild Full Array
                foreach($idkeys as $idkey){
                        $result[$idkey]=$data[$idkey];
                }
                return $result;
        }

        function make_safe($string) {
                $string = preg_replace('#<!\[CDATA\[.*?\]\]>#s', '', $string);
                $string = strip_tags($string);
                // The next line requires PHP 5, unfortunately.
                //$string = htmlentities($string, ENT_NOQUOTES, 'UTF-8', false);
                // Instead, use this set of replacements in PHP 4.
                $string = str_replace('<', '&lt;', $string);
                $string = str_replace('>', '&gt;', $string);
                $string = str_replace('(', '&#40;', $string);
                $string = str_replace(')', '&#41;', $string);
                return $string;
        }

?>
