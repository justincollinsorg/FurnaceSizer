class LocStorage {
    
    constructor(config){
        this.version = "1.01";
        this.initialize = function() {
            this.checkVersion();
            this.state = {
                sid: "",
                savedLocalStoreKeys: [],
                dataAttributes: [],
                formData: [],
            };
            this.updateService = "interval not set yet";
            this.establishSession();
            this.pushFormData(this.findAllFormElements());
            this.startUpdateInterval();
        }
        
        this.prefix = config.dataId;
        
        this.checkVersion = function (){
            if(localStorage.getItem("data-loc-store-hvac-version") == this.version){
                //do nothing
            } else {
                localStorage.setItem("data-loc-store-hvac-version",this.version)
                localStorage.removeItem("data-loc-store-hvac-formData");
                localStorage.removeItem("data-loc-store-hvac-sid");
            }
        }

        this.getRadioValue = function (name) {
            var radios = document.getElementsByName(name);
        
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    return radios[i].value;
                }
            }
        }

        this.unCheckRadioValues = function (name) {
            var radios = document.getElementsByName(name);
        
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    radios[i].checked = false;

                }
            }
        }


        this.restoreformState = function() {
            var formDataHistory = localStorage.getItem(this.prefix + '-' + "formData");
            if(formDataHistory != null) {
                this.formData = JSON.parse(formDataHistory);
                for(var key in this.formData){
                    switch(this.formData[key].type){
                        case "input":
                            switch(this.formData[key].inputType){
                                case "text":
                                    if(this.formData[key].value.length > 0) {
                                        document.querySelector(`[data-loc-store=${this.formData[key].name}]`).value = this.formData[key].value;
                                    } 
                                break
                                case "radio":
                                    if(this.formData[key].checked) {
                                        document.querySelector(`[data-loc-store=${this.formData[key].name}]`).checked = true;
                                    } else {
                                        document.querySelector(`[data-loc-store=${this.formData[key].name}]`).checked = false;   
                                    }
                                break
                                default:
                                // console.log(document.querySelector(`[data-loc-store=${this.formData[key].name}]`));
                                    // document.querySelector(`[data-loc-store=${this.formData[key].name}]`).value = this.formData[key].value;
                            }
                        break;
                        case "select":
                            var value = "";
                            var attrValue = this.formData[key].value;
                            var name = this.formData[key].name;
                            if(typeof this.formData[key].value == "object"){
                                var  selectedOptions = [];
                                for(var option in this.formData[key].value){
                                    var options = document.querySelector(`[data-loc-store=${name}]`).options;
                                    for(var k in options){
                                        if(options[k].value == this.formData[key].value[option]){
                                            document.querySelector(`[data-loc-store=${name}]`).options[k].selected = true;
                                        }
                                    }
                                }
                                value = selectedOptions;
                            } else {
                                var options = document.querySelector(`[data-loc-store=${name}]`).options;
                                for(var k in options){
                                    if(options[k].value == this.formData[key].value){
                                        document.querySelector(`[data-loc-store=${name}]`).options[k].selected = true;
                                    }
                                }
                            }
                        break;   
                        case "textarea":
                            document.querySelector(`[data-loc-store=${this.formData[key].name}]`).value = this.formData[key].value;
                        break; 
                    }
                }
            }
        }
        
        this.startUpdateInterval = function() {
            var that = this;
            var formData = this.findAllFormElements();
            this.updateService = setInterval(function(that){
                var lastSavedFormData = JSON.stringify(that.state.formData);
                var currentFormData = JSON.stringify(that.findAllFormElements());
                if(lastSavedFormData != currentFormData){
                    that.updateFormData(JSON.parse(currentFormData)); // pushFormData stringifies object
                }
            },1000,that);
        }
        
        this.findAllFormElements = function() {
            var results = [];
            var attrValue = "";
            var formElements = document.querySelectorAll('[data-loc-store]');
            for(var item in formElements){
                switch(formElements[item].localName){
                    case "input":
                        switch(formElements[item].type){
                            case "radio":
                                // document.getElementsByName(name);
                    // this.getRadioValue(formElements[item].name),
                                attrValue = formElements[item].attributes['data-loc-store'].nodeValue;
                                results.push({
                                type: "input",
                                inputType: "radio",
                                checked: formElements[item].checked,
                                radioName: formElements[item].name,
                                name: attrValue,
                                value: formElements[item].value
                            });
                            break;
                            default:
                            attrValue = formElements[item].attributes['data-loc-store'].nodeValue;
                            results.push({
                                type: "input",
                                inputType: "text",
                                name: attrValue,
                                value: formElements[item].value,
                            });
                            break;
                        }
                    break;
                    case "select":
                        var value = "";
                        attrValue = formElements[item].attributes['data-loc-store'].nodeValue;
                        if(formElements[item].multiple){
                            var  selectedOptions = [];
                            for(var option in formElements[item].selectedOptions){
                                var optValue = formElements[item].selectedOptions[option].value;
                                (optValue != null) ? selectedOptions.push(optValue) : false;
                            }
                            value = selectedOptions;
                        } else {
                            value = formElements[item].value;
                        }
                        results.push({
                            type: "select",
                            name: attrValue,
                            value: value,
                        });
                    break;   
                    case "textarea":
                        attrValue = formElements[item].attributes['data-loc-store'].nodeValue;
                        results.push({
                            type: "textarea",
                            name: attrValue,
                            value: formElements[item].value,
                        });
                        
                    break; 
                }
            }
            return results;
        }
        
        this.updateFormData = function (object){
            this.state.formData = object;
            var stringifiedFormData = JSON.stringify(this.state.formData);
            this.setLocalStorage('formData', stringifiedFormData);
        }
               
        this.pushFormData = function (object){
            this.state.formData.push(object);
            var stringifiedFormData = JSON.stringify(this.state.formData);
            this.setLocalStorage('formData', stringifiedFormData);
        }
        
        this.pushAttribute = function (attr){
            this.state.dataAttributes.push(attr);
            this.setLocalStorage('dataAttributes',this.state.dataAttributes);
        }
        
        this.pushKey = function(key){//this to be used for easy removal of localstorage
            this.state.savedLocalStoreKeys.push(key);
            this.setLocalStorage('keys',this.state.savedLocalStoreKeys);
        }
        
        this.getLocalStorage = function(key) {
            localStorage.getItem(this.prefix + '-' + key);
        }
        this.setLocalStorage = function(key,value) {
            localStorage.setItem(this.prefix + '-' + key, value);
        }
        
        this.establishSession = function() {
            var sid = this.getLocalStorage('sid');
            if(sid == null) {
                // create random id
                var randId = Math.floor(Math.random() * 1000000);
                //set new id in localStorage
                sid='sid'+randId;
                var sidKey = this.prefix + 'sid';
                this.setLocalStorage('sid', sid);
                this.state.savedLocalStoreKeys.push(sidKey);
                this.state.sid = sid;
                var savedFormData = localStorage.getItem(this.prefix + '-' + "formData");
                if(savedFormData != null){
                    if(typeof savedFormData != 'object'){
                        this.restoreformState();
                        this.state.formData = JSON.parse(savedFormData);
                       
                    } else {
                        this.restoreformState();
                        this.state.formData = savedFormData;
                    }
                }
            } else {
                this.state.sid = sid;
                this.state.savedLocalStoreKeys = this.getLocalStorage('keys');
                this.state.dataAttributes = this.getLocalStorage('dataAttributes');
            }
        }
        this.initialize();
    } // end of constuctor
}
    var locStore = "";
    var locStoreCallCount = 0;
    // document.addEventListener("load", function(){
        var runWhenReadyInterval = setInterval(function(){
            
            if(document.getElementById("j_systemBlock") != null){
                locStore = new LocStorage({
                    dataId: "data-loc-store-hvac",
                });
                clearInterval(runWhenReadyInterval);
            } else if(locStoreCallCount > 100){
                clearInterval(runWhenReadyInterval);
            }
            console.log("wait-");
            console.log(locStoreCallCount)
            locStoreCallCount++;
            
        },250);
        
    // });
        console.log(locStore);
