<?php




class UserAnnouncements extends BModel
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
    public $announcement_id;

    /**
     *
     * @var integer
     */
    public $read_time;

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

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'announcement_id' => 'announcement_id',
            'read_time' => 'read_time',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'user']);
        $this->belongsTo('announcement_id', 'Announcements', 'id', ['alias' => 'announcement']);
    }

    /**
     * Find first record, if not exists then create new one.
     * @param int $user_id
     * @param int $announcement_id
     * @return UserAnnouncements
     */
    public static function findFirstOrCreateNew($user_id, $announcement_id)
    {
        $user_announcement = UserAnnouncements::findFirst("user_id = $user_id AND announcement_id = $announcement_id");
        if (!$user_announcement) {
            $user_announcement = new UserAnnouncements();
            $user_announcement->user_id = $user_id;
            $user_announcement->announcement_id = $announcement_id;
            $user_announcement->read_time = 0;
            $user_announcement->save();
        }

        return $user_announcement;
    }

    /**
     * Get main data in the json format
     * @return string
     */
    public function getJsonData()
    {
        $data = [
            'user_announcement_id' => $this->id,
            'title' => $this->announcement->title,
            'content' => $this->announcement->content,
        ];
        return json_encode($data);
    }

    /**
     * Increase readtime of an user_announcement
     */
    public function increaseReadTime()
    {
        $this->read_time++;
        $this->save();
    }
}
