<?php
/**
 * @author Tran Duc Thang
 *
 */
use Phalcon\Mvc\Model\Validator;

class UsernameValidator extends Validator
{
    public function validate($model)
    {
        $field = $this->getOption('field');
        $value = $model->$field;
        if (!$value) {
            return true;
        }
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,30}$/', $value)) {
            $this->appendMessage(
                'The username is not valid. It must contain only alphabetical characters and numbers with the length beetween 5 and 30',
                $field,
                'UsernameValidator'
            );
            return false;
        }
        return true;
    }
}