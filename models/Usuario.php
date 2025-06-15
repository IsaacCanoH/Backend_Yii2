<?php

namespace app\models;

use yii\db\ActiveRecord;

class Usuario extends ActiveRecord
{
    public static function tableName()
    {
        return 'usuarios';
    }

    public function rules()
    {
        return [
            [['nombre', 'apellido', 'email', 'contrasena', 'rol'], 'required'],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['contrasena'], 'string'],
            [['rol', 'estado'], 'string', 'max' => 20],
        ];
    }
}
