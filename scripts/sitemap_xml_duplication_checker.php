<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
 * ���������� ���� �� ����, � $path ��������� ���� � ����� �����, ��������� ���� � ��������.
 */

$path = $_SERVER["DOCUMENT_ROOT"]."/sitemap.xml";

if (file_exists($path)) {
	$xml = simplexml_load_file($path);
	$urlset = array();
	foreach($xml->url as $url){
		$urlset[] = (string)$url->loc;
	}
	sort($urlset);
	$duplication = array();
	for($i = 0; $i < count($urlset) - 1; $i++){
		if($urlset[$i] == $urlset[$i + 1] && !in_array($urlset[$i], $duplication)){
			$duplication[] = $urlset[$i];
			echo "�����: ".$urlset[$i]."<br />";
		}
	}

	if(count($duplication) == 0){
		echo "������ �� ����������<br />";
	}
} else {
	exit("Failed to open ".$path);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>