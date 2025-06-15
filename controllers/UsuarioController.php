<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\Usuario;

class UsuarioController extends Controller
{
    public $enableCsrfValidation = false; 

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ];
    }

    // GET /usuarios
    public function actionIndex()
    {
        return Usuario::find()->all();
    }

    // GET /usuarios/<id>
    public function actionView($id)
    {
        $usuario = Usuario::findOne($id);
        if ($usuario === null) {
            throw new NotFoundHttpException("Usuario no encontrado");
        }
        return $usuario;
    }

    // POST /usuarios
    public function actionCreate()
    {
        $body = Yii::$app->request->bodyParams;
        $usuario = new Usuario();
        $usuario->load($body, '');
        if ($usuario->save()) {
            return $usuario;
        }
        return ['error' => $usuario->getErrors()];
    }

    // PUT /usuarios/<id>
    public function actionUpdate($id)
    {
        $usuario = Usuario::findOne($id);
        if (!$usuario) {
            throw new NotFoundHttpException("Usuario no encontrado");
        }

        $body = Yii::$app->request->bodyParams;
        $usuario->load($body, '');

        if ($usuario->save()) {
            return $usuario;
        }
        return ['error' => $usuario->getErrors()];
    }

    // DELETE /usuarios/<id>
    public function actionDelete($id)
    {
        $usuario = Usuario::findOne($id);
        if (!$usuario) {
            throw new NotFoundHttpException("Usuario no encontrado");
        }

        $usuario->delete();
        return ['mensaje' => 'Usuario eliminado'];
    }
}
