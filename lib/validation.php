<?php

/*
 * author: Mohamed Lafif Tej
 * E-mail: afif@quiteweb.com
 * website: quiteweb.com
 * Tutorial for this class: http://www.mxtutorial.com/2011/10/php-form-validation/
 * description: This class containing validation functions for fields of a form.
 * release date: 10/30/2011
 * version: 1.0
 * Distributed under GNU General Public License (GPL)
 */

class validation {

    function name_validation($name, $field = 'Name', $min_length = 3, $max_length = 33) {
        // Full Name must contain letters, dashes and spaces only. We have to eliminate dash at the begining of the name.
        $name = trim($name);
        if (strlen($name) >= $min_length) {
            if (strlen($name) <= $max_length) {
                if (preg_match("/^[a-zA-Z][a-zA-Z -]+$/", $name) === 0)
                    $error = $field . ' must contain letters, dashes and spaces only. we don\'t accept dash at the begining of the ' . $field;
                else
                    $error = null;
            } else
                $error = $field . ' must contain less than ' . $max_length . ' letters.';
        } else
            $error = $field . ' must contain at least ' . $min_length . ' letters.';

        return $error;

        /*
          if we want to impose the Full Name to start with upper case letter we have to use that:
          if(preg_match("/^[A-Z][a-zA-Z -]+$/", $name) === 0)
         */
    }

    function email_validation($email, $email_label) {
        //E-mail validation: We use regexp to validate email.
        $email = trim($email);
        if (strlen($email) >= 1) {
            if (preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $email) === 0)
                $error = 'You have to enter a valid ' . $email_label;
            else
                $error = null;
        } else
            $error = 'You have to enter your ' . $email_label;

        return $error;
    }

    function digits_validation($digits, $digits_label) {
        //Value must be digits.
        $digits = trim($digits);
        if (strlen($digits) >= 1) {
            if (preg_match("/^[0-9]+$/", $digits) === 0)
                $error = 'You have to enter a valid ' . $digits_label;
            else
                $error = null;
        } else
            $error = 'You have to enter your ' . $digits_label;

        return $error;
    }

    function username_validation($username, $username_label) {
        //User must be digits and letters.
        $username = trim($username);
        if (strlen($username) >= 1) {
            if (preg_match("/^[0-9a-zA-Z_]{3,}$/", $username) === 0)
                $error = $username_label . ' must be digits and letters and at least 3 characters.';
            else
                $error = null;
        } else
            $error = 'You have to enter your ' . $username_label;

        return $error;
    }

    function date_validation($date, $date_label) {
        //Date must be with this form: YYYY-MM-DD.
        $date = trim($date);
        if (strlen($date) >= 1) {
            if (preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $date) === 0)
                $error = $date_label . ' Date must be with this form: YYYY-MM-DD.';
            else
                $error = null;
        } else
            $error = 'You have to enter your ' . $date_label;

        return $error;
    }

    function address_validation($address, $required, $address_label) {
        //Address must be only letters, numbers or one of the following ". , : /"
        $address = trim($address);
        if (strlen($address_label) == 0)
            $address_label = 'address';

        if (strlen($address) >= 1) {
            if (preg_match("/^[a-zA-Z0-9 _.,:\"\']+$/", $address) === 0)
                $error = 'You have to enter a valid ' . $address_label;
            else
                $error = null;
        }elseif ($required == true)
            $error = 'You have to enter your ' . $address_label;
        else
            $error = null;

        return $error;
    }

    function password_validation($password, $level, $password_label) {
        $password = trim($password);

        switch ($level) {
            //Password must be at least 8 characters
            case 0:
                if (strlen($password) >= 1) {
                    if (preg_match("/^.*(?=.{8,}).*$/", $password) === 0)
                        $error = 'Password must be at least 8 characters.';
                    else
                        $error = null;
                } else
                    $error = 'You have to enter your ' . $password_label;

                break;

            //Password must be at least 8 characters and at least one digit.
            case 1:
                if (strlen($password) >= 1) {
                    if (preg_match("/^.*(?=.{8,})(?=.*[0-9]).*$/", $password) === 0)
                        $error = 'Password must be at least 8 characters and one digit.';
                    else
                        $error = null;
                } else
                    $error = 'You have to enter your ' . $password_label;

                break;

            //Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit(alphanumeric).
            case 2:
                if (strlen($password) >= 1) {
                    if (preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password) === 0)
                        $error = 'Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit.';
                    else
                        $error = null;
                } else
                    $error = 'You have to enter your ' . $password_label;

                break;

            default:
                $error = null;
                break;
        }
        return $error;
    }

}

;
?>