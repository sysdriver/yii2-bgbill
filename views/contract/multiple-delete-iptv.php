<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Yii::t('app', 'Удаление учетных записей IPTV для договоров bgbilling');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php if($info) { ?>
	<div class="alert alert-info">
        <?= Html::label('Договоров в bg billing, имеющих учетные записи на портале ИПТВ: '.count($items),'', ['style'=>'width:100%;',]); ?>
	</div>
<?php } ?>

<?php $form = ActiveForm::begin();?>

    <?php
    $options = ['style'=>'width:250px;margin-bottom:10px;', 'class'=>'form-control',];
    $dFormat = 'dd.MM.yyyy';
    $lng = 'ru';
    
    echo Html::label('Дата начала','from_date', ['style'=>'width:250px;',]);
    
    echo DatePicker::widget([
        'attribute' => 'from_date',
        'name'  => 'from_date',
        'id' => 'from_date',
        'language' => $lng,
        'dateFormat' => $dFormat,
        'options'=>$options,  
    ]);
    
    echo Html::label('Дата завершения','to_date', ['style'=>'width:250px;',]);
    
    echo DatePicker::widget([
        'attribute' => 'to_date',
        'name'  => 'to_date',
        'id' => 'to_date',
        'language' => $lng,
        'dateFormat' => $dFormat,
        'options'=>$options,  
    ]);

	?>
    <p></p>
    
        
	<?php
    //echo $f->field($form, 'year')->dropDownList($items2,$p2)->label('Год');
    echo yii\bootstrap\Button::widget([
        'label' => 'Отправить',
        'options' => ['style'=>'width:250px;','class' => 'btn btn-primary'],
    ]);
	?>
    
<?php ActiveForm::end(); ?>
