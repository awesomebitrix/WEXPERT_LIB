<?
/**
 * Установка обработчиков и их описание.
 * Желательно описание (определение класса и метода) делать отдельно от данного файла
 *
 * Т.е. в данном файле пишем AddEventHandler
 * а сам обработчик в файле /include/lib/handlers/siteevents.php
 */

// определяем глобальные константы, которые могут зависеть от $APPLICATION и $USER
AddEventHandler("main", "OnBeforeProlog", function () {

	global $APPLICATION, $USER;

	include __DIR__ . '/constants.php';

	// включаем генератор ORM
	if ($APPLICATION->GetCurPage() == '/bitrix/admin/perfmon_tables.php' && $_GET['orm'] != 'y') {
		LocalRedirect( $APPLICATION->GetCurPageParam("orm=y") );
	}

});

// проставляем id инфоблоков в административном меню
AddEventHandler("main", "OnBuildGlobalMenu", function (&$aGlobalMenu, &$aModuleMenu) {

	if (! $GLOBALS['USER']->IsAdmin() || !defined("ADMIN_SECTION")) {
		return;
	}
	foreach ($aModuleMenu as $k => $arMenu) {
		if ($arMenu['icon'] != 'iblock_menu_icon_types') {
			continue;
		}
		foreach ($arMenu['items'] as $i => $item) {
			$arEx = explode('/', $item['items_id']);
			$aModuleMenu[$k]['items'][$i]['text'] .= ' [' . $arEx[2] . ']';
		}
	}

});


// верхняя постраничка в админке в лентах
AddEventHandler("main", "OnAdminListDisplay", function ($this_al) {

	/* @var $this_al CAdminList */
	echo $this_al->sNavText;

});

// очищаем настройки формы по-умолчанию для всех админов
// @see http://hipot.wexpert.ru/Codex/form_iblock_element_settings/
AddEventHandler('main', 'OnEndBufferContent', function (&$content) {
	if (count($_POST['p']) <= 0) {
		return;
	}

	global $APPLICATION, $DB, $CACHE_MANAGER;

	$pCfg 		= array_shift($_POST['p']);

	if ($APPLICATION->GetCurPage() != '/bitrix/admin/user_options.php'
		|| $pCfg['c'] != 'form' || $pCfg['d'] != 'Y'
		|| !preg_match('#^form_((section)|(element))_[0-9]+$#', $pCfg['n'])
	) {
		return;
	}

	$DB->Query("DELETE FROM b_user_option WHERE CATEGORY = 'form' AND NAME = '" . $pCfg['n'] . "' AND COMMON = 'N'");
	$CACHE_MANAGER->cleanDir("user_option");
});


?>