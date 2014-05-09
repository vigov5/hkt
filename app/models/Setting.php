<?php




class Setting extends BModel
{

    /**
     *
     * @var integer
     */
    public $maintain;

    /**
     *
     * @var integer
     */
    public $hcoin_rate;

    /**
     *
     * @var integer
     */
    public $charge_rate;

    /**
     *
     * @var integer
     */
    public $updated_by;

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
            'maintain' => 'maintain',
            'hcoin_rate' => 'hcoin_rate',
            'charge_rate' => 'charge_rate',
            'updated_by' => 'updated_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }
    const NOT_MAINTAINED = 0;
    const BEING_MAINTAINED = 1;

    private static $_setting = null;

    public static function getSetting()
    {
        if (!self::$_setting) {
            self::$_setting = Setting::findFirst();
        }

        return self::$_setting;
    }

    public static function getHCoinRate()
    {
        $setting = self::getSetting();
        return $setting->hcoin_rate;
    }

    public static function getChargeRate()
    {
        $setting = self::getSetting();
        return $setting->charge_rate;
    }

    public static function isMaintained()
    {
        $setting = self::getSetting();
        return $setting->maintain != self::NOT_MAINTAINED;
    }

}
