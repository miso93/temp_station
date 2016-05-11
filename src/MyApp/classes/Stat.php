<?php
use Carbon\Carbon;
use Classes\Model;

/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 25.04.2016
 * Time: 11:28
 */

class Stat extends Model{

    protected static $table = "stats";

    public $id;
    public $date;
    public $temp;
    public $humi;
    public $dewp;
    public $day_pivot;

    public function __construct($id)
    {
        $result = dibi::query('SELECT * FROM ' . self::$table . ' WHERE id = ?', $id);
        $fetch = $result->fetch();
        if ($fetch) {
            $this->id = $fetch->id;
            $this->date = Carbon::parse($fetch->date);
            $this->temp = $fetch->temp;
            $this->humi = $fetch->humi;
            $this->dewp = $fetch->dewp;
            $this->day_pivot = Carbon::parse($fetch->date)->format('ymd');
        }
    }

}