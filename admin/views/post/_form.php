<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use zakurdaev\editorjs\EditorJsWidget;
use admin\components\PostStatus;
use common\models\PostCategory;

/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
        $data = [];
        foreach (PostCategory::find()->all() as $item) {
            $data[$item->id] = $item->name;
        }
        echo $form->field($model, 'post_category_id')->dropDownList($data, ['prompt' => 'Выберите категорию']);
    ?>

    <?php
        $status = new PostStatus();
        echo $form->field($model, 'status')->dropDownList($status->getStatuses(true));
    ?>

    <?php
        echo $form->field($model, 'image')->fileInput();
        if (isset($model->image)) {
            echo Html::img('/uploads/'."{$model->image}");
        }
    ?>

    <?= $form->field($model, 'text')->widget(EditorJsWidget::class, [
        'selectorForm' => $form->id,
        'endpoints' => [
            'uploadImageByFile' => Url::to(['/site/upload-file']),
            'uploadImageByUrl' => Url::to(['/site/fetch-url']),
        ],
    ]) ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success w-100']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>