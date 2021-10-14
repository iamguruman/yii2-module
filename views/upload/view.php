<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\MProjectUpload */

$module = app\modules\project\Module::moduleId;
$controller = 'upload';

$this->title = $model->filename_original;

$this->params['breadcrumbs'][] = app\modules\project\Module::getBreadcrumbs();

if($model->object){
    $this->params['breadcrumbs'][] = $model->object->getBreadcrumbs();

    $this->params['breadcrumbs'][] = ['label' => 'Файлы',
        'url' => ["/{$module}/default/view", 'id' => $model->object->id, 'tab' => 'files']];
}

$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="mproject-upload-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= aHtmlButtonUpdate($model) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'filename_original',
            'ext',
            'mimetype',
        ],
    ]) ?>

    <?= \yii\bootstrap\Tabs::widget(['items' => [
        [
            'label' => "ID",
            'active' => false,
            'content' => "<br>".DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'teamBy.name',
                        'object.urlTo:raw',
                        'created_at',
                        'createdBy.lastNameWithInitials',
                        'updated_at',
                        'updatedBy.lastNameWithInitials',
                        'markdel_at',
                        'markdelBy.lastNameWithInitials',
                    ],
                ])
        ],
    ]]) ?>

</div>
