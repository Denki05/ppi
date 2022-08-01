<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_packaging".
 *
 * @property int $id
 * @property string $packaging_name
 * @property int $is_deleted
 */
class Packaging extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_packaging';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['packaging_name'], 'required'],
            [['is_deleted'], 'integer'],
            [['packaging_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'packaging_name' => 'Kemasan',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
