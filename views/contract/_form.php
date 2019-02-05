<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\bgbilling\Contract */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gr')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_pattern_id')->textInput() ?>

    <?= $form->field($model, 'pswd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date1')->textInput() ?>

    <?= $form->field($model, 'date2')->textInput() ?>

    <?= $form->field($model, 'mode')->textInput() ?>

    <?= $form->field($model, 'closesumma')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pgid')->textInput() ?>

    <?= $form->field($model, 'pfid')->textInput() ?>

    <?= $form->field($model, 'fc')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'del')->textInput() ?>

    <?= $form->field($model, 'scid')->textInput() ?>

    <?= $form->field($model, 'sub_list')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sub_mode')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status_date')->textInput() ?>

    <?= $form->field($model, 'last_tariff_change')->textInput() ?>

    <?= $form->field($model, 'crm_customer_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
