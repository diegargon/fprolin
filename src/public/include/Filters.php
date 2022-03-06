<?php

class Filters
{

    //POST/GET
    static function getInt($val, $size = PHP_INT_MAX)
    {
        if (!isset($_GET[$val])) {
            return false;
        }

        return self::varInt($_GET[$val], $size);
    }

    static function postInt($val, $size = PHP_INT_MAX)
    {
        if (!isset($_POST[$val])) {
            return false;
        }

        return self::varInt($_POST[$val], $size);
    }

    static function varInt($val, $size = PHP_INT_MAX)
    {
        if (!isset($val)) {
            return false;
        }

        if (!is_array($val)) {

            if (!isset($val) || trim($val) > $size || !is_numeric(trim($val))) {
                return false;
            }
            $values = trim($val);
        } else {
            $values = $val;
            if (count($values) <= 0) {
                return false;
            }
            foreach ($values as $key => $val) {
                $values[$key] = trim($val);
                if (!is_numeric($val) || $val > $size) {
                    return false;
                }
                if (!is_numeric($key)) {
                    return false;
                }
            }
        }

        return $values;
    }

    //Simple String words without accents or special characters
    static function getString($val, $size = null)
    {
        if (empty($_GET[$val])) {
            return false;
        }

        return self::varString($_GET[$val], $size);
    }

    static function postString($val, $size = null)
    {
        if (empty($_POST[$val])) {
            return false;
        }

        return self::varString($_POST[$val], $size);
    }

    //TODO FILTER
    static function varString($val, $size = null)
    {
        if (empty($val)) {
            return false;
        }

        if (is_array($val)) {
        } else {
            if ((!empty($size) && (strlen($val) > $size))) {
                return false;
            }
        }

        return $val;
    }
    //USERNAME
    static function postUsername($val, $size = null)
    {
        if (empty($_POST[$val])) {
            return false;
        }

        return self::varUsername($_POST[$val], $size);
    }

    static function getUsername($val, $size = null)
    {
        if (empty($_GET[$val])) {
            return false;
        }

        return self::varUsername($_GET[$val], $size);
    }

    static function varUsername($var, $max_size = null, $min_size = null)
    {

        if ((empty($var)) || (!empty($max_size) && (strlen($var) > $max_size)) || (!empty($min_size) && (strlen($var) < $min_size))) {
            return false;
        }
        //TODO
        //if (!preg_match($user_name_regex, $var)) {
        //return false;
        //}

        return $var;
    }

    // PASSWORD
    static function postPassword($val, $size = null)
    {
        if (empty($_POST[$val])) {
            return false;
        }

        return self::varPassword($_POST[$val], $size);
    }

    static function getPassword($val, $size = null)
    {
        if (empty($_GET[$val])) {
            return false;
        }

        return self::varPassword($_GET[$val], $size);
    }

    static function varPassword($var, $max_size = null, $min_size = null)
    {

        if ((!empty($max_size) && (strlen($var) > $max_size)) || (!empty($min_size) && (strlen($var) < $min_size))
        ) {
            return false;
        }
        //TODO
        //if (!preg_match('/^(\S+)+$/', $var)) {
        //    return false;
        //        }
        return $var;
    }
}
