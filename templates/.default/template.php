<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult['error'])):?>
	<div class="error_form"><?=$arResult['error'];?></div>
<?endif;?>
<section class="form">
	<form action="">
		<div class="form_top">
			<div class="title">
				Остались вопросы?
			</div>
			<div class="text">
				Задайте нам их и мы ответим
			</div>
		</div>
		<div class="form_bottom">
			<?foreach ($arResult["QUESTIONS"] as $key => $question):?>
				<?if($question["CODE"] == "PHONE"):?>
					<div class="input_name">
						<input <?if($question["REQUIRED"] == "Y"):?>required<?endif;?> type="text" name="<?=$question["CODE"];?>" placeholder="<?=$question["NAME"];?>">
					</div>
				<?else:?>
					<div class="input_email">
						<input <?if($question["REQUIRED"] == "Y"):?>required<?endif;?> type="email" name="<?=$question["CODE"];?>" placeholder="<?=$question["NAME"];?>">
					</div>
				<?endif;?>
			<?endforeach;?>
			<div class="submit">
				<input type="submit" value="<?=$arParams["BTN_TEXT"];?>">
				<div class="privacy">
					<input type="checkbox" name="privacy" id="privacy">
					<label for="privacy">Соглашаюсь с условиями передачи данных</label>
				</div>
			</div>
		</div>
	</form>
</section>