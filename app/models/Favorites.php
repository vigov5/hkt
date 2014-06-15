<?php




class Favorites extends BModel
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $target_id;

    /**
     *
     * @var integer
     */
    public $target_type;

    /**
     *
     * @var integer
     */
    public $views;

    /**
     *
     * @var integer
     */
    public $receive_notification;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'target_id' => 'target_id',
            'target_type' => 'target_type',
            'views' => 'views',
            'receive_notification' => 'receive_notification',
            'created_at' => 'created_at'
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'user']);
    }

    const TYPE_SHOP = 1;
    const TYPE_ITEM = 2;
    const TYPE_USER = 3;
    const TYPE_UNKNOWN = 0;

    const NOTIFICATION_NO = 0;
    const NOTIFICATION_YES = 1;

    /**
     * @param Users|Items|Shops $target
     * @return int Target Type
     */
    public static function getTargetType($target)
    {
        if ($target instanceof Shops) {
            return self::TYPE_SHOP;
        }
        if ($target instanceof Users) {
            return self::TYPE_USER;
        }
        if ($target instanceof Items) {
            return self::TYPE_ITEM;
        }
        return self::TYPE_UNKNOWN;
    }

    /**
     * Check a target type valid or not
     * @param string $target_type
     * @return bool
     */
    public static function validateTargetType($target_type)
    {
        return ($target_type == self::TYPE_SHOP
            || $target_type == self::TYPE_ITEM
            || $target_type == self::TYPE_USER
        );
    }

    /**
     * Get target class from target type
     * @param int $target_type
     * @return null|string Target Class
     */
    public static function getTargetClass($target_type)
    {
        switch ($target_type) {
            case self::TYPE_USER:
                return 'Users';
            case self::TYPE_ITEM:
                return 'Items';
            case self::TYPE_SHOP:
                return 'Shops';
            default:
                return null;
        }
    }

    /**
     * Increase view
     * @return bool
     */
    public function increaseViews()
    {
        $this->views++;
        return $this->save();
    }

    /**
     * @return Users|Items|Shops Target
     */
    public function getTarget()
    {
        $target_class = self::getTargetClass($this->target_type);
        return $target_class::findFirst($this->target_id);
    }

    /**
     * @return string Target View Link
     */
    public function getViewLink()
    {
        $target = $this->getTarget();
        return $target->getViewLink();
    }

    /**
     * Change Receive Notification Status
     * @return bool
     */
    public function changeNotification()
    {
        if ($this->receive_notification == self::NOTIFICATION_YES) {
            $this->receive_notification = self::NOTIFICATION_NO;
        } else {
            $this->receive_notification = self::NOTIFICATION_YES;
        }
        return $this->save();
    }

    /**
     * Get list users who want to receive notification from a target
     * @param Users|Items|Shops $target
     * @return Favorites[]
     */
    public static function getSubscribers($target)
    {
        $target_type = self::getTargetType($target);
        return Favorites::find("target_id=$target->id AND target_type= $target_type AND receive_notification=" . self::NOTIFICATION_YES);
    }
}
