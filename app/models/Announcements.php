<?php




class Announcements extends BModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $show_time;

    /**
     *
     * @var string
     */
    public $start_at;

    /**
     *
     * @var string
     */
    public $end_at;

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

    const TYPE_SHOW = 1;
    const TYPE_HIDDEN = 0;

    public static $type_value = [
        self::TYPE_HIDDEN => 'Hidden',
        self::TYPE_SHOW => 'Show',
    ];
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'title' => 'title',
            'content' => 'content',
            'created_by' => 'created_by',
            'type' => 'type',
            'show_time' => 'show_time',
            'start_at' => 'start_at',
            'end_at' => 'end_at',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

    /**
     * @return array Save Attributes name
     */
    public function getSaveAttributesName()
    {
        return ['type', 'show_time', 'start_at', 'end_at', 'title', 'content'];
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('created_by', 'Users', 'id', ['alias' => 'createdBy']);
        $this->hasMany('id', 'UserAnnouncements', 'announcement_id', ['alias' => 'userAnnouncements']);
        $this->hasManyToMany('id', 'UserAnnouncements', 'announcement_id', 'user_id', 'Users', 'id', ['alias' => 'users']);
    }

    /**
     * @param null|string $datetime
     * @return Announcements[]
     */
    public static function getActiveAnnouncements($datetime = null)
    {
        if (!$datetime) {
            $datetime = DateHelper::now();
        }
        $type = self::TYPE_SHOW;
        return Announcements::find([
            'conditions' => "type = $type AND start_at <= '$datetime' AND '$datetime' <=  end_at",
            'order' => 'start_at',
        ]);
    }

    /**
     * Check whether the announcement is visible or hidden
     * @return bool
     */
    public function isVisible()
    {
        return $this->type == self::TYPE_SHOW;
    }

    /**
     * Check whether the announcement is on time to display or not
     */
    public function isOnTime()
    {
        $date = DateHelper::now();
        return $this->start_at <= $date && $this->end_at >= $date;
    }

    /**
     * Check whether the announcement is being displayed or not
     */
    public function isBeingDisplay()
    {
        return $this->isVisible() && $this->isOnTime();
    }
}
