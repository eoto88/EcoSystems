<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Str {

    public static function format($template, $data) {
        if (preg_match_all("/{{(.*?)}}/", $template, $m)) {
            foreach ($m[1] as $i => $varname) {
                $template = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $template);
            }
        }
        return $template;
    }
}