<?php

if( ! function_exists('is_serialized') ) :
    function is_serialized($value, &$result = null) {
        // Bit of a give away this one
        if (!is_string($value)) :
            return false;
        endif;

        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;') :
            $result = false;
            return true;
        endif;

        $length	= strlen($value);
        $end	= '';
        switch ($value[0]) :
            case 's':
                if ($value[$length - 2] !== '"') :
                    return false;
                endif;
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':') :
                    return false;
                endif;
                
                switch ($value[2])
                {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0])
                {
                    return false;
                }
            break;
            default:
                return false;
        endswitch;

        if( ($result = @unserialize($value)) === false ) :
            $result = null;

            return false;
        endif;

        return true;
    }
endif;