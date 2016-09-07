<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arTypesEx = array();

CModule::IncludeModule("fileman");
CMedialib::Init();

$rsCol = CMedialibCollection::GetList(array(
	'arOrder'	=> array('ML_TYPE' => 'ASC')
));
$arColIndexed = array();
foreach ($rsCol as $ar) {
	$arColIndexed[ $ar['ID'] ] = $ar;
}

if (! function_exists('_getCollectionParentsNames')) {
	function _getCollectionParentsNames($parentId, $arColIndexed)
	{
		$name = '';
		if (! isset($arColIndexed[ $parentId ])) {
			return $name;
		}
		$name = $arColIndexed[ $parentId ]['NAME'] . ' / ';
		if ($arColIndexed[ $parentId ]['PARENT_ID'] > 0) {
			$name = _getCollectionParentsNames($arColIndexed[ $parentId ]['PARENT_ID'], $arColIndexed) . $name;
		}
		return $name;
	}
}

foreach ($rsCol as $ar) {
	$arTypesEx[ $ar['ID'] ] = (_getCollectionParentsNames($ar['PARENT_ID'], $arColIndexed)) . $ar['NAME'] . ' [' . $ar['ID'] . '] ';
}
uasort($arTypesEx, create_function('$a, $b', '
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? 1 : -1;'));
unset($rsCol, $arColIndexed);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"COLLECTION_IDS"	=> array(
			"PARENT"		=> "BASE",
			"NAME"			=> "Коллекции(я)",
			"TYPE"			=> "LIST",
			"VALUES"		=> $arTypesEx,
			"DEFAULT"		=> false,
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE"			=> "Y",
			"REFRESH"			=> 'N',
			"COLS"				=> 8,
			"ROWS"				=> 8
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
?>
