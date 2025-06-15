<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Usuario;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    private $key = '336ef2c5c8f2d8e3e6656a6fc7879d115da84bb7e310ced16f257708d4633679'; 

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => ['application/json' => Response::FORMAT_JSON],
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }


    public function actionLogin()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['contrasena'])) {
            return ['error' => 'Faltan credenciales'];
        }

        $usuario = Usuario::findOne(['email' => $data['email']]);

        if (!$usuario) {
            return ['error' => 'Usuario no encontrado'];
        }

        if (!password_verify($data['contrasena'], $usuario->contrasena)) {
            return ['error' => 'Contraseña incorrecta'];
        }

        $payload = [
            'exp' => time() + 3600,  // Expira en 1h
            'data' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
            ]
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');

        return [
            'token' => $jwt,
            'usuario' => $payload['data']
        ];
    }

    public function actionVerify()
    {
        $headers = Yii::$app->request->headers;
        $authHeader = $headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return ['error' => 'Token no encontrado'];
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return ['valid' => true, 'data' => $decoded->data];
        } catch (\Exception $e) {
            return ['error' => 'Token inválido', 'message' => $e->getMessage()];
        }
    }
}
