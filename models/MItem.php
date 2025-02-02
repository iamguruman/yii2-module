<?php

namespace app\modules\{_MODULE_ID_}\models;

use Yii;
use app\modules\users\models\User;
use yii\helpers\Html;
use app\modules\fileserver\components\FileServerGetLink;

/**
 * This is the model class for table "{_OBJECT_TABLE_NAME_}{ITEM_NAME_LOWCASE}".
 *
 * @property int $id
 * @property string $created_at Добавлено когда
 *
 * @property int $created_by Добавлено кем
 * @property User $createdBy
 *
 * @property string $updated_at Изменено когда
 *
 * @property int $updated_by Изменено кем
 * @property User $updatedBy
 *
 * @property string $markdel_at Удалено когда
 *
 * @property int $markdel_by Удалено кем
 * @property User $markdelBy
 *
 * @property string $name Наименование
 *
 * @property-read $urlTo ссылка на объект
 * @property-read $urlToBlank ссылка на объект, которая открывается в новом окне
 *
 * @property-read {_OBJECT_MODEL_NAME_}Upload[] $uploads - вложения, см метод getUploads
 *
 * @property-read string $urlIconToFiles - ссылка-иконка на вложения
 *
 * @property string ${_ITEM_TABLE_PARENT_ID_FIELD_}
 * @property-read {_OBJECT_MODEL_NAME_} $parentObject
 * 
 * @property $viewIndex
 * @property $viewCreate
 * @property $viewView
 * @property $viewUpdate
 *
 */
class {_OBJECT_ITEM_MODEL_NAME_} extends \yii\db\ActiveRecord
{
    public static $viewIndex = "@app/modules/{_MODULE_ID_}/views/default/index.php";
    public static $viewCreate = "@app/modules/{_MODULE_ID_}/views/default/create.php";
    public static $viewView = "@app/modules/{_MODULE_ID_}/views/default/view.php";
    public static $viewUpdate = "@app/modules/{_MODULE_ID_}/views/default/update.php";
                       
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{_OBJECT_TABLE_NAME_}__{ITEM_NAME_LOWCASE}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['{_ITEM_TABLE_PARENT_ID_FIELD_}'], 'integer'],
            [['{_ITEM_TABLE_PARENT_ID_FIELD_}'], 'exist', 'skipOnError' => true, 'targetClass' => {_OBJECT_MODEL_NAME_}::className(), 'targetAttribute' => ['{_ITEM_TABLE_PARENT_ID_FIELD_}' => 'id']],

            [['name'], 'string', 'max' => 255],

            [['created_at', 'updated_at', 'markdel_at'], 'safe'],

            [['created_by', 'updated_by', 'markdel_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['markdel_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['markdel_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
         
            'urlTo' => 'Документ',
            'urlToBlank' => 'Документ',

            'created_at' => 'Добавлено когда',

            'created_by' => 'Добавлено кем',
            'createdBy.lastNameWithInitials' => 'Добавлено кем',

            'updated_at' => 'Изменено когда',

            'updated_by' => 'Изменено кем',
            'updatedBy.lastNameWithInitials' => 'Изменено кем',

            'markdel_at' => 'Удалено когда',

            'markdel_by' => 'Удалено кем',
            'markdelBy.lastNameWithInitials' => 'Удалено кем',

            'name' => 'Наименование',
        ];
    }

    /**
     * ссылка на просмотр объекта
     * @return array
     */
    public function getUrlView($return = 'string'){
        
        $arr = [];
        
        $arr [] = ['/{_MODULE_ID_}/{ITEM_NAME_LOWCASE}/view', 'id' => $this->id];
        
        if($return == 'array'){
            return $arr;
        } elseif ($return == 'string'){
            return "/{_MODULE_ID_}/{ITEM_NAME_LOWCASE}/view?id={$this->id}";
        } else {
            return $arr;
        }
    }

    /**
     * ссылка к списку объектов
     * @return array
     */
    public function getUrlIndex(){
        return ['/{_MODULE_ID_}/{ITEM_NAME_LOWCASE}/index'];
    }

    public function getUrlTo($target = null){
        return Html::a(str_replace("&nbsp;", " ", $this->getTitle()),
            $this->getUrlView(),
            ['target' => $target, 'data-pjax' => 0]);
    }

    /**
     * получить заголовок объекта
     * @return string
     */
    public function getTitle(){

        $ret = [];
        
        if($this->name)
            $ret [] = $this->name;
        
        if(count($ret) == 0){
            $ret [] = "Без названия";
        }
        
        //$ret [] = $this->getUrlIconToFiles();
        
        return implode(' ', $ret);
    
    }
    
    public function getUrlIconToFiles(){

        $count = count($this->uploads);

        $ret = [];

        if($count == 0){
            return;
        } elseif($count == 1){
            $ret [] = Html::a("<i class='fas fa-paperclip'>{$count}</i>",
                FileServerGetLink::http($this->uploads[0]->md5, $this->uploads[0]->ext), ['data-pjax' => 0]);
        } elseif ($count > 1){
            $ret [] = Html::a("<i class='fas fa-paperclip'>{$count}</i>",
                [$this->getUrlView('string'), 'tab' => 'files'], ['data-pjax' => 0]);
        } 

        return implode($ret);
    }

    public function getUrlToBlank(){
        return $this->getUrlTo('_blank');
    }

    public function getBreadcrumbs(){
        return [
            'label' => $this->getTitle(),
            'url' => $this->getUrlView()
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkdelBy()
    {
        return $this->hasOne(User::className(), ['id' => 'markdel_by']);
    }

    /**
     * получаем файлы вложения
     * @return \yii\db\ActiveQuery
     */
    public function getUploads()
    {
        return $this->hasMany({_OBJECT_MODEL_NAME_}{ITEM_NAME}Upload::className(), ['object_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return {_OBJECT_MODEL_NAME_}{ITEM_NAME}Query the active query used by this AR class.
     */
    public static function find()
    {
        return new {_OBJECT_MODEL_NAME_}{ITEM_NAME}Query(get_called_class());
    }

    public function getParentObject()
    {
        return $this->hasOne({_OBJECT_MODEL_NAME_}::className(), ['id' => '{_ITEM_TABLE_PARENT_ID_FIELD_}']);
    }
}
