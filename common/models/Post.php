<?php

namespace common\models;

use admin\components\PostStatus;
use Yii;
use yii\behaviors\TimestampBehavior;
use admin\components\StatusBehavior;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
    /** @var UploadedFile|string */
    public $imageFile;
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
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg'],
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
            'image' => 'Картинка',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate(): bool
    {
        if (Yii::$app->id === 'app-admin') {
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        }
        return parent::beforeValidate();
    }

    //сохраняет картинку и возвращает название картинки
    private function saveImage(UploadedFile $image) : string
    {
        $imgName = uniqid().".{$this->imageFile->extension}";
        $path = Yii::getAlias('@uploads')."/$imgName";
        $this->imageFile->saveAs($path);
        return $imgName;
    }

    //возвращает новое имя и удаляет старую картинку
    private function replaceImage(string $oldImgName, UploadedFile $newImg) : string
    {
        $path = Yii::getAlias('@uploads')."/$oldImgName";
        if (file_exists($path) && !is_dir($path)) {
            unlink($path);
        }
        return $this->saveImage($newImg);
    }

    public function beforeSave($insert): bool
    {
        // Сохранение файла
        if (isset($this->imageFile)) {
            if ($insert) {
                $this->image = $this->saveImage($this->imageFile);
            } else {
                $this->image = $this->replaceImage($this->oldAttributes['image'], $this->imageFile);
            }
        }
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
        if (json_decode($this->text) != null) {
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
        } else {
            $htmlData = "<p>{$this->text}</p>";
        }
        return $htmlData;
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'text' => 'htmlContent',
            'image',
            'user_id',
            'category_name' => fn () => $this->postCategory->name
        ];
    }
}
