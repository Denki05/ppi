<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_setting_category".
 *
 * @property integer $category_id
 * @property string $category_name
 * @property integer $is_deleted
 *
 * @property Setting[] $settings
 */
class SettingCategory extends \common\models\MasterModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_setting_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['is_deleted'], 'integer'],
            [['category_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(Setting::className(), ['setting_category_id' => 'category_id']);
    }
}
