<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_searah".
 *
 * @property int $id
 * @property string $searah_name
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_on
 * @property string $updated_on
 * @property int $is_deleted
 *
 * @property TblProduct[] $tblProducts
 */
class Searah extends \common\models\MasterModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_searah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['searah_name'], 'required'],
            [['created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['searah_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'searah_name' => 'Searah Name',
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
        return $this->hasMany(Product::className(), ['searah_id' => 'id']);
    }
}
