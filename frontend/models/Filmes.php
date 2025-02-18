<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "filmes".
 *
 * @property int $id
 * @property string $nome
 * @property int $categoria_id
 * @property float $valor_dia
 * @property string $status
 *
 * @property Categorias $categoria
 */
class Filmes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filmes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'categoria_id', 'classificacao_id'], 'required'],
            [['categoria_id', 'classificacao_id'], 'integer'],
            [['valor_dia'], 'number'],
            [['status'], 'string'],
            [['nome'], 'string', 'max' => 40],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['classificacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classificacoes::class, 'targetAttribute' => ['classificacao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'categoria_id' => 'Categoria',
            'classificacao_id' => 'Classificação',
            'valor_dia' => 'Valor Dia',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Classificacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassificacao()
    {
        return $this->hasOne(Classificacoes::class, ['id' => 'classificacao_id']);
    }
}
