<?php

namespace common\models;

use admin\components\PostStatus;
use Yii;
use yii\behaviors\TimestampBehavior;
use admin\components\StatusBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string $text
 * @property int|null $post_category_id
 * @property int $status
 * @property string|null $image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PostCategory $postCategory
 * @property User $user
 * @property string $htmlContent
 */
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
            ]
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'post_category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'text'], 'required'],
            [['text', 'image'], 'string'],
            [['title'], 'string', 'max' => 255],

            [['post_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PostCategory::class, 'targetAttribute' => ['post_category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Название',
            'text' => 'Контент',
            'post_category_id' => 'Категория',
            'status' => 'Статус',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate(): bool
    {
        // $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        return parent::beforeValidate();
    }

    public function beforeSave($insert): bool
    {
        // Сохранение файла
        $this->image = '/uploads';
        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[PostCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategory()
    {
        return $this->hasOne(PostCategory::class, ['id' => 'post_category_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getStatus()
    {
        $status = new PostStatus();
        return $status->getStatusByCode($this->status, true);
    }

    public function getHtmlContent()
    {
        $htmlData = '';
        $blocks = json_decode($this->text, true)['blocks'];
        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $htmlData .= "<p>{$block['data']['text']}</p>";
                    break;
                case 'header':
                    $htmlData .= "<h{$block['data']['level']}>{$block['data']['text']}</h{$block['data']['level']}>";
                    break;
                case 'list':
                    $htmlData .= $block['data']['style'] == 'ordered' ? '<ol>' : '<ul>';
                    foreach ($block['data']['items'] as $item) {
                        $htmlData .= "<li>{$item}</li>";
                    }
                    $htmlData .= $block['data']['style'] == 'ordered' ? '</ol>' : '</ul>';
                    break;
                case 'table':
                    $htmlData .= '<table>';
                    foreach ($block['data']['content'] as $row) {
                        $htmlData .= '<tr>';
                        foreach ($row as $rowItem) {
                            $htmlData .= "<td>{$rowItem}</td>";
                        }
                        $htmlData .= '</tr>';
                    }
                    $htmlData .= '</table>';
                    break;
                case 'quote':
                    $htmlData .= "<blockquote cite={$block['data']['caption']}>{$block['data']['text']}</blockquote>";
                    break;
            }
        }
        return $htmlData;
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'text' => 'htmlContent',
            'user_id'
        ];
    }
}
