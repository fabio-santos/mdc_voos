let page = (function() {
	
	"use strict";
	
	let init = function() {
        showWarnings();
        addListeners();
    };

	return {
		init: init
    };

    function showWarnings() {
        let warning = document.getElementById("page-warning");
        
        if(warning) {
            M.toast({html: warning.innerHTML});
        }
    }

    function addListeners() {
        let editElements = document.getElementsByClassName("editar");
        let deleteElements = document.getElementsByClassName("excluir");

        let addIdtoEditForm = function() {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");

            document.getElementById("edicao-id").value = id;
            document.getElementById("nome-edicao").value = name;

            setTimeout(function() {
                document.getElementById("nome-edicao").focus();
            }, 300);
        };

        let addIdtoDeleteForm = function() {
            let id = this.getAttribute("data-id");
            document.getElementById("exclusao-id").value = id;
        };

        for (var i = 0; i < editElements.length; i++) {
            editElements[i].addEventListener('click', addIdtoEditForm, false);
        }

        for (var i = 0; i < deleteElements.length; i++) {
            deleteElements[i].addEventListener('click', addIdtoDeleteForm, false);
        }
    }
    
}());