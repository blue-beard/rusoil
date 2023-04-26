<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc as Loc;

try {
    $arComponentParameters = array(
        "GROUPS" => array(
        ),
        "PARAMETERS" => array(
            "EMAIL_TO" => array(
                "NAME" => Loc::GetMessage("PARAMS_EMAIL_TO"),
                "TYPE" => "STRING",
                "DEFAULT" => "",
            ),
        ),
    );
} catch (Main\LoaderException $e) {
    ShowError($e->getMessage());
}