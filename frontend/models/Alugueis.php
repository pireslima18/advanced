<?php

namespace frontend\models;

use Yii;
use DateTime;

/**
 * This is the model class for table "alugueis".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_filmes
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $status
 *
 * @property Clientes $cliente
 * @property Devolucoes[] $devolucoes
 * @property Filmes $filme
 */
class Alugueis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alugueis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_filmes', 'data_inicio', 'data_fim'], 'required'],
            [['id_cliente', 'id_filmes'], 'integer'],
            [['data_inicio', 'data_fim', 'preco_final'], 'safe'],
            [['preco_final'], 'number'],
            [['status'], 'string'],
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
            'id_cliente' => 'Cliente',
            'id_filmes' => 'Filme',
            'data_inicio' => 'Data Inicio',
            'data_fim' => 'Data Fim',
            'preco_final' => 'Preço',
            'status' => 'Status',
        ];
    }

    public function beforeSave($insert) {
        $dataInicio = DateTime::createFromFormat('d/m/Y', $this->data_inicio);
        $dataFim = DateTime::createFromFormat('d/m/Y', $this->data_fim);
    
        $this->data_inicio = $dataInicio->format('Y-m-d');
        $this->data_fim = $dataFim->format('Y-m-d');
    
        return parent::beforeSave($insert);
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
     * Gets query for [[Devolucoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucoes()
    {
        return $this->hasMany(Devolucoes::class, ['id_aluguel' => 'id']);
    }

    /**
     * Gets query for [[Filme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilme()
    {
        return $this->hasOne(Filmes::class, ['id' => 'id_filmes']);
    }

    public function getFilmesAlugados()
    {
        // return $this->hasMany(FilmesAlugados::class, ['id_aluguel' => 'id']);
        $result = json_decode($this->id, true);

        return $this->hasMany(FilmesAlugados::className(), ['id_aluguel' => $result]);
    }
}
