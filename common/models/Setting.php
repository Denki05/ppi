<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_setting".
 *
 * @property integer $setting_id
 * @property integer $setting_category_id
 * @property string $setting_label
 * @property string $setting_name
 * @property string $setting_value
 * @property string $setting_desc
 * @property string $setting_input_type
 * @property string $setting_input_size
 * @property string $setting_dropdown_options
 *
 * @property SettingCategory $settingCategory
 */
class Setting extends \common\models\MasterModel
{
	const INPUT_TYPE_TEXT = 'text';
	const INPUT_TYPE_TEXTAREA = 'textarea';
	const INPUT_TYPE_DROPDOWN = 'dropdown';
	const INPUT_TYPE_FILE = 'file';

    const SETTING_CAT_PRINT_LABEL = '2';
	
	public $image_file;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_category_id', 'setting_label', 'setting_name', 'setting_input_type', 'setting_input_size'], 'required'],
            [['setting_category_id'], 'integer'],
            [['setting_input_type', 'setting_input_size'], 'string'],
            [['setting_label', 'setting_name', 'setting_value', 'setting_desc'], 'string', 'max' => 255],
			[['setting_value', 'setting_dropdown_options'], 'safe'],
			[['image_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 2],
            [['setting_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SettingCategory::className(), 'targetAttribute' => ['setting_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'setting_category_id' => 'Setting Category ID',
            'setting_label' => 'Setting Label',
            'setting_name' => 'Setting Name',
            'setting_value' => 'Setting Value',
            'setting_desc' => 'Setting Desc',
            'setting_input_type' => 'Setting Input Type',
            'setting_input_size' => 'Setting Input Size',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingCategory()
    {
        return $this->hasOne(SettingCategory::className(), ['category_id' => 'setting_category_id']);
    }
	
	public static function getSettingByName($name)
	{
		return Setting::find()->andWhere('setting_name=:name', [':name' => $name])->one();
	}
	
	public static function getSettingValueByName($name) 
	{
		$setting = self::getSettingByName($name);
		if ($setting)
			return $setting->setting_value;
		return "";
	}
}
