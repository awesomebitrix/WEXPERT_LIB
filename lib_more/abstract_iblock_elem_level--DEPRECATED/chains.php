<?
/**
 * Abstract Layer
 * Подсказки на выборки CIBlockElement::GetList
 *
 * @version 3.2 beta
 * @author hipot <hipot at wexpert dot ru>
 */


/**
 * Класс для работы с получением цепочек связанных элементов (через свойства привязка к элементам),
 * закрывает кольцевые цепочки
 *
 * @example
 * if ($arProps['series']['PROPERTY_TYPE'] == "E") {
 *		$obWeChainBuilder = new WeIblockElemLinkedChains();
 *		$obWeChainBuilder->init(2);
 *		$arProps['series']['CHAIN'] = $obWeChainBuilder->getChains_r($arProps['series']['VALUE']);
 * }
 */
class WeIblockElemLinkedChains
{
	/**
	 * Корень получаемой цепочки
	 * @var int
	 */
	private  $__topLevelId;

	/**
	 * Максимальный уровень вложенности
	 * @var int
	 */
	private  $__maxLevel;

	/**
	 * Текущий уровень, для итераций
	 * @var int
	 */
	private  $__level;
	
	/**
	 * Уже выбранные элементы, чтобы не выбирать их вновь (кеш)
	 * в ключе - ID элемента, в значении весь элемент с цепочкой ниже
	 * @var array
	 */
	private $__cacheItems;
	
	
	function __construct()
	{
		$this->__cacheItems = array();
	}

	/**
	 * Инициализация получения цепочки
	 * !! Нужно вызывать перед каждым вызовом getChains_r()
	 * @param int $maxLevel = 3 Максимальный уровень вложения (O)
	 */
	public function init($maxLevel = 3)
	{
		$this->__topLevelId = NULL;
		$this->__maxLevel = intval($maxLevel);
		$this->__level = 0;
	}

	/**
	 * Рекурсивный метод получения цепочек
	 * @param int $elementId корневой элемент для получения цепочки
	 * @param array $arSelect массив выбираемых полей, всегда выбираются
	 * "ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "NAME"
	 * Возвращает цепочку уровнем, указанным в init()
	 * @return array
	 */
	public function getChains_r($elementId, $arSelect = array())
	{
		$elementId = intval($elementId);
		
		if ($this->__topLevelId == $elementId || $this->__maxLevel == $this->__level) {
			return;
		}
		if (! $this->__topLevelId) {
			$this->__topLevelId = $elementId;
		}
		$this->__level++;

		// если элемент еще не выбирался
		if (! isset($this->__cacheItems[ $elementId ])) {
		
			$arSelectDef = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "NAME");
			$arSelect = array_merge($arSelect, $arSelectDef);
			$arFilter = array('ID' => intval($elementId));
			// QUERY 1
			$rsItems = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	
			if ($arItem = $rsItems->GetNext()) {
				// QUERY 2
				$db_props = CIBlockElement::GetProperty(
					$arItem["IBLOCK_ID"],
					$arItem['ID'],
					array("sort" => "asc"),
					array("EMPTY" => "N")
				);
				while ($ar_props = $db_props->Fetch()) {
					if ($ar_props['PROPERTY_TYPE'] == "E") {
						$ar_props['CHAIN'] = $this->getChains_r($ar_props['VALUE'], $arSelect);
					}
					if ($ar_props['PROPERTY_TYPE'] == "S" && isset($ar_props['VALUE']['TEXT'], $ar_props['VALUE']['TYPE'])) {
						$ar_props['VALUE']['TEXT'] = FormatText($ar_props['VALUE']['TEXT'], $ar_props['VALUE']['TYPE']);
					}
					if ($ar_props['PROPERTY_TYPE'] == 'F') {
						$ar_props['FILE_PARAMS'] = CFile::GetFileArray($ar_props['VALUE']);
					}
					if ($ar_props['MULTIPLE'] == "Y") {
						$arItem['PROPERTIES'][ $ar_props['CODE'] ][] = $ar_props;
					} else {
						$arItem['PROPERTIES'][ $ar_props['CODE'] ] = $ar_props;
					}
				}
			}
			$this->__cacheItems[ $elementId ] = $arItem;
			
		} else {
			$arItem = $this->__cacheItems[ $elementId ];
		}

		return $arItem;
	}
	
	/**
	 * Преобразование цепочки связанных элементов из массива в объекты абстрактного уровня
	 * @param array $arChain
	 * @return WeIblockElementItem
	 */
	static function chainArrayToChainObject($arChain)
	{
		return new WeIblockElementItem($arChain);
	}
}


?>