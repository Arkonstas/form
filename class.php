<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class CnForm extends CBitrixComponent {
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (   !Loader::includeModule('iblock')) 
        {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user() {
        global $USER;
        return $USER;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут пишем логику обработки параметров, дополнение параметрами по умолчанию
        // и прочие нужные вещи
        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам 
     */
    public function executeComponent() {
        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();

        if($this->_request->getPost('ajax') == "Y")
        {
            $posts = $this->_request->getPostList();

            foreach ($posts as $key => $post)
            {
                $PROP[$key] = $post;
            }

            $IBLOCK_ID = $this->arParams["IBLOCK_ID"]; 
            $el = new CIBlockElement;

            if(!empty($_FILES))
            {
                foreach ($_FILES as $keyFile => $file) 
                {
                    if(!empty($file["name"]))
                    {
                        $PROP[$keyFile] = $file;
                    }
                }
            }

            $arLoadProductArray = Array(
                "MODIFIED_BY"    => $this->_user()->GetID(),
                "IBLOCK_ID"      => $IBLOCK_ID,
                "PROPERTY_VALUES"=> $PROP,
                "NAME"           => date('Y-m-d h:i:s'),
                "ACTIVE"         => "Y",            
            );

            if($PRODUCT_ID = $el->Add($arLoadProductArray))
            {
                echo "New ID: ".$PRODUCT_ID;

                $SITE_ID = 's1';
                $EVEN_TYPE = 'FEEDBACK_FORM';


                CEvent::Send($EVEN_TYPE, $SITE_ID, $PROP, 'Y', '',);
            }
            else
            {
                echo $el->LAST_ERROR;
                $this->arResult['error'] = "К сожалению возникла ошибка. Просьба обратиться к менеджеру по телефону.";
            }
        }

        else
        {
            if(!empty($this->arParams["PROPS"]))
            {
                $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("IBLOCK_ID"=>$this->arParams["IBLOCK_ID"]));
                while ($prop_fields = $properties->GetNext())
                {
                    if(in_array($prop_fields["ID"], $this->arParams["PROPS"]))
                    {
                        $this->arResult["QUESTIONS"][$prop_fields["ID"]]["NAME"] = $prop_fields["NAME"];
                        $this->arResult["QUESTIONS"][$prop_fields["ID"]]["CODE"] = $prop_fields["CODE"];
                        $this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"] = $prop_fields["USER_TYPE"] == "HTML" ? "HTML" : $prop_fields["PROPERTY_TYPE"];

                        switch ($this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"]) {
                            case "HTML":
                                $this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"] = "textarea";
                                break;
                            case "S":
                                if($this->arResult["QUESTIONS"][$prop_fields["ID"]]["CODE"] == "PHONE")
                                {
                                    $this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"] = "tel";
                                }
                                else
                                {
                                    $this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"] = "text";
                                }
                                break;
                            case 'F':
                                $this->arResult["QUESTIONS"][$prop_fields["ID"]]["TYPE"] = "file";
                                break;
                        }
                    }

                    if(in_array($prop_fields["ID"], $this->arParams["REQUIRED_PROPS"]))
                    {
                        $this->arResult["QUESTIONS"][$prop_fields["ID"]]["REQUIRED"] = "Y";
                    }
                }
            }
        }

        $this->includeComponentTemplate();
    }
}