<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "classificacoes".
 *
 * @property int $id
 * @property string $classificacao
 */
class Classificacoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classificacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classificacao'], 'required'],
            [['classificacao'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'classificacao' => 'Classificacao',
        ];
    }
}
