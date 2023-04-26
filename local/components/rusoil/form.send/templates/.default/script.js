vueApp=BX.Vue3.BitrixVue.createApp( {
    data : function()  {
        return {
            arCategories: arCategories,
            arReqTypes : arReqTypes,
            arStorages : arStorages,
            arBrands : arBrands,

            formSelected :{
                reqHeader : 'Типа заголовок',
                category : 1, // выберем что нибудь за юзера, а то ленивые нынче пошли, да и мне надоело заполнять
                reqType : 1, // аналогично
                storage : 1, // стабильность признак.. ну и тестить проще (хотя в задании не выбрано)
                brands  : 1, // стабильность признак.. ну и тестить проще (хотя в задании не выбрано)
                reqComposition : [
                    {
                        brands : 1, // заполнил по причина выше
                        name : 'три корочки хлеба',
                        amount : 333,
                        packing : 'мешками',
                        client : ' уже созрел',
                    },
                ],
                comment : 'он и в африке коммент'
            }
        }
    },

    methods: {
        /**
         * показывать ли педаль удаления
         */
        hideMinusReqСomposition : function (){
            return this.formSelected.reqComposition.length<=1;
        },

        /**
         * показывать ли педаль добавления
         */
        hidePlusReqСomposition : function (i){
            return this.formSelected.reqComposition.length!=i+1;
        },

        /**
         * добавляем строку к заказу
         */
        addNewRowReqСomposition : function(){
            //this.formSelected.reqComposition.push({}); // вариант в prod

            //вариант для тестирования, копируем последний
            let l=JSON.parse(JSON.stringify(this.formSelected.reqComposition[this.formSelected.reqComposition.length-1])); // создаем не реактивную копию последнего
            this.formSelected.reqComposition.push(l);
        },

        /**
         * удаляем строку из заказа
         */
        deleteRowReqСomposition : function(i){
            this.formSelected.reqComposition.splice(i, 1);
        },

    },
}).mount('#formVueApp');

