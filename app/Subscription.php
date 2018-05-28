<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**Подписка
     * @param $email
     * @return Subscription
     */
    public static function add($email){
        $sub = new static;
        $sub->email = $email;
        $sub->token = str_random(100);
        $sub->save();

        return $sub;
    }

    /**Удаление подписки
     * @throws \Exception
     */
    public function remove(){
        $this->delete();
    }

}
