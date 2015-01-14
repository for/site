<?php
//Crypt class is used in other functions
class Crypt
{
    //Returns an encrypted string
    static public function encrypt($pure_string) {


        return ($pure_string * 17);
    }

    //Returns decrypted original string
    static public function decrypt($encrypted_string) {


        return ($encrypted_string / 17);
    }
}