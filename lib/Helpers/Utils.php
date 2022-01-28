<?

namespace lib\Helppers;

class Utils {
    public static function generate_password ($type, $lenght) 
    {   
        switch ($type) {
            case 1:
                $input = '123456790';
                break;

            case 2:
                $input =  $input = '0123456790qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
                break;

            case 3:
                $input = '0123456790qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!@#$%';
                break;
            case 4:
                $input = '0123456790qwertyuiopasdfghjklzxcvbnm';
                break;

            default: {
                $input = null;
            }
        }

        return $input ? substr(str_shuffle($input), 0, $lenght): null;
    }
}