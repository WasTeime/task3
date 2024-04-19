<?php

namespace api\modules\v1\controllers;

use api\modules\v1\controllers\AppController;
use common\models\Post;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class PostController extends AppController
{
    /*public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authentificator' => ['except' => ['posts']]
        ]);
    }*/

    private function _getPost($request)
    {
        $first_item = $request->get('first_item');
        $item_count = $request->get('item_count');

        $query = Post::find()->filterWhere([
            'id' => $request->get('id'),
            'user_id' => $request->get('user_id'),
            'post_category_id' => $request->get('category_id')
        ]);
        if ($first_item != null) {
            $query->offset($first_item);
        }
        if ($item_count != null) {
            $query->limit($item_count);
        }
        return $this->returnSuccess($query->all());
    }

    private function _addPost($request)
    {
        $post = new Post();
        $post->user_id = $request->post('user_id');
        $post->post_category_id = $request->post('category_id');
        $post->title = $request->post('title');
        $post->text = $request->post('text');
        $post->imageFile = UploadedFile::getInstanceByName('image');

        if ($post->save()) {
            return $this->returnSuccess($post);
        } else {
            return $this->returnError('validation', $post->getErrors());
        }
    }

    public function actionUpdate()
    {
        $post = Post::findOne(Yii::$app->request->post('id'));
        if (!$post) {
            return $this->returnError('db', 'Пост не найден');
        }

        $category = Yii::$app->request->post('category_id');
        if ($category !== null) {
            $post->post_category_id = $category;
        }

        $title = Yii::$app->request->post('title');
        if ($title !== null) {
            $post->title = $title;
        }
        $text = Yii::$app->request->post('text');
        if ($text !== null) {
            $post->text = $text;
        }

        if ($post->save()) {
            return $this->returnSuccess($post);
        } else {
            return $this->returnError('validation', $post->getErrors());
        }
    }

    private function _deletePost($request)
    {
        $post = Post::findOne($request->get('id'));
        if (!$post) {
            return $this->returnError('db', 'Пост не найден');
        }
        if (!$post->delete()) {
            return $this->returnError('db', 'Не получилось удалить пост');
        }
        return $this->returnSuccess('Пост удалён');
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        return match ($request->getMethod()) {
            'POST' => $this->_addPost($request),
            'DELETE' => $this->_deletePost($request),
            'GET' => $this->_getPost($request),
            default => $this->returnSuccess([]),
        };
    }
}