<?php
use yii\helpers\Url;
use frontend\components\MenuComponent;
// use frontend\components\AccessComponent;

$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;
$currentModule = Yii::$app->controller->module->id;
?>

<!-- side bar -->

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <?php foreach(MenuComponent::getArrOfMenu($currentController, $currentAction, $currentModule) as $menu):?>
            <?php if ($menu['visible']):?>
            <li class="<?php echo isset($menu['active']) && $menu['active'] ? "active" : "";?> nav-item">
                <a href="<?php echo Url::base()."/".$menu['url'];?>">
                    <i class="<?php echo $menu['icon'];?>"></i>
                    <span class="menu-title" data-i18n="nav.category.admin-panels"><?php echo $menu['label'];?></span>
                </a>
                <?php if (isset($menu['items']) && !empty($menu['items'])):?>
                <ul class="menu-content">
                    <?php foreach($menu['items'] as $secondMenu):?>
                    <?php if ($secondMenu['visible']):?>
                    <li class="<?php echo isset($secondMenu['active']) && $secondMenu['active'] ? "active" : "";?>">
                        <a class="menu-item" href="<?php echo Url::base()."/".$secondMenu['url'];?>">
                            <i class="<?php echo $secondMenu['icon']; ?>"></i>
                            <span><?php echo $secondMenu['label'];?></span>
                        </a>
                        <?php if (isset($secondMenu['items']) && !empty($secondMenu['items'])):?>
                        <ul class="menu-content">
                            <?php foreach($secondMenu['items'] as $thirdMenu):?>
                            <?php if ($thirdMenu['visible']):?>
                            <li class="<?php echo isset($thirdMenu['active']) && $thirdMenu['active'] ? "active" : "";?>">
                                <a class="menu-item" href="<?php echo Url::base()."/".$thirdMenu['url'];?>">
                                    <span data-i18n="nav.project.project_summary"><?php echo $thirdMenu['label'];?></span>
                                </a>
                            </li>
                            <?php endif;?>
                            <?php endforeach;?>
                        </ul>
                        <?php endif;?>
                    </li>
                    <?php endif;?>
                    <?php endforeach;?>
                </ul>
                <?php endif;?>
            </li>
            <?php endif;?>
            <?php endforeach;?>
        </ul>
    </div>
</div>