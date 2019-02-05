<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Yii::t('app', 'Отчёт по оттоку bg billing');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php if($info) { ?>
	<div class="alert alert-info">
		<strong>Info!</strong> Indicates a neutral informative change or action.
	</div>
<?php } ?>

<?php $f = ActiveForm::begin();?>

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
	<?php
    echo yii\bootstrap\Button::widget([
        'label' => 'Отправить',
        'options' => ['style'=>'width:250px;','class' => 'btn btn-primary'],
    ]);
	?>
	
<?php ActiveForm::end(); ?>
