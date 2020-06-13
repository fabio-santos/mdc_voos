let page = (function() {
	
	"use strict";
	
	let init = function() {
        showWarnings();
        addListeners();
        mask();
    };

	return {
		init: init
    };

    function mask() {
        let dateMask = IMask(
        document.getElementById('data'),
        {
            mask: Date,
            min: new Date(1990, 0, 1),
            max: new Date(2999, 0, 1)
        });

        let decimal_elements = document.getElementsByClassName('decimal');
        if(decimal_elements) {
            for(let i=0;i<decimal_elements.length;i++) {
                let numberMask = IMask(
                decimal_elements[i], 
                {
                    mask: Number,
                });
            }
        }

        let time_elements = document.getElementsByClassName('time');
        if(time_elements) {
            for(let i=0;i<time_elements.length;i++) {
                let timeMask = IMask(
                time_elements[i], 
                {
                    mask: 'HH:MM',
                    blocks: {
                        HH: {
                            mask: IMask.MaskedRange,
                            from: 1,
                            to: 24
                        },
                        MM: {
                            mask: IMask.MaskedRange,
                            from: 0,
                            to: 59
                        }
                    }
                });
            }
        }
    }

    function showWarnings() {
        let warning = document.getElementById("page-warning");
        
        if(warning) {
            M.toast({html: warning.innerHTML});
        }
    }

    function addListeners() {
        let editElements = document.getElementsByClassName("editar");
        let deleteElements = document.getElementsByClassName("excluir");
        let inclusionTimeElements = document.getElementsByClassName("time_inclusao");
        let editTimeElements = document.getElementsByClassName("time_alteracao");
        let nivel = document.getElementById("nivel");

        let addIdtoEditForm = function() {
            let id = this.getAttribute("data-id");
            document.getElementById("edicao-id").value = id;
 
            fetch('get.php?id=' + id)
            .then(function(response){
                response.json().then(function(data){
                    document.getElementById("data-edicao").value = data.data;
                    document.getElementById("comandante-edicao").value = data.comandante;
                    document.getElementById("copiloto-edicao").value = data.copiloto;
                    document.getElementById("prevoo-edicao").value = data.prevoo;
                    document.getElementById("posvoo-edicao").value = data.posvoo;
                    document.getElementById("origem-edicao").value = data.origem;
                    document.getElementById("destino-edicao").value = data.destino;
                    document.getElementById("alternativa-edicao").value = data.alternativa;
                    document.getElementById("rumo-edicao").value = data.rumo;
                    document.getElementById("distancia-edicao").value = data.distancia;
                    document.getElementById("nivel-edicao").value = data.nivel;
                    document.getElementById("pob-edicao").value = data.pob;
                    document.getElementById("acionamento-edicao").value = data.acionamento;
                    document.getElementById("decolagem-edicao").value = data.decolagem;
                    document.getElementById("pouso-edicao").value = data.pouso;
                    document.getElementById("corte-edicao").value = data.corte;
                    document.getElementById("kmh-edicao").value = data.kmh;
                    document.getElementById("combustivel_decolagem-edicao").value = data.combustivel_decolagem;
                    document.getElementById("combustivel_pouso-edicao").value = data.combustivel_pouso;
                    document.getElementById("combustivel_consumido-edicao").value = data.combustivel_consumido;
                    document.getElementById("ias-edicao").value = data.ias;
                    document.getElementById("tas-edicao").value = data.tas;
                    document.getElementById("gs-edicao").value = data.gs;
                    document.getElementById("n1-edicao").value = data.n1;
                    document.getElementById("egt-edicao").value = data.egt;
                    document.getElementById("hp-edicao").value = data.hp;
                    document.getElementById("torque-edicao").value = data.torque;
                    document.getElementById("volts-edicao").value = data.volts;
                    document.getElementById("amperes-edicao").value = data.amperes;
                    document.getElementById("fuel_press-edicao").value = data.fuel_press;
                    document.getElementById("oil_press-edicao").value = data.oil_press;
                    document.getElementById("oil_temp-edicao").value = data.oil_temp;
                    document.getElementById("fueld_flow-edicao").value = data.fueld_flow;
                    document.getElementById("oat-edicao").value = data.oat;

                    let modal = document.getElementById('modal-edicao');

                    let labels = modal.getElementsByTagName("label");

                    for(let i=0;i<labels.length;i++) {
                        if(!labels[i].classList.contains("select-label")) {
                            labels[i].classList.add("active");
                        }
                    }

                    let selects = modal.querySelectorAll('select');
                    M.FormSelect.init(selects);

                    applyCamposCalculados();
                });
            })
            .catch(function(err){ 
                console.error('Failed retrieving information', err);
            });
        };

        for (let i=0;i<editElements.length;i++) {
            editElements[i].addEventListener('click', addIdtoEditForm, false);
        }

        let copyNivelToFl = function() {
            document.getElementById("fl").value = document.getElementById("nivel").value;
        }

        nivel.addEventListener('change', copyNivelToFl, false);

        let addIdtoDeleteForm = function() {
            let id = this.getAttribute("data-id");
            document.getElementById("exclusao-id").value = id;
        };        

        for (let i=0;i<deleteElements.length;i++) {
            deleteElements[i].addEventListener('click', addIdtoDeleteForm, false);
        }

        let calculateInclusionTime = function() {
            let acionamento = document.getElementById("acionamento").value;
            let corte = document.getElementById("corte").value;
            let decolagem = document.getElementById("decolagem").value;
            let pouso = document.getElementById("pouso").value;

            //corte > pouso > decolagem > acionamento
            if(corte && acionamento) {
                let diff = calculateTimeDiff(corte, acionamento);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em acionamento e corte" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_ca");
            }

            if(pouso && decolagem) {
                let diff = calculateTimeDiff(pouso, decolagem);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em pouso e decolagem" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_pd");
            }

            if(decolagem && acionamento) {
                let diff = calculateTimeDiff(decolagem, acionamento);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em acionamento e decolagem" });
                    return;
                }

                setTimeMask(diff, "taxi");
            }

            if(corte && pouso) {
                let diff = calculateTimeDiff(corte, pouso);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em corte e pouso" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_cp");
            }
        };

        let calculateEditTime = function() {
            let acionamento = document.getElementById("acionamento-edicao").value;
            let corte = document.getElementById("corte-edicao").value;
            let decolagem = document.getElementById("decolagem-edicao").value;
            let pouso = document.getElementById("pouso-edicao").value;

            //corte > pouso > decolagem > acionamento
            if(corte && acionamento) {
                let diff = calculateTimeDiff(corte, acionamento);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em acionamento e corte" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_ca-edicao");
            }

            if(pouso && decolagem) {
                let diff = calculateTimeDiff(pouso, decolagem);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em pouso e decolagem" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_pd-edicao");
            }

            if(decolagem && acionamento) {
                let diff = calculateTimeDiff(decolagem, acionamento);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em acionamento e decolagem" });
                    return;
                }

                setTimeMask(diff, "taxi-edicao");
            }

            if(corte && pouso) {
                let diff = calculateTimeDiff(corte, pouso);
                if(diff <= 0) {
                    M.toast({ html: "Reveja os valores informados em corte e pouso" });
                    return;
                }

                setTimeMask(diff, "tempo_voo_cp-edicao");
            }
        };

        for(let i=0;i<inclusionTimeElements.length;i++) {
            inclusionTimeElements[i].addEventListener('change', calculateInclusionTime, false);
        }

        for(let i=0;i<editTimeElements.length;i++) {
            editTimeElements[i].addEventListener('change', calculateEditTime, false);
        }
    }

    function applyCamposCalculados() {
        document.getElementById("fl-edicao").value = document.getElementById("nivel-edicao").value;

        let acionamento = document.getElementById("acionamento-edicao").value;
        let corte = document.getElementById("corte-edicao").value;
        let decolagem = document.getElementById("decolagem-edicao").value;
        let pouso = document.getElementById("pouso-edicao").value;

        //corte > pouso > decolagem > acionamento
        if(corte && acionamento) {
            let diff = calculateTimeDiff(corte, acionamento);
            if(diff <= 0) {
                M.toast({ html: "Reveja os valores informados em acionamento e corte" });
                return;
            }

            setTimeMask(diff, "tempo_voo_ca-edicao");
        }

        if(pouso && decolagem) {
            let diff = calculateTimeDiff(pouso, decolagem);
            if(diff <= 0) {
                M.toast({ html: "Reveja os valores informados em pouso e decolagem" });
                return;
            }

            setTimeMask(diff, "tempo_voo_pd-edicao");
        }

        if(decolagem && acionamento) {
            let diff = calculateTimeDiff(decolagem, acionamento);
            if(diff <= 0) {
                M.toast({ html: "Reveja os valores informados em acionamento e decolagem" });
                return;
            }

            setTimeMask(diff, "taxi-edicao");
        }

        if(corte && pouso) {
            let diff = calculateTimeDiff(corte, pouso);
            if(diff <= 0) {
                M.toast({ html: "Reveja os valores informados em corte e pouso" });
                return;
            }

            setTimeMask(diff, "tempo_voo_cp-edicao");
        }
    }

    function setTimeMask(value, fieldId) {
        let hours = Math.floor(value / 60);          
        let minutes = value % 60;

        if(hours > 0) {
            document.getElementById(fieldId).value = hours + " hora(s) e " + minutes + " minuto(s)";
        } else {
            document.getElementById(fieldId).value = minutes + " minuto(s)";
        }
        
        document.getElementById(fieldId).parentElement.getElementsByTagName("label")[0].classList.add("active");
        return;
    }

    function calculateTimeDiff(maior, menor) {
        let maiorSplit = maior.split(":");
        let menorSplit = menor.split(":");

        if(maiorSplit.length != 2 || menorSplit.length != 2) {
            return 0;
        }

        return ((parseInt(maiorSplit[0]) * 60) + parseInt(maiorSplit[1])) - ((parseInt(menorSplit[0]) * 60) + parseInt(menorSplit[1]));
    }
    
}());