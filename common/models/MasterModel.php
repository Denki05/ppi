<?php

namespace common\models;

use Yii;

/**
 * Class to put generic functions for all models
 *
 */
class MasterModel extends \yii\db\ActiveRecord
{
	const SCENARIO_INSERT = 'insert';
	const SCENARIO_UPDATE = 'update';
	const SCENARIO_DELETE = 'delete';
	const SCENARIO_REPORT = 'report';

	// Keyword to be used when filling filter for querying records with value null
	const CRITERIA_IS_NULL = ':kosong';

	// Whether or not to fill updated_on and updated_by on create
	protected $alwaysFillUpdateAttributes = false;

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if ($this->isNewRecord) {
				if ($this->hasAttribute('created_on'))
					$this->created_on = date("Y-m-d H:i:s");
				if ($this->hasAttribute('created_by'))
					$this->created_by = Yii::$app->user->id;
			}
			if (!$this->isNewRecord && $this->scenario != self::SCENARIO_DELETE) {
				if ($this->hasAttribute('updated_on'))
					$this->updated_on = date("Y-m-d H:i:s");
				if ($this->hasAttribute('updated_by'))
					$this->updated_by = Yii::$app->user->id;
			}
			if (!$this->isNewRecord && $this->scenario == self::SCENARIO_DELETE) {
				if ($this->hasAttribute('deleted_on'))
					$this->deleted_on = date("Y-m-d H:i:s");
				if ($this->hasAttribute('deleted_by'))
					$this->deleted_by = Yii::$app->user->id;
			}

			return true;
		} else {
			return false;
		}
	}
	
	public function getMaxId($yearMonth, $field) 
	{
		$connection = \Yii::$app->db;
        $row = $connection->createCommand('SELECT MAX('.$field.') as mid FROM '.$this->tableName().' WHERE '.$field.' LIKE "%'.$yearMonth.'%"')->queryOne();
        if ($row['mid']) {
            return $row['mid'];
        } else {
            return 'false';
        }
    }

	public function getMaxIdP($yearMonthP, $fieldP) 
	{
		$connection = \Yii::$app->db;
        $rowP = $connection->createCommand('SELECT MAX('.$fieldP.') as midp FROM '.$this->tableName().' WHERE '.$fieldP.' LIKE "%'.$yearMonthP.'%"')->queryOne();
        if ($rowP['midp']) {
            return $rowP['midp'];
        } else {
            return 'false';
        }
    }

    public function getMaxStoreId($field) 
	{
		$connection = \Yii::$app->db;
        $row = $connection->createCommand('SELECT MAX('.$field.') as mid FROM '.$this->tableName())->queryOne();
        if ($row['mid']) {
            return $row['mid'];
        } else {
            return 'false';
        }
    }
	
	
	public function getLatestNumber($code, $field)
	{
		$parts = explode('-', date("d-m-Y"));
		$yearMonth = $parts[2] . $parts[1];
		$latestNumber = "";
        if ($this->getMaxId($yearMonth, $field) == 'false') {
            $latestNumber = $code . $yearMonth . '001';
		}
		else {
			$latestNumber = $this->getMaxId($yearMonth, $field);
			$id = (int) substr($latestNumber, strlen($code)) + 1;
			$latestNumber = $code . str_pad($id, 9, 0, STR_PAD_LEFT);
		}
		
		return $latestNumber;
	}

	public function getLatestNumberP($codeP, $fieldP)
	{
		$partsP = explode('-', date("d-m-Y"));
		$ppn = 'P';
		$yearMonthP = $ppn . $partsP[2] . $partsP[1];
		$latestNumberP = "";
        if ($this->getMaxIdP($yearMonthP, $fieldP) == 'false') {
            $latestNumberP = $codeP . $yearMonthP . '001';
		}
		else {
			$latestNumberP = $this->getMaxIdP($yearMonthP, $fieldP);
			$idP = (int) substr($latestNumberP, strlen($codeP)) + 1;
			$latestNumberP = $codeP . str_pad($idP, 9, 0, STR_PAD_LEFT);
		}
		
		return $latestNumberP;
	}

	public function getAbjadMonth($i){
		$abjad = array( '-', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
		return $abjad[$i];
	}

	

	public function getInvoiceCode($field)
	{
		$parts = explode('-', date("d-m-Y"));
		$p1 = substr($parts[2], (strlen($parts[2]) - 1) );
		$abj = $parts[1] <= 9 ? substr($parts[1], 1) : $parts[1];
		$p2 = $this->getAbjadMonth($abj);
		$yearMonth = $p1.$p2;
		$latestNumber = "";
        if ($this->getMaxId($yearMonth, $field) == 'false') {
            $latestNumber = $yearMonth . '001';
		}
		else {
			$latestNumber = $this->getMaxId($yearMonth, $field);
			$id = (int) substr($latestNumber, strlen($yearMonth)) + 1;
			$latestNumber = $yearMonth . str_pad($id, 3, 0, STR_PAD_LEFT);
		}
		return $latestNumber;
	}

	public function getInvoiceCodeP($fieldP)
	{
		$partsP = explode('-', date("d-m-Y"));
		$p1P = substr($partsP[2], (strlen($partsP[2]) - 1) );
		$abjP = $partsP[1] <= 9 ? substr($partsP[1], 1) : $partsP[1];
		$p2P = $this->getAbjadMonth($abjP);
		$ppn = 'P';
		$yearMonthP = $ppn.$p1P.$p2P;
		$latestNumberP = "";
        if ($this->getMaxIdP($yearMonthP, $fieldP) == 'false') {
            $latestNumberP = $yearMonthP . '001';
		}
		else {
			$latestNumberP = $this->getMaxIdP($yearMonthP, $fieldP);
			$idP = (int) substr($latestNumberP, strlen($yearMonthP)) + 1;
			$latestNumberP = $yearMonthP . str_pad($idP, 3, 0, STR_PAD_LEFT);
		}
		return $latestNumberP;
	}

	public function getStoreCode($code, $field)
	{
		$latestNumber = "";
        if ($this->getMaxStoreId($field) == 'false') {
            $latestNumber = $code.'00001';
		}
		else {
			$latestNumber = $this->getMaxStoreId($field);
			$id = (int) substr($latestNumber, strlen($code)) + 1;
			$latestNumber = $code.str_pad($id, 5, 0, STR_PAD_LEFT);
		}
		
		return $latestNumber;
	}

	public static function slugify($text)
	{
  		// replace non letter or digits by -
  		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

  		// transliterate
  		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  		// remove unwanted characters
  		$text = preg_replace('~[^-\w]+~', '', $text);

  		// trim
  		$text = trim($text, '-');

  		// remove duplicate -
  		$text = preg_replace('~-+~', '-', $text);

		// lowercase
  		$text = strtolower($text);

  		if (empty($text)) {
    		return 'n-a';
  		}

		return $text;
	}
}