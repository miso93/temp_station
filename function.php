<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 08.03.2016
 * Time: 22:07
 */
if (session_id() == "") {
    session_start();
}

require_once "config.php";
require __DIR__ . '/vendor/autoload.php';

dibi::connect([
    'driver'   => 'mysql',
    'host'     => Config::get('mysql.host'),
    'username' => Config::get('mysql.user'),
    'password' => Config::get('mysql.pass'),
    'database' => Config::get('mysql.db_name'),
    'charset'  => Config::get('mysql.charset'),
]);

function dd($arr)
{

    ?>
    <pre>
    <?php
    print_r($arr);
    ?>
    </pre>
    <?php
    die();
}


function getQuestionResult($question_id)
{
    $options = Option::fetchByQuestionId($question_id);
    $sum_count = 0;
    $option_items = [];
    foreach($options as $option){
        $sum_count += $option->count;
        $option_items[$option->id] = $option->count;
    }

    return ['sum_count' => $sum_count, 'options' => $option_items];

}