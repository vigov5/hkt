<?php




class Faqs extends BModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $lang;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var integer
     */
    public $priority;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    const LANG_EN = 0;
    const LANG_VN = 1;
    const LANG_JP = 2;

    public static $lang_value = [
        self::LANG_EN => 'English',
        self::LANG_VN => 'Tiếng Việt',
        self::LANG_JP => '日本語',
    ];

    /**
     * @return array Save Attributes
     */
    public function getSaveAttributesName()
    {
        return ['question', 'answer', 'lang', 'priority'];
    }

    /**
     * @return array Attribute Labels
     */
    public static function getAttributeLabels()
    {
        return [
            'lang' => 'Language',
        ];
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'lang' => 'lang',
            'created_by' => 'created_by',
            'question' => 'question',
            'answer' => 'answer',
            'priority' => 'priority',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

}
