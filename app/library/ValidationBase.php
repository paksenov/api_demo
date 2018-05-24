<?php
/**
 * Created by PhpStorm.
 * User: aks
 * Date: 29.06.16
 * Time: 11:13
 */

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\CreditCard;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Email as EmailValidation;
use Phalcon\Validation\Validator\ExclusionIn;
use Phalcon\Validation\Validator\File;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Url;


class ValidationBase extends Form {

    protected $object;
    protected $options;
    protected $fields;

    public function initialize($object = null, $options = [])
    {
        $this->object = $object;
        $this->options = $options;

        if (!isset($this->options['fields']))
        {
            throw new \Exception('Not set fields for validation');
        }

        foreach($this->options['fields'] as $options) {

            $field = $options['name'];

            $element = new Text($field, []);

            if (!isset($options['not_required']) || $options['not_required'] == false) {

                $element->addValidator(new PresenceOf(
                    array(
                        'message' => 'The :field is required',
                        'cancelOnFail' => true,
                        'allowEmpty' => isset($options['allowEmpty']) ? true : false,
                    )
                ));
            }

            if (count($options['validators'])) {
                foreach($options['validators'] as $validator => $params) {
                    $validator = "Phalcon\\Validation\\Validator\\".$validator;
                    $params['allowEmpty'] = isset($options['allowEmpty']) ? true : false;
                    $element->addValidator(new $validator($params));
                }
            }

            if (isset($options['filters'])) {
                $element->setFilters($options['filters']);
            }

            $this->add($element);

        }
    }

}