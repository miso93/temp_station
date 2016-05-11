<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 14.03.2016
 * Time: 23:37
 */

namespace Classes;

use dibi;
use Exception;
use ReflectionClass;

abstract class Model
{

    protected static $table;

    public static function fetchAll($select = ["*"], $andOr = "and", $where = [])
    {
        try {
            $select = implode(",", $select);

            if (count($where) == 1) {
                $result = dibi::query('SELECT ' . $select . ' FROM ' . static::$table . " WHERE " . $where[0][0], $where[0][1]);

            } elseif (count($where) == 2) {
                $result = dibi::query('SELECT ' . $select . ' FROM ' . static::$table . " WHERE %" . $andOr, $where);
            } else {
                $result = dibi::query('SELECT ' . $select . ' FROM ' . static::$table);
            }

            $objects = static::toCollection($result->fetchAll());

            return $objects;
        } catch(\Dibi\Exception $e){
            print $e->getSql();
        }

        return null;
    }

    public static function create($arr = [])
    {
        if (dibi::query('INSERT INTO '.static::$table, $arr)) {
            $id = dibi::getInsertId();
            $rc = new ReflectionClass(static::class);
            $objInstance = $rc->newInstance($id);
            return $objInstance;
        } else {
            return null;
        }
    }

    protected static function toCollection($rows)
    {
        $arr = [];

        foreach($rows as $row){
            $rc = new ReflectionClass(static::class);
            $objInstance = $rc->newInstance($row->id);
            $arr[] = $objInstance;
        }
        return $arr;
    }
    
}