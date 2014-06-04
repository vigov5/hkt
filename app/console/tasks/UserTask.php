<?php

class UserTask extends \Phalcon\CLI\Task
{
  public function loginAction($date = null)
  {
    // if (!DateHelper::isValidDate($date)) {
    //   $date = DateHelper::today();
    // }
    $count = SuccessLogins::find()->count();
    var_dump($count);
  }  
}