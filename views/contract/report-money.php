<?php

//use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use kartik\dialog\Dialog;
use kartik\grid\GridView;



$this->title = Yii::t('app', 'Реестр денежных операций bg billing');
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

    $gridColumns = [
        [
            'class'=>'kartik\grid\SerialColumn',
            'contentOptions'=>['class'=>'kartik-sheet-style'],
            'width'=>'36px',
            'header'=>'',
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'contract',
            'header' => 'Контракт',
            'pageSummary'=>'Total',
            'vAlign'=>'middle',
            'width'=>'210px',
            'editableOptions'=> function ($model, $key, $index) use ($colorPluginOptions) {
                return [
                    'header'=>'Контракт',
                    'name' => 'contract',
                    'size'=>'md',
                ];
            }
        ],
        [
            'attribute'=>'summ',
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status',
            'header'=>'Чекбокс',
            'vAlign'=>'middle'
        ],


        ['class' => 'kartik\grid\CheckboxColumn']
    ];
        
    echo GridView::widget([
        'dataProvider' => $provider,
        //'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        
    ]);

    echo yii\bootstrap\Button::widget([
        'label' => 'Отправить',
        'options' => ['style'=>'width:250px;','class' => 'btn btn-primary'],
    ]);
	?>
    
    
<?php ActiveForm::end(); ?>
