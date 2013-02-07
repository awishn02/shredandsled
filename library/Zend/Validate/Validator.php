<?php
/* TODO: find a better design for this class.  it only saves keystrokes, not
 * resources.
 */

class Zend_Validate_Validator
{
    private $validator_names = array("Alnum", "Alpha", "Ccnum",
                                    "CreditCard", "Date", "Digits", "EmailAddress", "Exception", "Float", "GreaterThan",
                                    "Hex", "Hostname", "Iban", "Identical", "Int", "Ip", "Isbn",
                                    "LessThan", "NotEmpty", "StringLength");
    const VAL_PREFIX = "Zend_Validate_";
    public $validators = array();

    public function initialize()
    {
        /*foreach($this->validator_names as $vname)
        {
            $full_vname = Zend_Validate_Validator::VAL_PREFIX . $vname;
            try {
                require_once 'Zend/Validate/' . $vname . '.php';
                $validator = new $full_vname();
            } catch (Exception $ex)
            {
                continue;
            }

            $this->validators[$vname] = $validator;
        }*/
    }

    public function validate_data($data)
    {
        $is_valids = array();
        foreach ($data as $input_name => $idata)
        {
            $value = $idata['value'];
        	
            //kind of a hack.. but, we just want to validate these if something was input.
            //if they were empty, we can just save null to the db, so we can skip validating
            if ($input_name == "move_in_earliest" || $input_name == "move_in_latest")
        	{
        		if (!$value)
        		{
        			$is_valids[$input_name] = true;
        			continue;
        		}
        	}
            
        	$validations = $idata['data-val'];
            if (is_array($validations))
            {
                foreach ($validations as $val)
                {
                    if (is_array($val))
                    {
                        $vname = $val['vname'];
                        $params = $val['params'];
                        require_once 'Zend/Validate/' . $vname . '.php';
                        $validatorname = Zend_Validate_Validator::VAL_PREFIX . $vname;
                        if (isset($params['options'])) {
                            $t_validator = new $validatorname($params['options']);
                        } else {
                            $t_validator = new $validatorname($params);
                        }
                    } else {
                        require_once 'Zend/Validate/' . $val . '.php';
                        $validatorname = Zend_Validate_Validator::VAL_PREFIX . $val;
                        $t_validator = new $validatorname();
                    }

                    if (!$t_validator->isValid($value))
                    {
                        $is_valids[$input_name] = $this->__get_error_message($t_validator);
                        break;
                    } else {
                        $is_valids[$input_name] = true;
                        continue;
                    }

                }
            }
            else
            {
                require_once 'Zend/Validate/' . $validations . '.php';
                $validatorname = Zend_Validate_Validator::VAL_PREFIX . $validations;
                $t_validator = new $validatorname();

                if (!$t_validator->isValid($value))
                {
                    $is_valids[$input_name] = $this->__get_error_message($t_validator);
                } else {
                    $is_valids[$input_name] = true;
                }
            }
        }
        $this->result = $is_valids;
        return $is_valids;
    }

    public function allValid()
    {
        $result = true;
        $all_valid = array_reduce($this->result, 'get_result', $result);
        return $all_valid;
    }

    private function __get_error_message($validator)
    {
        $messages = array_values($validator->getMessages());
        $e_msg = $messages[0];
        
        //override message for Date
    	$pos = strpos($e_msg,"does not fit the date format");
        if ($pos !== false)
        {
        	return "Complete date required.";
        }
        return $e_msg;
    }

}

function get_result($result, $item)
{
    if (is_array($item))
        $this_result = current(array_values($item));
    else
        $this_result = $item === true;
    $result = $result && $this_result;
    return $result;
}

?>