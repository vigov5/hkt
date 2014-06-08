<?php
/**
 * A part of BPhalcon.
 * @author tran.duc.thang
 */

use Phalcon\Mvc\Model\Behavior\Timestampable;
use \Phalcon\Mvc\Model;

/**
 * BModel extends the Model class of Phalcon, with various of features included.
 * When you create a new model, remember to extend from BModel instead of Model to use
 * convenient features of Base Phalcon
 */

class BModel extends Model
{
    private $created_at_field = 'created_at';
    private $updated_at_field = 'updated_at';

    /**
     * Attach timestamp behaviour to all model instance
     */
    public function initialize()
    {
        $time_stampable = [];
        if (property_exists($this, $this->created_at_field)) {
            $time_stampable['beforeCreate'] = [
                'field' => $this->created_at_field,
                'format' => 'Y-m-d H:i:s',
            ];
        }
        if (property_exists($this, $this->updated_at_field)) {
            $time_stampable['beforeUpdate'] = [
                'field' => $this->updated_at_field,
                'format' => 'Y-m-d H:i:s',
            ];
        }
        if ($time_stampable) {
            $this->addBehavior(new Timestampable($time_stampable));
        }
    }

    public function save($data=null, $whiteList=null)
    {
        try {
            return parent::save($data, $whiteList);
        } catch (\Phalcon\Exception $e) {
            $p = new \Phalcon\Utils\PrettyExceptions();
            $p->handle($e);
            exit;
        }
    }

    /**
     * Get all attributes
     * @return array attributes
     */
    public function getAttributesName()
    {
        return $this->getModelsMetaData()->getAttributes($this);
    }

    /**
     * Return the all the save attributes, which can be use in mass assignment or display in create/update form
     * @return array save attributes
     */
    public function getSaveAttributesName()
    {
        return [];
    }

    /**
     * Define label to each attribute. If an attribute's lable is not defined, the label will be generate by BText::snakeToWords function
     * @return the Label of each attribute, which will be displayed in create/edit form
     */
    public static function getAttributeLabels()
    {
        return [];
    }

    /**
     * Return all save attributes and their values
     * @return array The save attributes and values
     */
    public function getSaveAttributes()
    {
        $attributes = $this->getSaveAttributesName();
        $arr = [];
        foreach($attributes as $att) {
            $arr[$att] = $this->$att;
        }
        return $arr;
    }

    /**
     * Set values to save attributes
     * @param array $params Set values to save attributes
     */
    public function setSaveAttributes($params)
    {
        $this->load($params);
    }

    /**
     * Check whether the attribute is save or not
     * @param $att string
     * @return boolean
     */
    public function isSaveAttribute($att)
    {
        $save_attributes = $this->getSaveAttributes();
        return isset($save_attributes[$att]);
    }

    /**
     * Get the label of an attribute. If the label is not defined in the attributeLabels() function,
     * it will be generate be the snakeToWords() function, which will convert a snake_case string to Words
     * with the first letter capitalized.
     * @param $att string
     * @return string
     */
    public static function getAttributeLabel($att)
    {
        $attribute_label = static::getAttributeLabels();
        if (isset($attribute_label[$att])) {
            return $attribute_label[$att];
        }
        return BText::snakeToWords($att, true);
    }

    /**
     * The function to do mass assignment with the inputted attributes. If the attributes is empty,
     * all the save attributes can be changed via mass assignment.
     * @param $params
     * @param array $attributes
     */
    public function load($params, $attributes=[])
    {
        if (!$attributes) {
            $attributes = $this->getSaveAttributesName();
        }
        foreach ($attributes as $att) {
            if (isset($params[$att])) {
                $this->$att = $params[$att];
            }
        }
    }

    /**
     * Check existance
     * @param $id
     * @return bool
     */
    public static function exists($id)
    {
        return self::findFirst($id) ? true : false;
    }
}