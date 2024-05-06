<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "devolucoes".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_filme
 * @property string $data_inicio
 * @property string $data_entrega
 * @property float $valor_pago
 * @property float $valor_multa
 * @property float $valor_total
 *
 * @property Clientes $cliente
 * @property Filmes $filme
 */
class Devolucoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devolucoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_filme', 'data_inicio', 'data_entrega', 'valor_pago', 'valor_multa', 'valor_total'], 'required'],
            [['id_cliente', 'id_filme'], 'integer'],
            [['data_inicio', 'data_entrega'], 'safe'],
            [['valor_pago', 'valor_multa', 'valor_total'], 'number'],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::class, 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Nome cliente',
            'id_filme' => 'Nome filme',
            'data_inicio' => 'Data Inicio',
            'data_entrega' => 'Data Entrega',
            'valor_pago' => 'Valor Pago',
            'valor_multa' => 'Valor Multa',
            'valor_total' => 'Valor Total',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::class, ['id' => 'id_cliente']);
    }

    /**
     * Gets query for [[Filme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilme()
    {
        return $this->hasOne(Filmes::class, ['id' => 'id_filme']);
    }

    public function getFilmesDevolvidos()
    {
        return $this->hasMany(FilmesDevolvidos::class, ['id_filme' => 'id_filme']);
    }
}
