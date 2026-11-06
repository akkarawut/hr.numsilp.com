<?php
class Json
{
    /**
     * [TABLE_INFO] Translate label. [TODO/LATER]
     *
     * @author Ball
     * @param STRING $json Single Info
     * @return BOOL Returns TRUE on JSON_ERROR_NONE or FALSE on != JSON_ERROR_NONE.
     */
    public static function isValidJson($json)
    {
        json_decode($json);

        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
    | Group | Json
    | Description | json encode
    | $input | []Array or object data
     */
    public static function encode($input)
    {
        is_object($input) || is_array($input) ? $result = (json_encode($input, JSON_UNESCAPED_SLASHES)) : $result = null;

        return $result;
    }

    /**
    | Group | Json
    | Description | Json encode with out key
    | $input | []Array
     */
    public static function encodeWithoutKey($input)
    {
        is_array($input) || is_object($input) ? $result = json_encode(array_values($input), JSON_UNESCAPED_SLASHES) : $result = null;

        return $result;
    }

    /**
    | Group | Json
    | Description | Json decode
    | $json | Json data
     */
    public static function decode($json)
    {
        if (static::isValidJson($json)) {
            return json_decode($json, true);
        }

        return null;
    }

    /**
    | Group | Json
    | Description | Json decode no key
    | $json | Json data
     */
    public static function decodeNoKey($json)
    {
        if (static::isValidJson($json)) {
            return array_values(json_decode($json, true));
        }

        return null;
    }

    /**
    | Group | Json
    | Description | Get key
    | $json | Json data
    | $key | String
     */
    public static function getKey($json, $key)
    {
        if (!static::isValidJson($json)) {
            return null;
        }
        $data = json_decode($json, true);

        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
    | Group | Json
    | Description | checkKeyExists
    | $json | Json data
    | $key | [] Array or String
     */
    public static function checkKeyExists($json, $key)
    {
        if (!static::isValidJson($json)) {
            return null;
        }

        $data = json_decode($json, true);

        if (is_array($key)) {
            $return = [];

            foreach ($key as $k) {
                $return[$k] = false;
                if (isset($data[$k])) {
                    $return[$k] = true;
                }
            }

            return $return;
        } else if (is_string($key)) {
            if (isset($data[$key])) {
                return true;
            } 
            return false;
        }

        return null;
    }

    /**
    | Group | Json
    | Description | Merge json
    | $json1 | Json data
    | $json2 | Json data
     */
    public static function merge($json1, $json2)
    {
        if (!static::isValidJson($json1) || !static::isValidJson($json2)) {
            return null;
        }
        $mergedArray = array_merge(json_decode($json1, true), json_decode($json2, true));

        return json_encode($mergedArray);
    }

    /**
    | Group | Json
    | Description | Set new value
    | $json1 | Json default data
    | $key | String
    | $value | String
     */
    public static function set($json1, $key, $value)
    {
        if (!static::isValidJson($json1) || !self::checkKeyExists($json1, $key)) {
            return null;
        }

        $data1 = json_decode($json1, true);
        $data1[$key] = $value;

        return json_encode($data1);
    }

    /**
    | Group | Json
    | Description | Unset
    | $json | Json data
    | $key | Key
     */
    public static function unset($json, $key)
    {
        if (!static::isValidJson($json)) {
            return null;
        }

        $array = json_decode($json, true);
        if (isset($array[$key])) {
            unset($array[$key]);
        }
        
        return json_encode($array);
    }

    /**
    | Group | Json
    | Description | Pop lastest json data
    | $json | json data
     */
    public static function pop($json)
    {
        if (!static::isValidJson($json)) {
            return null;
        }

        $array = json_decode($json, true);
        array_pop($array);

        return json_encode($array);
    }

    /**
    | Group | Json
    | Description | Push add json data at lastest 
    | $json1 | Json data
    | $json2 | Json data
     */
    public static function push($json1, $json2)
    {
        if (!static::isValidJson($json1) || !static::isValidJson($json2)) {
            return null;
        }

        $data1 = json_decode($json1, true);
        $data2 = json_decode($json2, true);
        array_push($data1, $data2);

        return json_encode($data1);
    }
}
