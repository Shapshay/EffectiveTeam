<div class="clear"></div>
<div id="words_cloud" style="width:600px; height:400px; border: 1px solid #ccc;"></div>
<div class="clear"></div>
<form method="post" enctype="multipart/form-data" name="s_s" id="SearchForm">
    <fieldset>
<p>
    <label>Слово или словосочетание</label>
    <input class="text-input medium-input" type="text" name="word" id="word" value="{SEARCH_WORD}" maxlength="200" />
<div class="hint">Введите слово или словосочетание характеризующие задачу</div>
</p>
<p><button type="button" onclick="checkSearchForm();" name="search" class="button">Найти</button></p>
    </fieldset>

    <div class="clear"></div><!-- End .clear -->
</form>
<hr align="left" width="600" noshade color="#459300" size="1">

{SEARCH_SHOW}