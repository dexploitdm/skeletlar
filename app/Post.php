<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = ['title','content', 'date', 'description'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    use Sluggable;
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tags(){
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    /*
     *Добавление записи
     */
    public static function add($fields) {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();
        return $post;
    }
    /*
     *Редактирование записи
     */
    public function edit($fields){
        $this->fill($fields);
        $this->save();
    }
    /*
     *Удаление
     */
    public function remove(){
        //Удалить картинку поста, а потом все остальное
        $this->removeImage();
        $this->delete();
    }
    //Удаление Изображения
    public function removeImage(){
        if($this->image != null) {
            Storage::delete('uploads/' . $this->image);
        }
    }

    /**
     * Загрузка картинки
     * @param $image
     */
    public function uploadImage($image){
        if($image == null) {return; }

        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    /**
     * Вывод картинки
     * @return string
     */
    public function getImage(){
        //Проверим наличие
        if($this->image == null){
            return 'img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    /**
     * Выбор категории
     * @param $id
     */
    public function setCategory($id){
        if($id == null) { return; }
        $this->category_id = $id;
        $this->save();
    }

    /**
     * Выбор Тегов
     * @param $ids
     */
    public function setTags($ids){
        if($ids == null) { return; }
        $this->tags()->sync($ids);
    }

    /**
     * Статус
     */
    public function setDraft() {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }
    public function setPublic() {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }
    public function toggleStatus($value){
        if($value == null){
            return $this->setDraft();
        }
        else {
            return $this->setPublic();
        }
    }

    /**
     * Рекомендованные
     * @return SetFeatured()
     */
    public function setFeatured() {
        $this->is_featured = 1;
        $this->save();
    }
    public function setStandart() {
        $this->is_featured = 0;
        $this->save();
    }
    public function toggleFeatured($value){
        if($value == null){
            return $this->setStandart();
        }
        else {
            return $this->setFeatured();
        }
    }

    /**
     * Форматирование даты
     * @param $value
     */
    public function setDateAttribute($value){
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value){
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }
    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    /**Возвращает Название категории
     * @return string
     */
    public function getCategoryTitle(){
        if($this->category != null){
            return $this->category->title;
        }
        return 'Нет категории';
    }

    /**Возвращает навзание тегов
     * @return string
     */
    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty())
            ?   implode(', ', $this->tags->pluck('title')->all())
            : 'Нет тегов';
    }

    /**Получение ID категории для вывода в Edit
     * @return null
     */
    public function getCategoryID()
    {
        return $this->category != null ? $this->category->id : null;
    }

    /**Предыдущие записи
     * @return mixed
     */
    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); //ID
        return self::find($postID);
    }

    /**Следующие записи
     * @return mixed
     */
    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    /**Похожие записи
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public function related()
    {
        return self::all()->except($this->id);
    }

    /**
     * @return bool
     */
    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    /**Получение популярных записей
     * @return mixed
     */
    public static function getPopularPosts()
    {
        return self::orderBy('views','desc')->take(3)->get();
    }

    /**Получение Коментарий
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }





}
