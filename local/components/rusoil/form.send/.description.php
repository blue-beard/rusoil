<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

$arComponentDescription = array(
	"NAME" => Loc::getMessage("RUSOIL_COMPONENT_NAME"),
	"DESCRIPTION" => Loc::getMessage("RUSOIL_COMPONENT_DESCR"),
	"CACHE_PATH" => "Y",
	"PATH" => array(
        "NAME" => Loc::getMessage("RUSOIL_NAME_SECTION"),
		"ID" => "rusoil",
		"CHILD" => array(
			"ID" => "form",
			"NAME" => Loc::getMessage("RUSOIL_COMPONENT_NAME"),
		)
	),
);
