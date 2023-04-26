<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */

use \Bitrix\Main\Localization\Loc;
\Bitrix\Main\UI\Extension::load('ui.bootstrap4');
\Bitrix\Main\UI\Extension::load("jquery3");
\Bitrix\Main\UI\Extension::load("ui.vue3");
?>
<script data-skip-moving="true">
    let arCategories=<?=json_encode($arResult['AR_CATEGORIES'])?>;
    let arReqTypes=<?=json_encode($arResult['AR_REQ_TYPES'])?>;
    let arStorages=<?=json_encode($arResult['AR_STORAGES'])?>;
    let arBrands=<?=json_encode($arResult['AR_BRANDS'])?>;
</script>

<?if(isset($arResult["RESULT"])){?>
  <div class="alert alert-<?=($arResult["RESULT"]["STATUS"] == "SUCCESS" ? "success" : "danger")?>"><?=$arResult["RESULT"]["MESSAGE"]?></div>
<?}?>

<div class="container-fluid">
    <div id="formVueApp">
        <form method="POST" action1="#" enctype="multipart/form-data">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h2><?=Loc::getMessage("FORM_HEADER");?></h2></div>

                    <div class="card-body">
                        <div class="form-group col-xl-6 mb-4">
                            <label class="h6" for="inputHeader"><?=Loc::getMessage("FORM_INPUT_HEADER");?></label>
                            <input type="text" class="form-control" name="reqHeader" placeholder="<?=Loc::getMessage("FORM_INPUT_HEADER");?>" required
                                   :v-model="formSelected.reqHeader" :value="formSelected.reqHeader">
                        </div>
                        <div class="form-group col-12 mb-4">
                            <label class="h6"><?=Loc::getMessage("FORM_INPUT_CATEGORY");?></label>
                            <template v-for="element in arCategories">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="category"
                                           :value="element.id"
                                           :id="'category'+element.id"
                                           v-model="formSelected.category">
                                    <label class="form-check-label" type="radio"
                                           :for="'category'+element.id">{{element.name}}
                                    </label>
                                </div>
                            </template>
                        </div>
                        <div class="form-group col-12 mb-4">
                            <label class="h6"><?=Loc::getMessage("FORM_INPUT_REQ_TYPE");?></label>
                            <template v-for="element in arReqTypes">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="reqType"
                                           :value="element.id"
                                           :id="'reqType'+element.id"
                                           v-model="formSelected.reqType">
                                    <label class="form-check-label" type="radio"
                                           :for="'reqType'+element.id">{{element.name}}
                                    </label>
                                </div>
                            </template>
                        </div>
                        <div class="form-group col-xl-6 mb-4">
                            <label class="h6"><?=Loc::getMessage("FORM_INPUT_STORAGE");?></label>
                            <select class="form-control" title="<?=Loc::getMessage("FORM_INPUT_STORAGE");?>" name="storage"
                                    v-model="formSelected.storage">
                                <option v-for="element in arStorages" :value="element.id">{{element.name}}</option>
                            </select>
                        </div>
                        <div class="form-group col-12 mb-4">
                            <label class="h6"><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION");?></label>
                            <template v-for="(element, index) in formSelected.reqComposition">
                                <div class="row">
                                    <div class="col-12 col-xl">
                                        <label for="reqСompositionBrand"><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_BRANDS");?></label>
                                        <select class="form-control" title="<?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_BRANDS");?>"
                                                name="reqComposition[brands][]"
                                                v-model="element.brands">
                                            <option v-for="element in arBrands" :value="element.id">{{element.name}}</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-xl">
                                        <label><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_NAME");?></label>
                                        <input type="text" class="form-control" v-model="element.name" name="reqComposition[name][]">
                                    </div>
                                    <div class="col-12 col-xl">
                                        <label><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_AMOUNT");?></label>
                                        <input type="text" class="form-control" v-model="element.amount" name="reqComposition[amount][]">
                                    </div>
                                    <div class="col-12 col-xl">
                                        <label><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_PACKING");?></label>
                                        <input type="text" class="form-control" v-model="element.packing" name="reqComposition[packing][]">
                                    </div>
                                    <div class="col-12 col-xl">
                                        <label><?=Loc::getMessage("FORM_INPUT_REQ_COMPOSITION_CLIENT");?></label>
                                        <input type="text" class="form-control" v-model="element.client" name="reqComposition[client][]">
                                    </div>
                                    <div class="col-12 col-xl">
                                        <div>&nbsp;</div>
                                        <button type="button" class="btn btn-danger" title="Удалить"
                                                :class="{'d-none' : hideMinusReqСomposition()}"
                                                @click="deleteRowReqСomposition(index)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-success" title="добавить"
                                                :class="{'d-none' : hidePlusReqСomposition(index)}"
                                                @click="addNewRowReqСomposition()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                </div>
                           </template>
                        </div>
                        <div class="form-group mb-4"><input type="file" name="uploadfiles[]" multiple></div>
                        <div class="form-group">
                            <label class="h6"><?=Loc::getMessage("FORM_INPUT_COMMENT");?></label>
                            <textarea name="comment" class="form-control" v-model="formSelected.comment"></textarea>
                        </div>
                    </div>

                </div>
                <footer class="card-footer">
                    <button type="submit" value="Submit" class="btn btn-primary">
                        <i class="fa fa-save pr-1"></i><?=Loc::getMessage("FORM_BUTTON_SEND");?>
                    </button>
                </footer>
            </div>
        </form>

    </div>
</div>
