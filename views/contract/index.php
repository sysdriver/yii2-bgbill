<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\bgbilling\ContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contracts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Contract', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'title',
                'label' => 'N договора',
            ],
            [
                'attribute' => 'date1',
                'label' => 'Дата начала действия',
            ],
             
            [
                'attribute' => 'date2',
                'label' => 'Дата окончания действия',
            ],
            [
                'attribute' => 'comment',
                'label' => 'Клиент',
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус (0-открыт, 3-закрыт)',
            ],
            ['class' => 'yii\grid\ActionColumn'],
            [
                'attribute' => 'Добавить iptv',
                'value' => function (\frontend\modules\bgbill\models\Contract $data) {
                    return Html::a(Html::encode($data->title), Url::to(['add-iptv?cid='.$data->id]));   // 'id' => $data->id
                },
                'format' => 'raw',
            ],
        ],
        'layout'=>"{pager}\n{items}\n{summary}\n{pager}",
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel'  => 'Последняя'
        ],
    ]); ?>
</div>
