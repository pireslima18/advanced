<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "filmes_devolvidos".
 *
 * @property int $id
 * @property int|null $id_filme
 * @property int $id_devolucao
 *
 * @property Devolucoes $devolucao
 * @property Filmes $filme
 */
class FilmesDevolvidos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filmes_devolvidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_filme', 'id_devolucao'], 'integer'],
            [['id_devolucao'], 'required'],
            [['id_devolucao'], 'exist', 'skipOnError' => true, 'targetClass' => Devolucoes::class, 'targetAttribute' => ['id_devolucao' => 'id']],
            [['id_filme'], 'exist', 'skipOnError' => true, 'targetClass' => Filmes::class, 'targetAttribute' => ['id_filme' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_filme' => 'Id Filme',
            'id_devolucao' => 'Id Devolucao',
        ];
    }

    /**
     * Gets query for [[Devolucao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucao()
    {
        return $this->hasOne(Devolucoes::class, ['id' => 'id_devolucao']);
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
}
