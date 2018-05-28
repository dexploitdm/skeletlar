<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable
{
    const IS_BANNED = 1;
    const IS_ACTIVE = 0;

    protected $fillable = ['name', 'email'];

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**Добавление пользователя
     * @param $fields
     * @return User
     */
    public static function add($fields){
        $user = new static;
        $user->fill($fields);
        $user->save();
        return $user;
    }

    /**Редактирование пользователя
     * @param $fields
     */
    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }

    /**Кодировка пароля
     * @param $password
     */
    public function generatePassword($password){
        if($password != null){
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    /**Загрузка аватара
     * @param $image
     */
    public function uploadAvatar($image){
        if($image == null) {return; }
        $this->removeAvatar();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar= $filename;
        $this->save();
    }

    /**Удаление аватара
     * @throws \Exception
     */
    public function remove() {
        $this->removeAvatar();
        $this->delete();
    }
    public function removeAvatar(){
        if($this->avatar != null) {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    /**Вывод аватара
     * @return string
     */
    public function getImage(){
        if($this->avatar == null){
            return '/img/no-user-image.png';
        }
        return '/uploads/' . $this->avatar;
    }

    /**
     * Права Админа
     */
    public function makeAdmin(){
        $this->is_admin = 1;
        $this->save();
    }

    /**
     * Права пользователя
     */
    public function makeNormal(){
        $this->is_admin = 0;
        $this->save();
    }

    /**Переключатель прав
     * @param $value
     */
    public function toggleAdmin($value){
        if($value == null){
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }

    /**
     * Система блокировки пользователя
     */
    public function ban(){
        $this->status = User::IS_BANNED;
        $this->save();
    }
    public function unban(){
        $this->status = User::IS_ACTIVE;
        $this->save();
    }
    public function toggleBan($value){
        if($value == null){
            return $this->unban();
        }
        return $this->ban();
    }










}
