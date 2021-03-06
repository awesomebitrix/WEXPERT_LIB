<?
if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/* @var $this CBitrixComponent */

$reqParams = array('TYPE', 'CACHE_TAG', 'CACHE_TIME');
foreach ($reqParams as $param) {
	if (trim($arParams[ $param ]) == '') {
		ShowError('Need PARAM ' . $param . ', see .description.php!');
		return false;
	}
}

// сюда соберем все пункты меню
$arResult 		= array();

$CACHE_TIME		= (COption::GetOptionString("main", "component_cache_on", "Y") == "N")
					? 0
					: intval($arParams['CACHE_TIME']);
$CACHE_ID		= __FILE__ . '|' . serialize($arParams);
$cachePath		= 'php/' . ToLower($arParams['CACHE_TAG']) . '/';

$obMenuCache = new CPHPCache;

if ($obMenuCache->StartDataCache($CACHE_TIME, $CACHE_ID, $cachePath)) {

	CModule::IncludeModule('iblock');

	if (count($arParams["ORDER"]) > 0) {
		$arOrder = $arParams["ORDER"];
	} else {
		$arOrder = array("SORT" => "ASC");
	}

	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");
	if (count($arParams["FILTER"]) > 0) {
		$arFilter = array_merge($arFilter, $arParams["~FILTER"]);
	}

	if ($arParams["TYPE"] == 'elements') {

		$arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "NAME");
		if (count($arParams["SELECT"]) > 0) {
			$arSelect = array_merge($arSelect, $arParams["SELECT"]);
		}

		$rsItems = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
		while ($arItem = $rsItems->GetNext()) {
			$link_params = (count($arParams["SELECT"]) > 0) ? $arItem : array();

			$arResult[] = array(
				$arItem['NAME'],
				$arItem['DETAIL_PAGE_URL'],
				array(),
				$link_params
			);
		}
	} else if ($arParams["TYPE"] == 'sections') {

		$arSelect = array('ID', 'IBLOCK_ID', 'CODE', 'SECTION_PAGE_URL', 'NAME', 'DEPTH_LEVEL');
		if (count($arParams['SELECT']) > 0) {
			$arSelect = array_merge($arSelect, $arParams['SELECT']);
		}

		$rsSect = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect, $arNavStartParams);
		while ($arSect = $rsSect->GetNext()) {
			$link_params = (count($arParams["SELECT"]) > 0) ? $arSect : array();

			$arResult[] = array(
				$arSect['NAME'],
				$arSect['SECTION_PAGE_URL'],
				array(),
				$link_params
			);
		}
	}

	if (count($arResult) == 0) {
		$obMenuCache->AbortDataCache();
	} else {
		$obMenuCache->EndDataCache(array("arResult" => $arResult));
	}

} else {
	$arVars		= $obMenuCache->GetVars();
	$arResult	= $arVars["arResult"];
}


// возвращаем выборку для использования в файлах menu_ext
return $arResult;

?>