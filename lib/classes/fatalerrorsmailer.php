<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Модуль отображает Fatal Error в дружелюбном для пользователя виде и отправляет
 * сообщение разработчику сайта.
 * Причем отправка происходит только для НЕ администраторов сайта, т.е. под админом эти
 * ошибки не отправляются на емейл.
 *
 * Битрикс тоже использует такой метод завершения работы, поэтому важно чтобы
 * данный регистатор окончания скрипта был объявлен первым. init.php для этого подходит.
 *
 * @uses
 * Для емейлов необходим тип почтового шаблона DEBUG_MESSAGE с полями:
 * TO, SUBJECT, MESSAGE
 * запуск в init.php
 * FatalErrorsMailer::init();
 *
 * @author hipot AT ya DOT ru, 2013
 * @version 0.1 alpha
 */
class FatalErrorsMailer
{
	/**
	 * Разработчики, получатели сообщения об ошибке
	 * @var string
	 */
	const DEVELOPER_EMAIL_TO = 'hipot@wexpert.ru, crus@wexpert.ru';

	/**
	 * ССылка для пользователя с фразы "сообщить нам об ошибке"
	 * @var string
	 */
	const USER_SEND_ERROR_LINK = 'mailto:support@wexpert.ru?subject=Ошибка+PHP';

	/**
	 * первое событие в этапе постоения страницы, где определен пользователь
	 * @see http://dev.1c-bitrix.ru/api_help/main/general/pageplan.php
	 */
	static function init()
	{
		ini_set('display_errors', false);
		register_shutdown_function(array('FatalErrorsMailer', 'catch_fatal_error'));
	}

	static function catch_fatal_error()
	{
		global $USER;
		$last_error =  error_get_last();

		// see http://www.php.net/manual/ru/errorfunc.constants.php
		$bFatalError = isset($last_error['type'])
			&& in_array($last_error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR)
		);
		if (! $bFatalError) {
			return;
		}

		if (! $USER->IsAdmin() || !in_array($USER->GetLogin(), array('hipot', 'crus'))) {
			self::mailErrorToDevs($last_error);
		}
		self::showErrorToUser($last_error);
	}

	static function mailErrorToDevs($last_error)
	{
		$last_error['dateTime'] = date('d.m.Y G:i:s');

		$html = 'Данные об ошибке' . "\n";
		foreach ($last_error as $k => $v) {
			$html .= $k . ': ' . $v . "\n";
		}

		$html .= "\n\nДополнительные переменные \n" . "\n";
		$html .= '$_REQUEST '. print_r($_REQUEST, true). "\n". "\n";
		$html .= '$_SERVER '. print_r($_SERVER, true). "\n". "\n";
		$html .= '$_SESSION '. print_r($_SESSION, true). "\n". "\n";

		$arFields = array(
			'TO'		=> self::DEVELOPER_EMAIL_TO,
			'SUBJECT'	=> $_SERVER['HTTP_HOST'] . ' Фатальная ошибка ' . $last_error['file'] . ' : ' . $last_error['line'],
			'MESSAGE'	=> $html
		);
		$evt = new CEvent();
		$evt->SendImmediate('DEBUG_MESSAGE', 's1', $arFields);
		//$evt->Send('DEBUG_MESSAGE', 's1', $arFields);
	}

	static function showErrorToUser($last_error)
	{
		global $USER;

		if ($USER->IsAdmin()) {
			?>
			<span class="errortext" style="color:red;">
				<b>Fatal error:</b> <?=$last_error['message']?> in <?=$last_error['file']?> on line <?=$last_error['line']?>
			</span>
			<?
		} else {
			$_SESSION['WE_ERROR_500_REFERER_URL'] = $_SERVER['REQUEST_URI'];

			LocalRedirect('/error/500.php');
			?>
			<noindex>
				<style>
				.fatal-error {font-size:130%; font-family:Arial, sans-serif; padding:10px; background:#fff; color:#000; clear:both;}
				.fatal-error * {font-family:Arial, sans-serif;}
				.fatal-error .error-raw {background:#c7c7c7; padding:8px; font-size:11px; margin:5px 0px; font-family:monospace;}
				.fatal-error .has-error {font-size:110%; padding:0px 0px 10px 0px; font-weight:bold; color:red;}
				.fatal-error .we-know {font-weight:bold;}
				.fatal-error img {float:left; margin:0px 6px 6px 0px;}
				</style>
				<br clear="all" />
				<div class="fatal-error">
					<img src="data:image/jpg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABVAAD/4QMraHR0cDov
						L25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENl
						aGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4
						OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6
						NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5
						OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHht
						bG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6
						Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUu
						Y29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBo
						b3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjVCMTkyMDQ3
						NzlDNDExRTJBMDI0QjFCOTczREVGNkI1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjVCMTky
						MDQ4NzlDNDExRTJBMDI0QjFCOTczREVGNkI1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmlu
						c3RhbmNlSUQ9InhtcC5paWQ6NUIxOTIwNDU3OUM0MTFFMkEwMjRCMUI5NzNERUY2QjUiIHN0UmVm
						OmRvY3VtZW50SUQ9InhtcC5kaWQ6NUIxOTIwNDY3OUM0MTFFMkEwMjRCMUI5NzNERUY2QjUiLz4g
						PC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9
						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
						X4X1X4X1X4X1REu4gsXjwrlfOv/Z" alt="" />
					<div class="has-error">Упс…<br />
					Похоже возникла ошибка в нашем программном коде.</div>
					<div class="we-know">Мы уже об этом знаем, и в ближайшее время все будет исправлено.</div>
					Просим прощения за неудобства.<br /><br />

					<small>Вы можете <a href="<?=self::USER_SEND_ERROR_LINK?>">написать нам</a>, указав подробности,
					если вы не впервые видите данную ошибку:</small><br />
					<div class="error-raw">
						<b>Fatal error:</b> <?=$last_error['message']?> in <?=$last_error['file']?> on line <?=$last_error['line']?>
					</div>

					<br /><br />Обновите страницу - скорее всего заработает.
				</div>
				<br clear="all" />
			</noindex>
			<?
		}
	}
}
?>