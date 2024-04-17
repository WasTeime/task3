<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $content
 * @property int $author_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $author
 * @property string $htmlContent
 */
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
            ],
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
            [['title', 'content'], 'required'],
            [['description', 'content'], 'string'],
            [['author_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'content' => 'Контент',
            'author_id' => 'Author ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getHtmlContent()
    {
        $htmlData = '';
        $blocks = json_decode($this->content, true)['blocks'];
        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $htmlData .= "<p>{$block['data']['text']}</p>";
                    break;
                case 'header':
                    $htmlData .= "<h{$block->data->level}>{$block->data->text}</h{$block->data->level}>";
                    break;
                case 'list':
                    $htmlData .= $block->data->style == 'ordered' ? '<ol>' : '<ul>';
                    foreach ($block->data->items as $item) {
                        $htmlData .= "<li>{$item}</li>";
                    }
                    $htmlData .= $block->data->style == 'ordered' ? '</ol>' : '</ul>';
                    break;
                case 'table':
                    $htmlData .= '<table>';
                    foreach ($block->data->content as $row) {
                        $htmlData .= '<tr>';
                        foreach ($row as $rowItem) {
                            $htmlData .= "<td>{$rowItem}</td>";
                        }
                        $htmlData .= '</tr>';
                    }
                    $htmlData .= '</table>';
                    break;
                case 'quote':
                    $htmlData .= "<blockquote cite={$block->data->caption}>{$block->data->text}</blockquote>";
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
            'description',
            'content' => 'htmlContent',
            'author_id'
        ];
    }
}
