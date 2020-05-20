<?php


namespace core\classes;


class Filter {

    public static function mail($var, $errorMsg = false) {
        $result = filter_var($var, FILTER_VALIDATE_EMAIL);
        if (!$result) {
            if ($errorMsg) {
                Notice::addDanger($errorMsg);
            }
        }

        return $result;
    }

    public static function string($var, $errorMsg = false) {
        $result = filter_var($var, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!$result) {
            if ($errorMsg) {
                Notice::addDanger($errorMsg);
            }   
        }

        return $result;
    }

    public static function integer($var, $errorMsg = false) {
        $result = filter_var($var, FILTER_SANITIZE_NUMBER_INT);
        if (!$result) {
            if ($errorMsg) {
                Notice::addDanger($errorMsg);
            }
        }

        return $result;
    }
}