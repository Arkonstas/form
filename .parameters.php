<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */
 
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if( !Loader::includeModule("iblock") ) {
    throw new \Exception('Не загружены модули необходимые для работы компонента');
}

// типы инфоблоков
$arIBlockType = CIBlockParameters::GetIBlockTypes();

// инфоблоки выбранного типа
$arIBlock = [];
$iblockFilter = !empty($arCurrentValues['IBLOCK_TYPE'])
    ? ['TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y']
    : ['ACTIVE' => 'Y'];

$rsIBlock = CIBlock::GetList(['SORT' => 'ASC'], $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

if(!empty($arCurrentValues['IBLOCK_ID']))
{
    $res = CIBlock::GetProperties($arCurrentValues['IBLOCK_ID'], Array(), Array());
    while($res_arr = $res->Fetch())
    {
        $arProps[$res_arr['ID']] = '['.$res_arr['CODE'].'] '.$res_arr['NAME'];
    }
        
}
unset($res, $res_arr);

$arComponentParameters = [
    "GROUPS" => [
        "SETTINGS" => [
            "NAME" => Loc::getMessage('CN_FORM_PROP_SETTINGS'),
            "SORT" => 550,
        ],
    ],
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('CN_FORM_PROP_IBLOCK_TYPE'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y"
        ],
        "IBLOCK_ID" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('CN_FORM_PROP_IBLOCK_ID'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y"
        ],
        "PROPS" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('CN_FORM_PROP_PROPS'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arProps,
            "REFRESH" => "N",
            "MULTIPLE" => "Y",
        ],
        "REQUIRED_PROPS" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('CN_FORM_PROP_REQUIRED_PROPS'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arProps,
            "REFRESH" => "N",
            "MULTIPLE" => "Y",
        ],
        "BTN_TEXT" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('CN_FORM_PROP_BTN'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "COLS" => 25
        ],
        'CACHE_TIME' => ['DEFAULT' => 3600],
    ]
];