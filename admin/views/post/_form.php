<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zakurdaev\editorjs\EditorJsWidget;

/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'author_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])?>

    <?= $form->field($model, 'content')->widget(EditorJsWidget::class, [
        'selectorForm' => $form->id,
    ]) ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success w-100']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>