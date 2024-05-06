<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Devolucoes;

/**
 * DevolucoesSearch represents the model behind the search form of `frontend\models\Devolucoes`.
 */
class DevolucoesSearch extends Devolucoes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'id_filme'], 'integer'],
            [['data_inicio', 'data_entrega'], 'safe'],
            [['valor_pago', 'valor_multa', 'valor_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Devolucoes::find();

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
            'id_cliente' => $this->id_cliente,
            'id_filme' => $this->id_filme,
            'data_inicio' => $this->data_inicio,
            'data_entrega' => $this->data_entrega,
            'valor_pago' => $this->valor_pago,
            'valor_multa' => $this->valor_multa,
            'valor_total' => $this->valor_total,
        ]);

        return $dataProvider;
    }
}
