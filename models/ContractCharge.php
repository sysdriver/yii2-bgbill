<?php

namespace frontend\modules\bgbill\models;

use Yii;

/**
 * This is the model class for table "contract_charge".
 *
 * @property integer $id
 * @property string $dt
 * @property integer $cid
 * @property integer $pt
 * @property integer $uid
 * @property string $summa
 * @property string $comment
 * @property string $lm
 */
class ContractCharge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_charge';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbbgbilling');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt', 'lm'], 'safe'],
            [['cid', 'pt', 'uid'], 'integer'],
            [['summa'], 'required'],
            [['summa'], 'number'],
            [['comment'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt' => 'Dt',
            'cid' => 'Cid',
            'pt' => 'Pt',
            'uid' => 'Uid',
            'summa' => 'Summa',
            'comment' => 'Comment',
            'lm' => 'Lm',
        ];
    }
}