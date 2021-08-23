<?php

    namespace App\Globals;
    use Auth;

    //global functions for different purposes
    class Globals
    {
           //check if user is admin
            public static function check_admin()
            {   
                if(Auth::check())
                {
                    //if user type is 0 or 1, this user is admin
                    if (Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    { return true; }
                    else
                    { return false; }
                }
                else
                { return false; }
            }

            public static function rome_numbers()
            {
                $array = [
                    'I' => 1,
                    'II' => 2,
                    'III' => 3,
                    'IV' => 4,
                    'V' => 5,
                ];

                return $array;
            }
    }

?>