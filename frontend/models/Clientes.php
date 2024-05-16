<?php

namespace frontend\models;

use Yii;
use DateTime;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $telefone
 * @property int $filmes_alugados
 */
class Clientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'telefone', 'filmes_alugados', 'data_nascimento'], 'required'],
            [['filmes_alugados'], 'integer'],
            [['nome'], 'string', 'max' => 40],
            [['email'], 'string', 'max' => 50],
            [['telefone'], 'string', 'max' => 20],
            [['data_nascimento'], 'checkDate'],
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
            'email' => 'Email',
            'telefone' => 'Telefone',
            'data_nascimento' => 'Data Nascimento',
            'filmes_alugados' => 'Filmes Alugados',
        ];
    }

    public function beforeSave($insert) {
        // $format = checkDateFormat($this->data_nascimento);
        $format = 0;
    
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $this->data_nascimento)) {
            $format = 'd-m-Y';
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->data_nascimento)) {
            $format = 'Y-m-d';
        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->data_nascimento)) {
            $format = 'd/m/Y';
        } else {
            $format = false;
        }

        $date = DateTime::createFromFormat($format, $this->data_nascimento);
        $this->data_nascimento = $date->format('Y-m-d');
        
        return true;
    }

    public function checkDate($attribute, $params){
        $format = false;
    
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $this->$attribute)) {
            $format = 'd-m-Y';
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->$attribute)) {
            $format = 'Y-m-d';
        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->$attribute)) {
            $format = 'd/m/Y';
        }
    
        if ($format) {
            $dateTime = DateTime::createFromFormat($format, $this->$attribute);
            if ($dateTime && $dateTime->format($format) === $this->$attribute) {
                $diaSelecionado = $dateTime->format('Y-m-d');
                $dataAtual = date('Y-m-d');
                
                if($diaSelecionado > $dataAtual){
                    $this->addError($attribute, 'A data de nascimento deve ser anterior à data atual.');
                }
            } else {
                echo "Data inválida";
            }
        } else {
            echo "Formato de data inválido";
        }
    }

    // Função para calcular idade do cliente
    public function obterIdadeCliente($cliente){
        // Data de nascimento do cliente
        $dataNascimento = $cliente->data_nascimento;  
        // Convertendo para um objeto DateTime
        $dataNascimento = new DateTime($dataNascimento);
        // Data atual
        $dataAtual = new DateTime();
        // Calculando a diferença entre a data atual e a data de nascimento
        $diferenca = $dataAtual->diff($dataNascimento);
        // Obtendo a idade
        $idade = $diferenca->y;
        return $idade;
    }
}
