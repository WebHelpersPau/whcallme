function toggle(element) {
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        setTimeout(function () {
            element.classList.remove('visuallyhidden');
        }, 20);
    } else {
        element.classList.add('visuallyhidden');
        // event pour declencher transition
        element.addEventListener('transitionend', function(e) {
            element.classList.add('hidden');
        }, {
            capture: false,
            once: true,
            passive: false
        });
    }
}

function validBic(str) {
    return /^[a-z]{6}[2-9a-z][0-9a-np-z]([a-z0-9]{3}|x{3})?$/.test(str.toLowerCase());
}

function validIBAN(str) {
    return /^FR\d{12}[0-9A-Z]{11}\d{2}$/.test(str);
}

function trimAll(str) {
    return str.replace(/[\s\uFEFF\xA0]+/g, '');
}


$(document).ready(function(){
    $('#iban').mask('SS00 0000 0000 0000 0000 0000 000', {placeholder: "____ ____ ____ ____ ____ ____ ___"});
    $('#bic').mask('SSSSSSSS000', {placeholder: "___________"});
});

window.addEventListener("DOMContentLoaded", (event) => {
    console.log("DOM entièrement chargé et analysé");

    PPButton = document.getElementById('PPButton');
    PPForm  = document.getElementById('PPForm');
    PPValid = document.getElementById('PPValid');
    PPNotify = document.getElementById('PPNotify');

    if (PPValid!==undefined && PPValid!==null) {
        PPValid.addEventListener('click', (e)=>{
            e.preventDefault();

            let errors = [whMessages.consent];
            PPNotify.textContent = '';

            for(let element of PPForm.elements) {

                switch(element.name) {
                    case 'iban':
                        if (!validIBAN(trimAll(element.value))) {
                            errors.push(whMessages.iban);
                        }
                    break;

                    case 'bic':
                        if (!validBic(trimAll(element.value))) {
                            errors.push(whMessages.bic);
                        }
                    break;

                    case 'adresse_banque':
                        if (element.value.length<5) {
                            errors.push(whMessages.adresse);
                        }
                    break;

                    case 'acceptation':
                        if (parseInt(element.value)===1) {
                            errors.shift();
                        }
                    break;
                }
            }

            if (errors.length>0) {
                PPNotify.classList.remove('hidden');
                PPNotify.classList.add('alert-danger');
                PPNotify.innerHTML = errors.join('<br/>');
                return false;
            } else {
                let data = {
                    //'id_whpprev': parseInt(PPForm.elements['id_whpprev'].value),
                    'iban': trimAll(PPForm.elements['iban'].value),
                    'bic': trimAll(PPForm.elements['bic'].value),
                    'adresse_banque': PPForm.elements['adresse_banque'].value,
                    'acceptation': PPForm.elements['acceptation'].checked ? 1 : 0,
                    'id_user': PPForm.elements['id_user'].value,
                    'cache': false,
                    //'token': PPForm.elements['token'].value, //Controller token
                    'ajax': 1,
                    'controller': 'AdminWhPPrev',
                    'action': 'insert',
                };

                toggle(PPForm);

                PPNotify.classList.remove('hidden');
                PPNotify.classList.remove('alert-danger');
                PPNotify.classList.add('alert-warning');
                PPNotify.textContent = whMessages.sending;

                $.ajax({
                    //url : adminAjax_link,
                    url: ajax_link,
                    type: "POST",
                    data : data,
                    success : function(response){
                        data = JSON.parse(response);
                        PPNotify.classList.remove('alert-warning');

                        if (data.status) {
                            PPNotify.classList.add('alert-success');
                            PPNotify.innerHTML = whMessages.success;
                        } else {
                            PPNotify.classList.add('alert-danger');
                            PPNotify.innerHTML = whMessages.failure;
                        }
                    }
                });
            }

        });
    }

  });
