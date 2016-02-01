<?php

namespace JordanSalazar\DutchDigitValidator;

/**
 * Class: DutchDigitValidator
 *
 * @see \Illuminate\Validation\Validator
 */
class DutchDigitValidator extends \Illuminate\Validation\Validator
{
    /**
     * Validation messages
     *
     * @var array
     */
    private $custom_messages = [
        'iban' => ':attribute moet een geldig IBAN nummer zijn',
        'bsn' => ':attribute moet een geldig BSN nummer zijn',
    ];

    /**
     * Constructor used to set custom messages
     *
     * @param mixed $translator
     * @param mixed $data
     * @param mixed $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages = array(), $customAttributes = array());

        $this->setCustomMessages($this->custom_messages);
    }

    /**
     * Dutch IBAN number validator
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $params
     */
    public function validateIban($attribute, $value, $params)
    {
        $iban_replace_chars = range('A', 'Z');
        $iban_replace_values = [];

        foreach (range(10, 35) as $intvalue) {
            $iban_replace_values[] = (string) $intvalue;
        }

        $testiban = strtoupper($value);
        $testiban = str_replace(' ', '', $testiban);

        if (strlen($testiban) !== 18) {
            return false;
        }

        $testiban = substr($testiban, 4) . substr($testiban, 0, 4);
        $testiban = str_replace(
            $iban_replace_chars,
            $iban_replace_values,
            $testiban
        );

        $head = (int) substr($testiban, 0, 9);
        $tail = substr($testiban, 9);

        $mod = $head % 97;

        foreach (str_split($tail, 7) as $chunk) {
            $digit = (int) ((string) $mod . $chunk);
            $mod = $digit % 97;
        }

        return $mod === 1;

    }

    /**
     * Validate dutch social security number
     *
     * @param mixed $attribute
     * @param mixed $value
     * @param mixed $params
     */
    public function validateBsn($attribute, $value, $params)
    {
        $testbsn = strlen($value) < 9 ? '0' . $value : $value;
        $result = 0;

        $products = range(9, 2);
        $products[] = -1;

        foreach (str_split($testbsn) as $i => $char) {
            $result += (int) $char * $products[$i];
        }

        return $result % 11 === 0;
    }
}
