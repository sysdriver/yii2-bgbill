<?php

namespace frontend\modules\bgbill\models;

use Yii;

/**
 * This is the model class for table "contract_account".
 *
 * @property integer $yy
 * @property integer $mm
 * @property integer $cid
 * @property integer $sid
 * @property string $summa
 */
class ContractAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_account';
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
            [['yy', 'mm', 'cid', 'sid'], 'required'],
            [['yy', 'mm', 'cid', 'sid'], 'integer'],
            [['summa'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'yy' => 'Yy',
            'mm' => 'Mm',
            'cid' => 'Cid',
            'sid' => 'Sid',
            'summa' => 'Summa',
        ];
    }
}