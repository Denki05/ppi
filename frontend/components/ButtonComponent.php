<?php
namespace frontend\components;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use yii\helpers\Html;

class ButtonComponent extends Controller
{
    public static function getAddButton($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'create')) {
            $stringParam = '?';
            if (!empty($params)) {
                $i = 0;
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            // dd($stringParam);
            }
            return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'\';" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-plus"></i></button>';
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getCheckButton($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'check')) {
            $stringParam = '?';
            if (!empty($params)) {
                $i = 0;
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            // dd($stringParam);
            }
            return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/check'.$stringParam.'\';" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-check"></i></button>';
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getExportPdf($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'exportpdf')) {
            $stringParam = '?';
            if (!empty($params)) {
                $i = 0;
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            }
            // return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/exportpdf'.$stringParam.'\';" type="button" class="btn btn-outline-secondary btn-sm" id="pdf_btn" target="_blank" title="Export Pdf"><i class="la la-file-pdf-o"></i></button>';
            return Html::a('<i class="la la-file-pdf-o"></i>',['exportpdf'],['class'=>'btn btn-outline-secondary btn-sm', 'id' => 'pdf_btn', 'target' => '_blank', 'title' => 'export PDF']);
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getExportExcel($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'exportexcel')) {
            $stringParam = '?';
            if (!empty($params)) {
                $i = 0;
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            }
            // return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/exportexcel'.$stringParam.'\';" type="button" class="btn btn-outline-info btn-sm" title="Export Excel"><i class="la la-file-excel-o"></i></button>';
            return Html::a('<i class="la la-file-excel-o"></i>',['exportexcel'],['class'=>'btn btn-outline-info btn-sm', 'id' => 'excel_btn', 'target' => '_blank', 'title' => 'export EXCEL']);
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getBackUpButton($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'create')) {
            $stringParam = '';
            if (!empty($params)) {
                $i = 0;
                $stringParam = '?';
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            }
            return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'\';" type="button" class="btn btn-outline-secondary btn-sm" title="Backup"><i class="la la-hdd-o"></i></button>';
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getImportButton($params='')
    {
        if (AccessComponent::hasAccess(Yii::$app->controller->module->id, Yii::$app->controller->id, 'import')) {
            $stringParam = '?';
            if (!empty($params)) {
                $i = 0;
                foreach($params as $key => $param) {
                    $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
                }
            }
            return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/import'.$stringParam.'\';" type="button" class="btn btn-outline-secondary btn-sm"><i class="la la-cloud-upload"></i></button>';
            // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/create'.$stringParam.'">
            //     <button type="button" class="btn btn-sm btn-outline-primary btn-min-width mr-1 mb-1 mt-1" title="'.LabelComponent::NEW_BUTTON.'"><i class="la la-plus"></i></button>
            // </a>';
        }
        return "";
    }

    public static function getBackButton($params='')
    {
        $stringParam = '?';
        if (!empty($params)) {
            $i = 0;
            foreach($params as $key => $param) {
                $stringParam .= $i > 0 ? '&'.$key.'='.$param : $key.'='.$param;
            }
        }
        return '<button href="javascript:;" onclick="window.location.href = \''.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index'.$stringParam.'\';" type="button" class="btn btn-outline-secondary btn-sm"><i class="la la-reply"></i></button>';
        // return '<a href="'.Url::base().'/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index'.$stringParam.'">
        //     <button type="button" class="btn bg-grey waves-effect" title="'.LabelComponent::BACK_BUTTON.'"><i class="material-icons">reply</i></button>
        // </a>';
    }

    public static function getSaveButton()
    {
        return '<button type="button" href="javascript:;" onclick="submitbutton(\'application.apply\')" class="btn btn-outline-success btn-sm" title="'.LabelComponent::SAVE_BUTTON.'"><i class="la la-save"></i></button>';
    }
}