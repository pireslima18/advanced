<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "filmes_alugados".
 *
 * @property int $id
 * @property int $id_aluguel
 * @property int $id_filme
 *
 * @property Alugueis $aluguel
 * @property Filmes $filme
 */
class FilmesAlugados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filmes_alugados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_aluguel', 'id_filme'], 'required'],
            [['id_aluguel', 'id_filme'], 'integer'],
            [['id_aluguel'], 'exist', 'skipOnError' => true, 'targetClass' => Alugueis::class, 'targetAttribute' => ['id_aluguel' => 'id']],
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
            'id_aluguel' => 'Id Aluguel',
            'id_filme' => 'Id Filme',
        ];
    }

    /**
     * Gets query for [[Aluguel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAluguel()
    {
        return $this->hasOne(Alugueis::class, ['id' => 'id_aluguel']);
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
