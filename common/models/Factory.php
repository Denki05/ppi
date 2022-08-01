<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_factory".
 *
 * @property int $id
 * @property string $factory_name
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 *
 * @property TblProduct[] $tblProducts
 */
class Factory extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_factory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['factory_name'], 'required'],
            [['created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['factory_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_name' => 'Factory Name',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProducts()
    {
        return $this->hasMany(Product::className(), ['factory_id' => 'id']);
    }
}
