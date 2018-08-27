<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				<h1>Кампании</h1>
				<?php echo $campaignName; ?>
				
				<h1>Минус-слова</h1>
				<?php foreach ($negativeKeywords->Items as $value){
					echo $value."<br />";
				}?>
				
			</div>
        </div>
    </div>
</div>
