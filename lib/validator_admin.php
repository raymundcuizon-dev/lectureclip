<?php
class validator_admin {
    var $e_o = '<div data-alert class="alert-box alert">';                         
    var $e_c = '</div>';
    
    function required($field_name, $field = " "){
        if(empty($field_name)){
            $error_mess = $this->e_o." This fields is require (".$field.") ". $this->e_c;
        } 
        return $error_mess;
    }
    function email($field_name){
        if(!filter_var($field_name, FILTER_VALIDATE_EMAIL)){
            $error_mess = $this->e_o." Please enter valid email address!". $this->e_c;
        }
        return $error_mess;
    }
    /*function (){
        
    } */
}