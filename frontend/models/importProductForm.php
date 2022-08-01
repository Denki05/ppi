<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class ImportProductForm extends Model
{
	public $excelFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            array('excelFile', 'file', 'extensions' => 'xls, xlsx'),
        ];
    }
	
	
}
