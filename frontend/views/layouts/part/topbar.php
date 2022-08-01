<?php
use yii\helpers\Url;
?>

<nav class="navbar navbar-static-top <?php //echo isset($this->page_caption) ? "" : "white-bg";?> role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
	</div>
	<ul class="nav navbar-top-links navbar-right">
		<li>
			<a href="<?=Url::home();?>site/logout">
				<i class="fa fa-sign-out"></i> Log out
			</a>
		</li>
	</ul>

</nav>