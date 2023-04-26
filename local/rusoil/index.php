<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use Bitrix\Main\Page\Asset;
/**
 * @global CMain $APPLICATION
 */

$APPLICATION->SetTitle("rusoil send req mail");
Asset::getInstance()->addCss("/bitrix/css/main/font-awesome.css"); // плюс и минус на педальках
$APPLICATION->IncludeComponent(
	"rusoil:form.send",
	".default", 
	array(
		"EMAIL_TO" => "webmaster@rusoil72.ru",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
