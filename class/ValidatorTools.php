<?php
class ValidatorTools {
    
    public static function validateNotEmpty($value, $field_name) {
        if (empty($value) || trim($value) == '') {
            return "$field_name ne peut pas être vide.";
        }
        return null; 
    }

    /**
     * Valide si la valeur n'est pas vide et respecte une longueur donnée.
     *
     * @param string $value La valeur à valider.
     * @param string $fieldName Le nom du champ.
     * @param int $min La longueur minimale acceptée.
     * @param int $max La longueur maximale acceptée.
     * @param string $param Le paramètre spécifique concerné par la validation.
     * @return void
     */
    public static function validateNotEmptyAndLength($value, $fieldName,$param, $min = null, $max = null, $canBeNull = false) {

        $error = self::validateNotEmpty($value, $fieldName);
        if ($error) {
            $response['success'] = false;
            $response['error']['msg'] = $error;
            $response['error']['param'] = $param;
            echo json_encode($response);
            return;
        }

        if($min !== null && $max !== null) {
            $error = self::validateLength($value, $fieldName, $min, $max);
            if ($error) {
                $response['success'] = false;
                $response['error']['msg'] = $error;
                $response['error']['param'] = $param;
                echo json_encode($response);
                return;
            }
        }
       

        return true;
    }

    public static function sanitizePostParam($param, $type = PARAM_STR) {
        if (isset($_POST[$param])) {
            switch ($type) {
                case PARAM_INT:
                    return self::sanitizeToInt($_POST[$param]);
                case PARAM_STR:
                    return self::sanitizeToString($_POST[$param]);
                case PARAM_BOOL:
                    return self::sanitizeToBool($_POST[$param]);
                case PARAM_ARRAY:
                    return self::sanitizeToArray($_POST[$param]);
                case PARAM_FLOAT:
                    return self::sanitizeToFloat($_POST[$param]);
                default:
                    return null;
            }
        }
        return null;
    }

    public function sanitizeGetParam($param, $type) {
        if (isset($_GET[$param])) {
            switch ($type) {
                case PARAM_INT:
                    return self::sanitizeToInt($_GET[$param]);
                case PARAM_STR:
                    return self::sanitizeToString($_GET[$param]);
                case PARAM_BOOL:
                    return self::sanitizeToBool($_GET[$param]);
                case PARAM_ARRAY:
                    return self::sanitizeToArray($_GET[$param]);
                case PARAM_FLOAT:
                    return self::sanitizeToFloat($_GET[$param]);
                default:
                    return null;
            }
        }
        return null;
    }


    /**
    * Filtre une chaîne de caractères pour éviter les injections XSS
    *
    * @param string $data La chaîne à filtrer
    * @return string La chaîne filtrée
    */
    public static function sanitizeToString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    public static function sanitizeToInt($data) {
        return intval($data);
    }

    /**
     * Filtre une valeur pour ne garder que des nombres flottants
     *
     * @param mixed $data La donnée à filtrer
     * @return float|null Renvoie le nombre flottant nettoyé ou null si ce n'est pas un nombre flottant valide.
     */
    public static function sanitizeToFloat($data) {
        if (is_numeric($data)) {
            return floatval($data);
        } else {
            return null;
        }
    }
    

    /**
     * Filtre une valeur pour ne garder que des booléens.
     *
     * @param mixed $data La donnée à filtrer.
     * @return bool|null Renvoie le booléen nettoyé ou null si ce n'est pas un booléen valide.
    */
    public static function sanitizeToBool($data) {
        if (is_bool($data)) {
            return $data;
        } else {
            return null;
        }
    }

    /**
     * Filtre une valeur pour ne garder que des tableaux.
     *
     * @param mixed $data La donnée à filtrer.
     * @return array|null Renvoie le tableau nettoyé ou null si ce n'est pas un tableau valide.
    */
    public static function sanitizeToArray($data) {
        if (is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = self::sanitizeToString($value);
            }
            return $data;
        } else {
            return null;
        }
    }


    public static function validateLength($value, $field_name, $min, $max) {
        if (strlen($value) < $min || strlen($value) > $max) {
            return "$field_name doit contenir entre $min et $max caractères.";
        }
        return null; 
    }



}