<?php

class GeneratePassword {
    private $validTypes = array();
    private $length = 8;
    private $lengthAndFields = array();
    private $defaultField = 'lowercase';
    private $amount = 2;
    private $remainder = 0;

    public function __construct($length = 8, array $options = []) {
        $this->length = (int)$length;
        $this->mapFieldsAndLengths();
        $this->setValidTypes($options);
        if($this->isAnyLengthSet($options)) {
            $this->setMaxLengths($options);
        } else {
            $this->calculateAmountAndRemainder();
            $this->setDefaultMaxLengths($this->length);
        }
    }

    private function calculateAmountAndRemainder() {
        $this->amount = floor($this->length / 4);
        $this->remainder = $this->length - $this->amount * 4;
    }

    private function isAnyLengthSet(array $options) {
        foreach($this->lengthAndFields as $lengthAndField) {
            if(isset($options[$lengthAndField['length']]) && is_numeric($options[$lengthAndField['length']])) {
                return true;
            }
        }

        return false;
    }

    private function displayErrorAndExit() {
        throw new Exception('Incorrect lengths provided');
        exit;
    }

    private function checkOptionsCorrectAmount($options) {
        $calculatedLength = 0;
        foreach($this->lengthAndFields as $lengthAndField) {
            if(isset($options[$lengthAndField['length']]) && is_numeric($options[$lengthAndField['length']])) {
                $calculatedLength += (int)$options[$lengthAndField['length']];
            }
        }

        return $calculatedLength == $this->length;
    }

    private function mapFieldsAndLengths() {
        $this->lengthAndFields = array(
            array('field' => 'lowercase', 'length' => 'lowercaseLength', 'value' => 0),
            array('field' => 'uppercase', 'length' => 'uppercaseLength', 'value' => 0),
            array('field' => 'numeric', 'length' => 'numericLength', 'value' => 0),
            array('field' => 'specialChars', 'length' => 'specialCharsLength', 'value' => 0),
            array('field' => 'otherChars', 'length' => 'otherCharsLength', 'value' => 0));
    }

    private function setMaxLengths(array $options = []) {
        if($this->checkOptionsCorrectAmount($options) === false) {
            $this->displayErrorAndExit();
        }
        foreach($this->lengthAndFields as $i => $lengthAndField) {
            if(isset($options[$lengthAndField['length']]) && is_numeric($options[$lengthAndField['length']])) {
                $this->lengthAndFields[$i]['value'] = $this->getLength($options, $lengthAndField['length']);
            }
        }
    }

    private function getLength(array $options, $field) {
        if(isset($options[$field])) {
            return (int)$options[$field];
        }

        return 0;
    }

    private function setDefaultMaxLengths() {
        foreach($this->lengthAndFields as $i => $lengthAndField) {
            if($lengthAndField['field'] != 'otherChars') {
                if($lengthAndField['field'] == $this->defaultField) {
                    $this->lengthAndFields[$i]['value'] = $this->amount + $this->remainder;
                } else {
                    $this->lengthAndFields[$i]['value'] = $this->amount;
                }
            }
        }
    }

    private function getCharsFromRange($start, $end) {
        $chars = "";
        for($a = $start; $a <= $end; $a++) {
            $chars .= chr($a);
        }

        return $chars;
    }

    private function setValidTypes(array $options = []) {
        $this->validTypes['lowercase'] = $this->getCharsFromRange(97, 122);
        $this->validTypes['uppercase'] = $this->getCharsFromRange(65, 90);
        $this->validTypes['numeric'] = $this->getCharsFromRange(48, 57);
        $this->validTypes['specialChars'] = $this->getSpecialChars();
        $this->validTypes['otherChars'] = $this->getOtherChars($options);
    }

    private function getSpecialChars() {
        //Special characters are being picked from the following ascii ranges
        //33 - 47 && 58 - 64
        $specialChars = $this->getCharsFromRange(33, 47);
        $specialChars .= $this->getCharsFromRange(58, 64);

        return $specialChars;
    }

    private function getOtherChars(array $options) {
        if(isset($options['otherChars']) && empty($options['otherChars']) === false) {
            return $options['otherChars'];
        }

        return "";
    }

    public function generatePassword() {
        $password = "";
        foreach($this->lengthAndFields as $lengthAndField) {
            for($a = 0; $a < $lengthAndField['value']; $a++) {
                //$password .= $this->validTypes[$lengthAndField['field']][mt_rand(0, mb_strlen($this->validTypes[$lengthAndField['field']]) - 1)];
                $password .= mb_substr(
                    $this->validTypes[$lengthAndField['field']],
                    mt_rand(0, mb_strlen($this->validTypes[$lengthAndField['field']]) - 1),
                    1);
            }
        }

        return $this->mb_str_shuffle($password);
    }

    private function mb_str_shuffle($multibyte_string) {
        $characters_array = mb_str_split($multibyte_string);
        shuffle($characters_array);
        return implode('', $characters_array);
    }
}
