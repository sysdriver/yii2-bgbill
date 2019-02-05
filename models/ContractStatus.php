<?php

namespace frontend\modules\bgbill\models;

use Yii;

/**
 * This is the model class for table "contract_status".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $status
 * @property integer $uid
 * @property string $date1
 * @property string $date2
 * @property string $comment
 */
class ContractStatus extends \frontend\modules\bgbill\components\BgActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_status';
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
            [['cid', 'status', 'uid', 'date1', 'comment'], 'required'],
            [['cid', 'status', 'uid'], 'integer'],
            [['date1', 'date2'], 'safe'],
            [['comment'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'status' => 'Status',
            'uid' => 'Uid',
            'date1' => 'Date1',
            'date2' => 'Date2',
            'comment' => 'Comment',
        ];
    }
}
