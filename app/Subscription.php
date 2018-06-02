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
        $sub->save();

        return $sub;
    }
    public function generateToken() {
        $this->token = str_random(100);
        $this->save();
    }

    /**Удаление подписки
     * @throws \Exception
     */
    public function remove(){
        $this->delete();
    }

}
