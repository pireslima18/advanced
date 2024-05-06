<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id
 * @property string $nome_categoria
 *
 * @property Filmes[] $filmes
 */
class Categorias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome_categoria'], 'required'],
            [['nome_categoria'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome_categoria' => 'Nome Categoria',
        ];
    }

    /**
     * Gets query for [[Filmes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilmes()
    {
        return $this->hasMany(Filmes::class, ['categoria_id' => 'id']);
    }
}
