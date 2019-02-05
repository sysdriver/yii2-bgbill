<?php

namespace frontend\modules\bgbill\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\bgbill\models\Contract;

/**
 * ContractSearch represents the model behind the search form about `frontend\models\bgbilling\Contract`.
 */
class ContractSearch extends Contract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gr', 'title_pattern_id', 'mode', 'pgid', 'pfid', 'fc', 'del', 'scid', 'sub_mode', 'status', 'crm_customer_id'], 'integer'],
            [['title', 'pswd', 'date1', 'date2', 'comment', 'sub_list', 'status_date', 'last_tariff_change'], 'safe'],
            [['closesumma'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Contract::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gr' => $this->gr,
            'title_pattern_id' => $this->title_pattern_id,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'mode' => $this->mode,
            'closesumma' => $this->closesumma,
            'pgid' => $this->pgid,
            'pfid' => $this->pfid,
            'fc' => $this->fc,
            'del' => $this->del,
            'scid' => $this->scid,
            'sub_mode' => $this->sub_mode,
            'status' => $this->status,
            'status_date' => $this->status_date,
            'last_tariff_change' => $this->last_tariff_change,
            'crm_customer_id' => $this->crm_customer_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'pswd', $this->pswd])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'sub_list', $this->sub_list]);

        return $dataProvider;
    }
}
