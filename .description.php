<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("CN_FORM_COMPONENT"),
    "DESCRIPTION" => Loc::getMessage("CN_FORM_COMPONENT_DESCRIPTION"),
    "COMPLEX" => "N",
    "PATH" => [
        "ID" => Loc::getMessage("CN_FORM_COMPONENT_PATH_ID"),
        "NAME" => Loc::getMessage("CN_FORM_COMPONENT_PATH_NAME"),
    ],
];
?>