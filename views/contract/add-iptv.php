<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\data\ArrayDataProvider;

$this->title = Yii::t('app', 'Добавление учетной записи IPTV для договора bgbilling');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php if($info) { ?>
	<div class="alert alert-info">
		<strong>Info!</strong> Indicates a neutral informative change or action.
	</div>
<?php } ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'class' => 'form-horizontal', 
]);
?>

    <?php
    
    print_r(count($items));
    
    $options = ['style'=>'width:250px;margin-bottom:10px;', 'class'=>'form-control',];
    $dFormat = 'dd.MM.yyyy';
    $lng = 'ru';
    ?>
    
    <div class="row">
        <div class="col-xs-6 form-group" style="">

        <?php

        foreach ($model as $key => $val) {
            if($key == 'pswd') {
                echo $form->field($model,$key)->passwordInput(['readonly' => true,'style' => 'width:250px;']);     //'maxlength'=>40,  ->hint('Password should be within A-Za-z0-9')    
            } else
                echo $form->field($model, $key)->textInput(['readonly' => true,'style' => 'width:250px;']);
        }
        ?>

        </div>
        
        <div class="col-xs-6 form-group">
            <p>
                Информация об учетных записях договора на портале IPTV
            </p>
            
            
            <div id="iptv-info">
                
            
            
            <?php
            if(empty($subscriber)) {
                
                echo Html::a('Добавить', 
                    '#iptv-info', [
                    'title' => Yii::t('yii', 'Добавить'),
                    'onclick'=>"//$('#sync').dialog('open');//for jui dialog in my page ?cid=3735
                        $.ajax({
                        type     :'POST',
                        cache    : false,
                        url  : '/bgbill/contract/add-iptv-account',
                        data: {cid:".$model->id."}, //
                        success  : function(response) {
                            $('#iptv-info').html(response);
                        }
                        });return false;",
                ]);
            } else {
                $dataProvider = new ArrayDataProvider([
                    'allModels' => [$subscriber],
                    'sort' => [
                        'attributes' => ['id','username'],
                    ],
                    'pagination' => [
                        'pageSize' => 2,
                    ],
                ]);
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        ['class' => 'yii\grid\RadioColumn'],
                        'id',
                        'username',
                        'surname',
                        'first_name',
                        'password'
                    ],

                    'layout'=>"{pager}\n{summary}\n{items}",
                    'pager' => [
                        'firstPageLabel' => 'First',
                        'lastPageLabel'  => 'Last'
                    ],

                ]); 
            }
            ?>
                
            </div>
        </div>
        
    </div>
        
        
	<?php

	?>
    
<?php ActiveForm::end(); ?>
