<?php

namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\ComissionPay;
use common\components\CurrencyComponent;
/**
 * ComissionPaySearch represents the model behind the search form of `common\models\ComissionPay`.
 */
class ComissionReport extends ComissionPay
{
    public $employee_name, $start_date, $end_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'salesman_id', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['comission_pay_date', 'comission_pay_periode', 'created_on', 'updated_on', 'employee_name', 'start_date', 'end_date'], 'safe'],
            [['comission_pay_exchange_rate', 'comission_pay_value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $sort = true)
    {
        $sql = "
            SELECT 
                b.id,
                b.employee_name, 
                a.comission_pay_periode,
                a.comission_pay_exchange_rate,
                a.comission_pay_value,
                YEAR(a.comission_pay_date) AS tahun 
            FROM 
                tbl_comission_pay a
            CROSS JOIN 
                tbl_employee b 
            where a.is_deleted = 0
        ";

        if (!empty($this->comission_pay_periode))
            $sql .= " AND a.comission_pay_periode = '".$this->comission_pay_periode."'";
        if (!empty($this->employee_name))
            $sql .= " AND b.employee_name LIKE '%".$this->employee_name."%'";

        $sql .= "group by b.employee_name, a.comission_pay_periode, year(a.comission_pay_date)";

        $count = count(Yii::$app->db->createCommand($sql)->queryAll());
        // add conditions that should always apply here

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            ],
            // 'pagination' =>[
            //     'pageSize' => (new \common\models\Setting)->getSettingValueByName('record_per_page')
            // ],
            'sort' => $sort ? [
                'attributes' => [
                    'employee_name' => [
                        'asc' => ['b.employee_name' => SORT_ASC],
                        'desc' => ['b.employee_name' => SORT_DESC],
                    ],
                    'comission_pay_periode' => [
                        'asc' => ['a.comission_pay_periode' => SORT_ASC],
                        'desc' => ['b.comission_pay_periode' => SORT_DESC],
                    ],
                    'tahun',
                ],
                'defaultOrder' => ['tahun' => SORT_ASC, 'comission_pay_periode' => SORT_ASC],
            ] : false,
        ]);
        return $dataProvider;
    }

    public static function getExcel($provider)
    {
        date_default_timezone_set('Asia/Jakarta');
        
        $firstCol = "A";
        $endCol = "E";
    
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:E1")->setCellValue('A1', 'LAPORAN KOMISI SALES')->getStyle('A1')->applyFromArray(array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER), 'font' => array('bold' => 'true', 'size' => '18')));

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Nama Sales');
        $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Periode');
        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Jumlah Komisi');
        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Jumlah Komisi Yang Sudah Cair');
        $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Jumlah Komisi Yang Belum Cair');

        $rowNum = 4;
        foreach($provider as $item) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNum, $item['employee_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNum,  (new ComissionPay)->getPeriodeLabel($item['comission_pay_periode']));
            
            $temp = (new ComissionPay)->getComissionPaid($item['id'], $item['comission_pay_periode'], $item['tahun']) + (new ComissionPay)->getComissionNotPay($item['id'], $item['comission_pay_periode'], $item['tahun']);
            if($item['comission_pay_exchange_rate'] > 1)
                $total = $temp !== 0 ? CurrencyComponent::formatMoney($temp) : "-";
            else
                $total =  $temp !== 0 ? CurrencyComponent::formatMoney($temp,0,',','.', Product::CURRENCY_DOLAR) : "-";

            $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowNum,  $total);

            $temp2 = (new ComissionPay)->getComissionPaid($item['id'], $item['comission_pay_periode'], $item['tahun']);
            if($item['comission_pay_exchange_rate'] > 1)
                $paid = $temp2 !== 0 ? CurrencyComponent::formatMoney($temp2) : "-";
            else
                $paid = $temp2 !== 0 ? CurrencyComponent::formatMoney($temp2,0,',','.', Product::CURRENCY_DOLAR) : "-";
            
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNum,  $paid);
            
            $temp3 = (new ComissionPay)->getComissionNotPay($item['id'], $item['comission_pay_periode'], $item['tahun']);
            if($item['comission_pay_exchange_rate'] > 1)
                $noPaid = $temp3 !== 0 ? CurrencyComponent::formatMoney($temp3) : "-";
            else
                $noPaid = $temp3 !== 0 ? CurrencyComponent::formatMoney($temp3,0,',','.', Product::CURRENCY_DOLAR) : "-";

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNum, $noPaid);
            
            $rowNum++;
        }

        //auto fit the cell's width with the content
        foreach(range($firstCol, $endCol) as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Save a xls file
        $filename = "laporan_komisi_sales_".date("d-m-Y-His").".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        
        die();
    }
}
