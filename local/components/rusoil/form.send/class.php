<?php
use\Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Context;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

define("MIN_FORM_STRING_LEN", 3); //минимальная длинна обязательных строковых полей формы

class RusoilAddApplication extends \CBitrixComponent {

    /**
     * по уму получаем из какой то таблицы
     */
    private function getCategoriesFromSomeTable() :array{
        return array(
            1 => array('id' => 1, 'name' =>"Масла, автохимия, фильтры. Автоаксессуары, обогреватели, запчасти, сопутствующие товары.",),
            2 => array('id' => 2,'name' => "Шины, диски",),
        );
    }

    /**
     * по уму получаем из какой то таблицы
     */
    private function getReqTypesFromSomeTable() : array{
        return array(
            1 => array('id' => 1, 'name' => "Запрос цены и сроков поставки",),
            2 => array('id' => 2, 'name' => "Пополнение складов",),
            3 => array('id' => 3, 'name' => "Спецзаказ",),
        );
    }

    /**
     * по уму получаем из какой то таблицы
     */
    private function getStoragesFromSomeTable() : array{
        return array(
            1 => array('id' => 1, 'name' => "Склад 1",),
            2 => array('id' => 2, 'name' => "Склад 2",),
            3 => array('id' => 3, 'name' => "Склад 3",),
        );
    }

    /**
     * по уму получаем из какой то таблицы
     */
    private function getBrandsFromSomeTable() : array{
        return array(
            1 => array('id' => 1, 'name' => "Rusoil",),
            2 => array('id' => 2, 'name' => "Rusoil +",),
            3 => array('id' => 3, 'name' => "Rusoil + pro",),
        );
    }

    private function getCategoryName($id){
        return $this->getCategoriesFromSomeTable()[intval($id)]['name'];
    }

    private function getReqTypeName($id){
        return $this->getReqTypesFromSomeTable()[intval($id)]['name'];
    }

    private function getStorageName($id){
        return $this->getStoragesFromSomeTable()[intval($id)]['name'];
    }

    private function getBrandName($id){
        return $this->getBrandsFromSomeTable()[intval($id)]['name'];
    }


    /**
     *  лепим письмо
     */
    private function buildSendMail(){
        $TEXT =  "<b>".Loc::getMessage("FORM_INPUT_HEADER").": </b> {$this->request->getPost('reqHeader')} <br>";
        $TEXT .= "<b>".Loc::getMessage("FORM_INPUT_CATEGORY").": </b> {$this->getCategoryName($this->request->getPost('category'))}<br>";
        $TEXT .= "<b>".Loc::getMessage("FORM_INPUT_REQ_TYPE").": </b> {$this->getReqTypeName($this->request->getPost('reqType'))}<br>";
        $TEXT .= "<b>".Loc::getMessage("FORM_INPUT_STORAGE").": </b> {$this->getStorageName($this->request->getPost("storage"))}<br>";
        $TEXT .= "<b>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION").": </b><br>";

        $TEXT .= "<style>#mTable{width: 100%;border: 1px solid black!important;border-collapse: collapse;border-spacing: 1px}; #mtable td, #mtable th {padding: 0.75rem;border: 1px solid black!important;}</style>";
        $TEXT .= "<table id='mTable'>";
        $reqC=$this->request->getPost("reqComposition");
        $TEXT .= "<tr>";
        $TEXT .= "<th>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_BRANDS")."</th>";
        $TEXT .= "<th>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_NAME").'</th>';
        $TEXT .= "<th>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_AMOUNT").'</th>';
        $TEXT .= "<th>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_PACKING").'</th>';
        $TEXT .= "<th>".Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_CLIENT").'</th>';
        $TEXT .= "</tr>";

        foreach ($reqC['brands'] as $key => $brandID){
            $TEXT .= "<tr>";
            $TEXT .= "<td>".$this->getBrandName($brandID).'</td>';
            $TEXT .= "<td>".$reqC['name'][$key].'</td>';
            $TEXT .= "<td>".$reqC['amount'][$key].'</td>';
            $TEXT .= "<td>".$reqC['packing'][$key].'</td>';
            $TEXT .= "<td>".$reqC['client'][$key].'</td>';
            $TEXT .= "</tr>";
        }
        $TEXT .= "</table><br>";
        $TEXT .= "<b>".Loc::getMessage("FORM_INPUT_COMMENT").":</b> {$this->request->getPost("comment")}<br><br>";
        if(count($_FILES)) $TEXT .= Loc::getMessage("COMPONENT_MAIL_WHERE_FILES");
        $TEXT .= "<br><br>";

        $this->arEventFields=array(
            "DEFAULT_EMAIL_FROM" => \COption::GetOptionString("main", "email_from"),
            "EMAIL_TO" => $this->arParams["EMAIL_TO"],
            "SUBJECT" => Loc::getMessage("FORM_HEADER"),
            'TEXT' => $TEXT,
        );

        $this->files = array();
        $inputFilesName='uploadfiles';
        foreach ($_FILES[$inputFilesName]["tmp_name"] as $key => $tmp_name){
            if (!empty($tmp_name)) {
                $arrFile = array(
                    "name"     => $_FILES[$inputFilesName]["name"][$key],
                    "type"     => $_FILES[$inputFilesName]["type"][$key],
                    "tmp_name" => $_FILES[$inputFilesName]["tmp_name"][$key],
                    "error"    => $_FILES[$inputFilesName]["error"][$key],
                    "size"     => $_FILES[$inputFilesName]["size"][$key],
                );
                $this->files[] = CFile::SaveFile($arrFile, "/tempMail");
            }
        }

        // почтовый шаблон должен быть в html
        $res=\CEvent::SendImmediate("FEEDBACK_FORM", array(SITE_ID), $this->arEventFields, "Y", "", $this->files);
        foreach ($this->files as $fID) \CFile::Delete($fID);
        return $res;
    }


    /**
     * @return bool
     */
    public function checkForm() : bool{
        if( mb_strlen($this->request->getPost('reqHeader'))<=MIN_FORM_STRING_LEN ) return false;
        $category=intval($this->request->getPost('category'));
        if( !isset($this->getCategoriesFromSomeTable()[$category]) ) return false; // категорию передали и она есть CategoriesSomeTable
        $reqType=intval($this->request->getPost('reqType'));
        if( !isset($this->getReqTypesFromSomeTable()[$reqType]) ) return false; // вид заявки передали и она есть ReqTypesSomeTable
        // по уму еще текстовые поля на инъекции попроверять но в задании этого небыло
        return true;
    }


    /**
     * @return void
     */
    public function executeComponent(){
        try {
            $this->request = Context::getCurrent()->getRequest();
            if($this->request->getRequestMethod()==="POST"){
                if($this->checkForm()){ // проверка формы
                    if($this->buildSendMail()){ // создаем отправляем письмо
                        $this->arResult["RESULT"]=array("STATUS"=>"SUCCESS", "MESSAGE" => Loc::getMessage("COMPONENT_SEND_SUCCESS_MESSAGE")); // ошибка проверки формы
                    }
                    else{
                        $this->arResult["RESULT"]=array("STATUS"=>"ERROR", "MESSAGE" => Loc::getMessage("COMPONENT_SEND_ERROR_MESSAGE")); // ошибка проверки формы
                    }
                }
                else{
                    $this->arResult["RESULT"]=array("STATUS"=>"ERROR", "MESSAGE" => Loc::getMessage("COMPONENT_SEND_ERROR_NOT_FIELDS")); // ошибка проверки формы
                }

            }

            $this->arResult['AR_CATEGORIES'] = $this->getCategoriesFromSomeTable();
            $this->arResult['AR_REQ_TYPES'] = $this->getReqTypesFromSomeTable();
            $this->arResult['AR_STORAGES'] = $this->getStoragesFromSomeTable();
            $this->arResult['AR_BRANDS'] = $this->getBrandsFromSomeTable();

            $this->includeComponentTemplate();
        }
        catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}