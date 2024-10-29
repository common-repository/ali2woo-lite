<?php

/**
 * Description of ResultBuilder
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ResultBuilder {

    public static function build($state, $message = "", $object = false, $json=false) {
        $result = array('state' => $state);
        if ($message) {
            $result['message'] = $message;
        }
        if ($object) {
            if (is_array($object)) {
                foreach ($object as $key => $value) {
                    if ($key != 'state' && $key != 'message') {
                        $result[$key] = $value;
                    }
                }
            } else {
                $result['object'] = $object;
            }
        }
        return $json?wp_json_encode($result):$result;
    }

    public static function buildOk($object = false, $json=false) {
        return self::build("ok", false, $object, $json);
    }

    public static function buildError($message, $object = false, $json=false) {
        return self::build("error", $message, $object, $json);
    }

    public static function buildWarn($message, $object = false, $json=false) {
        return self::build("warn", $message, $object, $json);
    }

}