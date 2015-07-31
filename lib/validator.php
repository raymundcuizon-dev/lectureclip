<?php

class validator {

    var $opening = '<div style="color: red"><strong>Warning!</strong> ';
    var $clossing = '</div>';

    function walang_laman($name, $field = 'Name') {
        $trimname = trim($name);
        if (empty($trimname)) {
            $error = $this->opening . " " . $field . " Must not be empty!" . $this->clossing;
        }
        return $error;
    }

    function email_validation($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = $this->opening . ' Invalid email address. ' . $this->clossing;
        } elseif (empty($email)) {
            $error = $this->opening . ' Invalid email address. ' . $this->clossing;
        } else {
            $error = NULL;
        }
        return $error;
    }

    function numeric($name, $field = 'Name', $min_length = "", $max_length = "") {
        //$nametrim = trim($name);
        if (strlen($name) >= $min_length) {
            if (strlen($nametrim) <= $max_length) {
                if (!is_numeric($name)) {
                    $error = $this->opening . ' Only numeric are allowed. ' . $this->clossing;
                } else {
                    $error = null;
                }
            } else {
                $error = $this->opening . '' . $field . ' must contain less than ' . $max_length . ' letters.' . $this->clossing;
            }
        } else {
            $error = $this->opening . '' . $field . ' must contain at least ' . $min_length . ' letters.' . $this->clossing;
        }
        return $error;
    }

    function min_max_lenght($name, $field = "", $min_lenght = "", $max_lenght = "") {
        //$name = mysql_real_escape_string($name1);
        if (strlen($name) >= $min_lenght) {
            if (strlen($name) <= $max_lenght) {
                $error = null;
            } else {
                $error = $this->opening . '' . $field . ' must contain less than ' . $max_length . ' letters.' . $this->clossing;
            }
        } else {
            $error = $this->opening . '' . $field . ' must contain at least ' . $min_length . ' letters.' . $this->clossing;
        }
        return $error;
    }

    function pass_match($password_a, $password_b) {
        if ($password_a != $password_b) {
            $error = $this->opening . ' Password did not match! ' . $this->clossing;
        } else {
            $error = null;
        }
        return $error;
    }

}
