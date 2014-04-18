<?php
/**
 * @author Tran Duc Thang
 * @date 4/6/14
 *
 */
use Phalcon\Mvc\Model\Validator;

class ImageValidator extends Validator
{
    public function validate($model)
    {
        $field = $this->getOption('field');
        $value = $model->$field;
        if (!$value) {
            return true;
        }
        $fh = new FileHelper($value);
        if (!$fh->isValidImage()) {
            $this->appendMessage(
                'The image path is not correct',
                $field,
                'ImageValidator'
            );
            return false;
        }
        return true;
    }
}