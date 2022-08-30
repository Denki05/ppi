<?php

namespace common\models;
use common\components\ErrorGenerateComponent;
use Yii;

/**
 * This is the model class for table "tbl_product".
 *
 * @property int $id
 * @property string $product_material_code
 * @property string $product_material_name
 * @property string $product_code
 * @property string $product_name
 * @property int $factory_id
 * @property int $brand_id
 * @property int $category_id
 * @property int $product_is_new
 * @property int $original_brand_id
 * @property int $searah_id
 * @property string $product_gender
 * @property string $product_cost_price
 * @property string $product_sell_price
 * @property string $product_web_image
 * @property string $product_status
 * @property string $created_on
 * @property string $updated_on
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property TblIndentItem[] $tblIndentItems
 * @property TblBrand $brand
 * @property TblBrand $originalBrand
 * @property TblCategory $category
 * @property TblFactory $factory
 * @property TblSearah $searah
 */
class Product extends \common\models\MasterModel
{
    const PRODUCT_GENDER_MALE = 'm';
    const PRODUCT_GENDER_FEMALE = 'f';
    const PRODUCT_GENDER_NEUTRAL = 'neutral';
    const PRODUCT_STATUS_ACTIVE = 'active';
    const PRODUCT_STATUS_INACTIVE = 'inactive';
    const CURRENCY_RUPIAH = 'rupiah';
    const CURRENCY_DOLAR = 'dolar';
    const PRODUCT_TYPE_GCF = '10';
    const PRODUCT_TYPE_SENSES = '20';
    const PRODUCT_TYPE_PROJECT_FF = '30';
    const PRODUCT_TYPE_PROJECT_NON_FF = '40';
    const PRODUCT_TYPE_DUFTNOL = '50';
    const PRODUCT_TYPE_SELUZ = '60';
    const PRODUCT_TYPE_LAIN = '90';

    public $image, $mode, $factory_name, $brand_name, $category_name, $productLabel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'product_code', 'product_name', 'factory_id', 'brand_id', 'original_brand_id', 'searah_id', 'product_gender', 'product_cost_price', 'product_sell_price', 'product_status', 'product_type'], 'required'],
            [[ 'product_is_new', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['product_gender', 'product_web_image', 'product_status', 'product_type'], 'string'],
            [['product_cost_price', 'product_sell_price'], 'number'],
            [['created_on', 'updated_on', 'factory_id', 'searah_id','brand_id', 'category_id', 'original_brand_id', 'mode', 'factory_name', 'brand_name', 'category_name', 'productLabel', 'product_type'], 'safe'],
            [['image'],'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024000, 'skipOnEmpty' => true],
            [['product_material_code', 'product_material_name', 'product_code', 'product_name'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['original_brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['original_brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['factory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factory::className(), 'targetAttribute' => ['factory_id' => 'id']],
            [['searah_id'], 'exist', 'skipOnError' => true, 'targetClass' => Searah::className(), 'targetAttribute' => ['searah_id' => 'id']],
            [['product_name', 'product_code', 'brand_id', 'category_id'], 'uniqueRow'],
        ];
    }

    public function uniqueRow($attribute, $params)
    {
        if($this->mode == 'save'){
            if(!empty($this->category_id)){
                $product = Product::find()->where('product_name=:name AND product_code=:code AND brand_id=:b AND category_id=:c', [':name' => $this->product_name, ':code' => $this->product_code, ':b' => $this->brand_id, ':c' => $this->category_id])->one();
                if (!empty($product)){
                        $this->addError('product_name', 'Barang dengan nama '.$this->product_name.' Sudah Ada!!');
                        $this->addError('product_code', 'Barang dengan kode '.$this->product_code.' Sudah Ada!!');
                        $this->addError('category_id', 'Barang dengan kategori '.$this->category->category_name.' Sudah Ada!!');
                        $this->addError('brand_id', 'Barang dengan Merek '.$this->brand->brand_name.' Sudah Ada!!');
                    }
            }
            else{
                $product = Product::find()->where('product_name=:name AND product_code=:code AND brand_id=:b', [':name' => $this->product_name, ':code' => $this->product_code, ':b' => $this->brand_id])->one();
                if (!empty($product)){
                    $this->addError('product_name', 'Barang dengan nama '.$this->product_name.' Sudah Ada!!');
                    $this->addError('product_code', 'Barang dengan kode '.$this->product_code.' Sudah Ada!!');
                    $this->addError('brand_id', 'Barang dengan Merek '.$this->brand->brand_name.' Sudah Ada!!');
                }
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_material_code' => 'Kode Bahan',
            'product_material_name' => 'Nama Bahan',
            'product_code' => 'Kode Barang',
            'product_name' => 'Nama Barang',
            'factory_id' => 'Pabrik',
            'brand_id' => 'Merek',
            'category_id' => 'Kategori',
            'product_is_new' => 'Barang Baru',
            'original_brand_id' => 'Merek Original',
            'searah_id' => 'Searah',
            'product_gender' => 'Gender',
            'product_cost_price' => 'Harga Beli',
            'product_sell_price' => 'Harga Jual',
            'product_web_image' => 'Web',
            'image' => 'Upload',
            'product_status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_deleted' => 'Is Deleted',
            'mode' => 'Mode',
            'factory_name' => 'Pabrik',
            'brand_name' => 'Merek',
            'category_name' => 'Kategori',
            'productLabel' => 'Product',
            'product_type' => 'Tipe Barang'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndentItems()
    {
        return $this->hasMany(IndentItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginalBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'original_brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactory()
    {
        return $this->hasOne(Factory::className(), ['id' => 'factory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearah()
    {
        return $this->hasOne(Searah::className(), ['id' => 'searah_id']);
    }

    public function getProductStatus($status='')
    {
        $statuses = [
           self::PRODUCT_STATUS_ACTIVE => 'Active',
           self::PRODUCT_STATUS_INACTIVE => 'Tidak Active',
        ];

        return empty($status) ? $statuses : (isset($statuses[$status]) ? $statuses[$status] : "");
    }

    public function getProductGender($gender='')
    {
        $genders = [
           self::PRODUCT_GENDER_MALE => 'Laki - Laki',
           self::PRODUCT_GENDER_FEMALE => 'Perempuan',
           self::PRODUCT_GENDER_NEUTRAL => 'Netral',
        ];

        return empty($gender) ? $genders : (isset($genders[$gender]) ? $genders[$gender] : "");
    }

    public function getProductType($type='')
    {
        $types = array(
            self::PRODUCT_TYPE_GCF => 'GCF',
            self::PRODUCT_TYPE_SENSES => 'Senses',
            self::PRODUCT_TYPE_PROJECT_FF => 'Project FF',
            self::PRODUCT_TYPE_PROJECT_NON_FF => 'Project Non FF',
            self::PRODUCT_TYPE_DUFTNOL => 'Duftnol',
            self::PRODUCT_TYPE_SELUZ => 'Seluz',
            self::PRODUCT_TYPE_LAIN => 'Lain',
        );

        return $type == '' ? $types : (isset($types[$type]) ? $types[$type] : "");
    }

    public function setNewFactory($name){
        $factory = Factory::find()->where('id=:id', [':id' => $name])->one();
        
        $id = 0;
        if(empty($factory)){
            $item = new Factory();
            $item->factory_name = $name;
            if(!$item->save()){
                $errorString = ErrorGenerateComponent::generateErrorLabels($item->getErrors());
                return array('success' => false, 'message' => 'Gagal menyimpan factory baru karena kesalahan berikut: '.$errorString);
            }
            else{
                $id = $item->id;
            }
        }
        else{
            $id = $factory->id;
        }

        return array('success' => true, 'message' => '', 'id' => $id);
    }

    public function setNewSearah($name){
        $searah = Searah::find()->where('id=:id', [':id' => $name])->one();
        
        $id = 0;
        if(empty($searah)){
            $item = new Searah();
            $item->searah_name = $name;
            if(!$item->save()){
                $errorString = ErrorGenerateComponent::generateErrorLabels($item->getErrors());
                return array('success' => false, 'message' => 'Gagal menyimpan searah baru karena kesalahan berikut: '.$errorString);
            }
            else{
                $id = $item->id;
            }
        }
        else{
            $id = $searah->id;
        }

        return array('success' => true, 'message' => '', 'id' => $id);
    }

    public function setNewBrand($name){
        $brand = Brand::find()->where('id=:id', [':id' => $name])->one();

        $id = 0;
        if(empty($brand)){
            $item = new Brand();
            $item->brand_name = $name;
            $item->brand_type = 'ppi';
            if(!$item->save()){
                $errorString = ErrorGenerateComponent::generateErrorLabels($item->getErrors());
                return array('success' => false, 'message' => 'Gagal menyimpan Merek baru karena kesalahan berikut: '.$errorString);
            }
            else{
                $id = $item->id;
            }
        }
        else{
            $id = $brand->id;
        }

        return array('success' => true, 'message' => '', 'id' => $id);
    }

    public function setNewCategory($name, $brandId){
        $category = Category::find()->where('id=:id', [':id' => $name])->one();
        
        $id = 0;
        if(empty($category)){
            $item = new Category();
            $item->category_name = $name;
            $item->brand_id = $brandId;
            if(!$item->save()){
                $errorString = ErrorGenerateComponent::generateErrorLabels($item->getErrors());
                return array('success' => false, 'message' => 'Gagal menyimpan kategori baru karena kesalahan berikut: '.$errorString);
            }
            else{
                $id = $item->id;
            }
        }
        else{
            $id = $category->id;
        }

        return array('success' => true, 'message' => '', 'id' => $id);
    }

    public function setNewOriginalBrand($name){
        $brand = Brand::find()->where('id=:id', [':id' => $name])->one();
        
        $id = 0;
        if(empty($brand)){
            $item = new Brand();
            $item->brand_name = $name;
            $item->brand_type = 'original';
            if(!$item->save()){
                $errorString = ErrorGenerateComponent::generateErrorLabels($item->getErrors());
                return array('success' => false, 'message' => 'Gagal menyimpan merek original baru karena kesalahan berikut: '.$errorString);
                
            }
            else{
                $id = $item->id;
            }
        }
        else{
            $id = $brand->id;
        }

        return array('success' => true, 'message' => '', 'id' => $id);
    }

    public static function getCurrencyLabel($currency='')
    {
        $currencies = array(
            self::CURRENCY_RUPIAH => 'Rp',
            self::CURRENCY_DOLAR => '$',
        );
        
        return $currency == '' ? $currencies : (isset($currencies[$currency]) ? $currencies[$currency] : "");
    }

    public function import($fileName)
    {
        $fileType = 'Excel2007';
        //$fileName = 'uploads/' . $model->file->name;

        $objPHPExcel = new \PHPExcel();
        //$objReader = \PHPExcel_IOFactory::createReader($fileType);
        $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
        
        $activeSheet = $objPHPExcel->getActiveSheet();
        
        //$arrCats = (new Category)->trim($activeSheet, 'I');
                        
        $num = array('success' => 0, 'failed' => 0);
        
        $lastRow = $activeSheet->getHighestRow();
        // print_r($lastRow);
        // exit();
        $string = "";
        for($i=2;$i<=$lastRow;$i++) {
            $factoryName = $activeSheet->getCell('E'.$i)->getCalculatedValue();
            $brandName = $activeSheet->getCell('G'.$i)->getCalculatedValue();
            $categoryName = $activeSheet->getCell('H'.$i)->getCalculatedValue() !== '-' ? $activeSheet->getCell('H'.$i)->getCalculatedValue() : '';
            $originalName = $activeSheet->getCell('J'.$i)->getCalculatedValue();
            $searahName = $activeSheet->getCell('K'.$i)->getCalculatedValue();
            $new = empty($activeSheet->getCell('I'.$i)->getCalculatedValue()) ? 0 : 1;

            $materialCode = $activeSheet->getCell('A'.$i)->getCalculatedValue();
            $materialName = $activeSheet->getCell('B'.$i)->getCalculatedValue();
            $productCode = $activeSheet->getCell('C'.$i)->getCalculatedValue();
            $productName = $activeSheet->getCell('D'.$i)->getCalculatedValue();
            $gender = $activeSheet->getCell('L'.$i)->getCalculatedValue() === 'F/M' ? 'neutral' : $activeSheet->getCell('L'.$i)->getCalculatedValue();
            $costPrice = $activeSheet->getCell('N'.$i)->getCalculatedValue();
            $sellPrice = $activeSheet->getCell('O'.$i)->getCalculatedValue();
            $web = $activeSheet->getCell('P'.$i)->getCalculatedValue();
            $status = $activeSheet->getCell('Q'.$i)->getCalculatedValue();

            $brand = Brand::find()->where('brand_name=:name', [':name' => $brandName])->one();
            if(empty($brand)){
                $newBrand = new Brand();
                $newBrand->brand_name = $brandName;
                $newBrand->brand_type = 'ppi';
                if(!$newBrand->save()){
                    print_r($newBrand->getErrors());
                    exit();
                    break;
                }
                else{
                    $brandId = $newBrand->id;
                }
            }
            else{
                $brandId = $brand->id;
            }

            
            if(!empty($categoryName)){

                $category = Category::find()->where('category_name=:name', [':name' => $categoryName])->one();
                if(empty($category)){
                    $newCategory = new Category();
                    $newCategory->category_name = $categoryName;
                    $newCategory->brand_id = $brandId;
                    if(!$newCategory->save()){
                        print_r($newCategory->getErrors());
                        exit();
                        break;
                    }
                    else{
                        $categoryId = $newCategory->id;
                    }
                }
                else{
                    $categorydId = $category->id;
                }
            }

            $factory = Factory::find()->where('factory_name=:name', [':name' => $factoryName])->one();
            if(empty($factory)){
                $newFactory = new Factory();
                $newFactory->factory_name = $factoryName;
                if(!$newFactory->save()){
                    print_r($newFactory->getErrors());
                    exit();
                    break;
                }
                else{
                    $factoryId = $newFactory->id;
                }
            }
            else{
                $factoryId = $factory->id;
            }

            $searah = Searah::find()->where('searah_name=:name', [':name' => $searahName])->one();
            if(empty($searah)){
                $newSearah = new Searah();
                $newSearah->searah_name = $searahName;
                if(!$newSearah->save()){
                    print_r($newSearah->getErrors());
                    exit();
                    break;
                }
                else{
                    $searahId = $newSearah->id;
                }
            }
            else{
                $searahId = $searah->id;
            }

            $original = Brand::find()->where('brand_name=:name', [':name' => $originalName])->one();
            if(empty($original)){
                $newOriginal = new Brand();
                $newOriginal->brand_name = $originalName;
                $newOriginal->brand_type = 'original';
                if(!$newOriginal->save()){
                    print_r($newOriginal->getErrors());
                    exit();
                    break;
                }
                else{
                    $originalId = $newOriginal->id;
                }
            }
            else{
                $originalId = $original->id;
            }

            $product = new Product;
            if(!empty($materialCode))
                $product->product_material_code = (String) $materialCode;
            
            if(!empty($materialName))
                $product->product_material_name = $materialName;
            
            $product->product_code = (String) $productCode;
            $product->product_name = $productName;
            $product->factory_id = $factoryId;
            $product->brand_id = $brandId;
            
            if(!empty($categoryName))
                $product->category_id = $categoryId;
            
            $product->product_is_new = empty($new) ? 0 : 1;
            $product->original_brand_id = $originalId;
            $product->searah_id = $searahId;
            $product->product_gender = $gender;
            $product->product_cost_price = $costPrice;
            $product->product_sell_price = $sellPrice;
            $product->product_web_image = $web === '-' ? '' : $web;
            $product->product_status = empty($status) ? 'inactive' : 'active';

            if(!$product->save()){
                    print_r($product->getErrors());
                    exit();
                    break;
            }

            
        }
        
        //$num['errorMessage'] = $string;

        //return $num;
    }

    public function getProductName(){
        return $this->product_code.' -- '.$this->product_name;
    } 
}
