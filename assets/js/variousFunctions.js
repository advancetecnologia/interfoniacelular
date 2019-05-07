function CriaRequest() {
    try {
        request = new XMLHttpRequest();
    } catch (IEAtual) {

        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (IEAntigo) {

            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (falha) {
                request = false;
            }
        }
    }

    if (!request)
        alert("Seu Navegador não suporta Ajax!");
    else
        return request;
}

function get_cidades(uf, cidade, tipo) {
    var estado = "";
    if (tipo === 0) {
        estado = uf.value;
    } else if (tipo === 1) {
        estado = uf;
    }
    var url = get_url();
    var xmlreq = CriaRequest();
    var result = document.getElementById("inputCidadeResult");
    result.disabled = true;
    result.innerHTML = "<option>Carregando...</option>";
    xmlreq.open("GET", url + "usuarios/get_cidades?estado=" + estado + "&cidade=" + cidade, true);

    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                result.innerHTML = xmlreq.responseText;
                result.disabled = false;
            } else {
                result.innerHTML = "Erro: " + xmlreq.statusText;
            }
        }
    };
    xmlreq.send(null);
}

function get_url() {
    var newURL = window.location.protocol + "//" + window.location.host + "/condominio_html/";
    return newURL;
}

function marcar_todos() {
    var dias = document.getElementsByName('dias_semana');
    for (var i = 0; i < dias.length; i++) {
        dias[i].checked = true;
    }
}

function desmarcar_todos() {
    var dias = document.getElementsByName('dias_semana');
    for (var i = 0; i < dias.length; i++) {
        dias[i].checked = false;
    }
}

function limpa_campo_telefone_faixas() {
    var inicio = document.getElementById("faixa-telefone-inicio");
    var fim = document.getElementById("faixa-telefone-fim");
    inicio.value = "00:00";
    fim.value = "23:59";
}

function salva_faixa_telefone(id_telefone) {
    var hora_inicio = document.getElementById("faixa-telefone-inicio").value;
    var hora_fim = document.getElementById("faixa-telefone-fim").value;
    var message = document.getElementById("return_insert_faixa");
    if (hora_inicio === "") {
        message.innerHTML = "<div class='alert alert-danger' role='alert'>Campo hora início não pode ficar vazio</div>";
    }
    if (hora_fim === "") {
        message.innerHTML = "<div class='alert alert-danger' role='alert'>Campo hora fim não pode ficar vazio</div>";
    }
    var valida_hora_inicio = valida_hora(hora_inicio);
    var valida_hora_fim = valida_hora(hora_fim);
    if (valida_hora_inicio && valida_hora_fim) {
        if (hora_inicio > hora_fim) {
            message.innerHTML = "<div class='alert alert-danger' role='alert'>Hora início não pode ser maior que hora fim</div>";
        } else {
            var dias = document.getElementsByName('dias_semana');
            var dias_array = "";
            var cont = 0;
            for (var i = 0; i < dias.length; i++) {
                if (dias[i].checked) {
                    if (cont === 0) {
                        dias_array += dias[i].value;
                    } else {
                        dias_array += "," + dias[i].value;
                    }
                    cont++;
                }

            }
            if (cont === 0) {
                message.innerHTML = "<div class='alert alert-danger' role='alert'>Você deve selecionar pelo menos um dia da semana</div>";
            } else {
                grava_faixa_telefone(hora_inicio, hora_fim, dias_array, id_telefone);
            }
        }
    } else {
        message.innerHTML = "<div class='alert alert-danger' role='alert'>Formato de horas inválido</div>";
    }

}

function valida_hora(hora) {
    var hrs = (hora.substring(0, 2));
    var min = (hora.substring(3, 5));
    if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59)) {
        return false;
    } else {
        return true;
    }
}

function inclui_faixas() {
    var div_faixas = document.getElementById("faixas_horarios_telefone");
    var div_incluir_faixas = document.getElementById("incluir_faixas");
    div_faixas.style.display = 'none';
    div_incluir_faixas.style.display = 'block';
}

function grava_faixa_telefone(hora_inicio, hora_fim, dias, id_telefone) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var result = document.getElementById('return_insert_faixa');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "telefones/grava_faixas?hora_inicio="
            + hora_inicio + "&hora_fim=" + hora_fim + "&dias=" + dias + "&id_telefone=" + id_telefone, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                if (xmlreq.responseText == '1') {
                    result.innerHTML = "<div class='alert alert-success' role='alert'>Faixa inserida com sucesso</div>";
                } else {
                    result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente</div>";
                }
                get_faixas(id_telefone, 1);
                desmarcar_todos();
                limpa_campo_telefone_faixas();
            } else {
                loading.style.display = "none";
                result.innerHTML = "Erro: " + xmlreq.statusText;
            }
        }
    };
    xmlreq.send(null);
}

function get_faixas(id_telefone, tipo) {
    var url = get_url();
    var xmlreq = CriaRequest();
    var result = document.getElementById("result_table_faixas");
    var loading = document.getElementById('loading');
    var loading_modal = document.getElementById('loading_modal');
    if (tipo === 2) {
        result.innerHTML = "";
        loading_modal.innerHTML = "<img src='" + url + "assets/images/loader.gif'>";
    }
    xmlreq.open("GET", url + "telefones/get_faixas?id_telefone=" + id_telefone + "&tipo=" + tipo, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                var response = JSON.parse(xmlreq.responseText);
                var hora_inicio;
                var hora_fim;
                if (tipo === 1) {
                    var table = "<tr class='table_title'><th class='table_title'>Faixa</th><th class='table_title'>Dias semana</th><th class='table_title'>Excluir</th></tr>";
                } else {
                    var table = "<tr class='table_title'><th class='table_title'>Faixa</th><th class='table_title'>Dias semana</th></tr>";
                }
                var tag = false;
                for (var i = 0; i < response.length; i++) {
                    tag = true;
                    var dias_semana = "";
                    hora_inicio = response[i].inicio.substring(0, 5);
                    hora_fim = response[i].fim.substring(0, 5);
                    if (response[i].seg === '1') {
                        dias_semana += "Seg, ";
                    }
                    if (response[i].ter === '1') {
                        dias_semana += "Ter, ";
                    }
                    if (response[i].qua === '1') {
                        dias_semana += "Qua, ";
                    }
                    if (response[i].qui === '1') {
                        dias_semana += "Qui, ";
                    }
                    if (response[i].sex === '1') {
                        dias_semana += "Sex, ";
                    }
                    if (response[i].sab === '1') {
                        dias_semana += "Sab, ";
                    }
                    if (response[i].dom === '1') {
                        dias_semana += "Dom, ";
                    }
                    dias_semana = dias_semana.substring(0, dias_semana.length - 2);
                    table += "<tr>";
                    table += "<td>" + hora_inicio + " as " + hora_fim + "</td>";
                    table += "<td>" + dias_semana + "</td>";
                    if (tipo === 1) {
                        table += "<td><a class='btn btn-danger' href='javascript:delete_faixa(" + response[i].id + "," + id_telefone + ")'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> Excluir</a></td>";
                    }
                    table += "</tr>";
                }
                if (tipo === 2) {
                    loading_modal.innerHTML = "";
                }
                if (!tag && tipo === 2) {
                    table = "Sem faixas cadastradas";
                } else if (!tag && tipo === 1) {
                    table = "";
                    document.getElementById('btn_finaliza_cad_faixa_telefone').style.display = 'none';
                } else if (tipo === 1) {
                    document.getElementById('btn_finaliza_cad_faixa_telefone').style.display = 'block';
                }
                result.innerHTML = table;
                loading.style.display = "none";
            } else {
                result.innerHTML = "Erro: " + xmlreq.statusText;
                loading.style.display = "none";
            }
        }
    };
    xmlreq.send(null);
}

function delete_faixa(id_faixa, id_telefone) {
    if (confirm("Tem certeza que deseja excluir essa faixa de horário?")) {
        var result = document.getElementById('return_insert_faixa');
        var loading = document.getElementById('loading');
        var url = get_url();
        var xmlreq = CriaRequest();
        loading.style.display = "block";
        xmlreq.open("GET", url + "telefones/delete_faixa?id_faixa=" + id_faixa, true);
        xmlreq.onreadystatechange = function () {

            if (xmlreq.readyState == 4) {

                if (xmlreq.status == 200) {
                    if (xmlreq.responseText == '1') {
                        result.innerHTML = "<div class='alert alert-success' role='alert'>Faixa deletada com sucesso</div>";
                    } else {
                        result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente</div>";
                    }
                    get_faixas(id_telefone, 1);
                } else {
                    result.innerHTML = "Erro: " + xmlreq.statusText;
                    loading.style.display = "none";
                }
            }
        };
        xmlreq.send(null);
    }

}

function ativa_desativa_telefone(valor, id_telefone) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var result = document.getElementById('result_msg_telefone');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "telefones/ativa_desativa_telefone?valor=" + valor.checked + "&id_telefone=" + id_telefone, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            } else {
                loading.style.display = "none";
                result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente Erro:" + xmlreq.statusText;
                +"</div>";
            }
        }
    };
    xmlreq.send(null);
}

function ativa_desativa_apartamento(valor, id_apartamento) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var result = document.getElementById('result_msg_apartamentos');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "apartamentos/ativa_desativa_apartamento?valor=" + valor.checked + "&id_apartamento=" + id_apartamento, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            } else {
                loading.style.display = "none";
                result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente Erro:" + xmlreq.statusText;
                +"</div>";
            }
        }
    };
    xmlreq.send(null);
}

function ativa_desativa_equipamento(valor, id_equipamento) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var result = document.getElementById('result_msg_equipamentos');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "equipamentos/ativa_desativa_equipamentos?valor=" + valor.checked + "&id_equipamento=" + id_equipamento, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            } else {
                loading.style.display = "none";
                result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente Erro:" + xmlreq.statusText;
                +"</div>";
            }
        }
    };
    xmlreq.send(null);
}

function altera_ordem(id, tipo, apartamento_selecionado) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var result = document.getElementById('result_msg_telefone');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "telefones/altera_ordem?id_telefone=" + id + "&tipo=" + tipo + "&apartamento_selecionado=" + apartamento_selecionado, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            } else {
                loading.style.display = "none";
                result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente Erro:" + xmlreq.statusText;
                +"</div>";
            }
        }
    };
    xmlreq.send(null);
}

function delete_telefone(id, apartamento_selecionado) {
    if (confirm("Tem certeza que deseja excluir esse telefone?")) {
        var url = get_url();
        window.location = url + "telefones/delete/" + id + "/" + apartamento_selecionado;
    }
}

function delete_apartamento(id) {
    if (confirm("Tem certeza que deseja excluir esse apartamento?")) {
        var url = get_url();
        window.location = url + "apartamentos/delete/" + id;
    }
}

function delete_equipamento(id) {
    if (confirm("Tem certeza que deseja excluir esse apartamento?")) {
        var url = get_url();
        window.location = url + "equipamentos/delete/" + id;
    }
}

function delete_bloco(id) {
    if (confirm("Tem certeza que deseja excluir esse apartamento?")) {
        var url = get_url();
        window.location = url + "blocos/delete/" + id;
    }
}

function salva_bloco(tipo = null) {
    var cad_bloco_error = document.getElementById("cad_bloco_error");
    cad_bloco_error.style.display = "none";
    var cad_bloco_success = document.getElementById("cad_bloco_success");
    cad_bloco_success.style.display = "none";
    var id_identificador = document.getElementById("id_identificador").value;
    var nome_bloco = document.getElementById("nome_bloco").value;
    var id_conodominio = document.getElementById("id_condominio").value;
    var url = get_url();
    var xmlreq = CriaRequest();

    if (id_identificador === "") {
        cad_bloco_error.style.display = "block";
        cad_bloco_error.innerHTML = "O campo identificador do bloco é necessário.";
    } else if (isNaN(id_identificador)) {
        cad_bloco_error.style.display = "block";
        cad_bloco_error.innerHTML = "Informe somente números";
    } else if (nome_bloco === "") {
        cad_bloco_error.style.display = "block";
        cad_bloco_error.innerHTML = "O campo  nome do bloco é necessário.";
    } else if (id_conodominio === "") {
        alert("Houve um erro inesperado, por favor entre novamente no sistema e repita a operação");
        window.location.href = get_url() + "sair";
    } else {
        xmlreq.open("GET", url + "blocos/novo_by_modal?id_condominio=" + id_conodominio + "&nome=" + nome_bloco + "&identificador=" + id_identificador, true);
        xmlreq.onreadystatechange = function () {
            if (xmlreq.readyState == 4) {
                if (xmlreq.status == 200) {
                    if (xmlreq.responseText == '1') {
                        id_identificador = "";
                        nome_bloco = "";
                        cad_bloco_success.style.display = "block";
                        cad_bloco_success.innerHTML = "Bloco inserido com sucesso";
                        get_blocos(tipo);
                    } else if (xmlreq.responseText == '2') {
                        cad_bloco_error.style.display = "block";
                        cad_bloco_error.innerHTML = "Já existe um bloco com o mesmo identificador";
                    } else if (xmlreq.responseText == '3') {
                        cad_bloco_error.style.display = "block";
                        cad_bloco_error.innerHTML = "O campo Identificador deve conter um número maior que 0";
                    } else if (xmlreq.responseText == '4') {
                        cad_bloco_error.style.display = "block";
                        cad_bloco_error.innerHTML = "Você não pode cadastrar novos blocos, pois tem equipamento(s) cadastrado para o bloco único";
                    } else if (xmlreq.responseText == '5') {
                        cad_bloco_error.style.display = "block";
                        cad_bloco_error.innerHTML = "Você não pode cadastrar novos blocos, pois tem apartamento(s) cadastrado para o bloco único";
                    } else {
                        cad_bloco_error.style.display = "block";
                        cad_bloco_error.innerHTML = "Houve um erro ao gravar a informação, tente novamente";
                    }
                } else {
                    cad_bloco_error.style.display = "block";
                    cad_bloco_error.innerHTML = "Houve um erro ao gravar a informação, tente novamente";
                }
            }
        };
        xmlreq.send(null);
}
}

function limpa_campo_novo_bloco() {
    var cad_bloco_error = document.getElementById("cad_bloco_error");
    var cad_bloco_success = document.getElementById("cad_bloco_success");
    var nome_bloco = document.getElementById("nome_bloco");
    var identificador = document.getElementById("id_identificador");
    cad_bloco_error.style.display = "none";
    cad_bloco_success.style.display = "none";
    nome_bloco.value = "";
    identificador.value = "";
}

function get_blocos(tipo = null) {
    var id_bloco = document.getElementById("bloco_selecionado").value;
    var id_conodominio = document.getElementById("id_condominio").value;
    var url = get_url();
    var bloco = document.getElementById("id_bloco");
    var xmlreq = CriaRequest();
    xmlreq.open("GET", url + "blocos/get_by_condominio?id_condominio=" + id_conodominio, true);
    xmlreq.onreadystatechange = function () {
        if (xmlreq.readyState == 4) {
            if (xmlreq.status == 200) {
                var obj = JSON.parse(xmlreq.responseText);
                var retorno = "";
                var tamanho_blocos = obj.length;
                if (tamanho_blocos > 0) {
                    retorno = "<option value=''>Selecione um bloco</option>";
                    if (tipo == 1) {
                        if (id_bloco == '9db1737630d272e4fe7673185fa9db36') {
                            retorno += "<option selected='true' value='9db1737630d272e4fe7673185fa9db36'>Equipamento da portaria principal ( recebe todos os blocos )</option>";
                        } else {
                            retorno += "<option value='9db1737630d272e4fe7673185fa9db36'>Equipamento da portaria principal ( recebe todos os blocos )</option>";
                        }
                    }
                    for (var i = 0; i < obj.length; i++) {
                        if (obj[i].identificador != 0) {
                            if (id_bloco == obj[i].id) {
                                retorno += "<option selected='true' value='" + obj[i].id + "'>" + obj[i].nome + "</option>";
                            } else {
                                retorno += "<option value='" + obj[i].id + "'>" + obj[i].nome + "</option>";
                            }
                        }
                    }
                } else {
                    retorno += "<option value=''>Sem blocos cadastrados</option>";
                }
                if (tipo != 2) {
                    ativa_btn_salvar();
                }

                if (tipo == 1) {
                    qtde_digitos_apartamento(document.getElementById('id_bloco'));
                }

                bloco.innerHTML = retorno;

            }
        }
    };
    xmlreq.send(null);
}

function ativa_btn_salvar() {
    var btn_salvar = document.getElementById("btn_disabled");
    btn_salvar.disabled = false;
}

function ativa_loading() {
    var loading = document.getElementById('loading');
    loading.style.display = "block";
}

function desativa_loading() {
    var loading = document.getElementById('loading');
    loading.style.display = "none";
}

function esvazia_apartamento(id, id_bloco) {
    document.getElementById('id_apartamento_esvaziar').value = id;
    document.getElementById('id_bloco_esvaziar').value = id_bloco;
}

function ativa_desativa_cliente(valor, id_condominio) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "administrador/ativa_desativa_cliente?valor=" + valor.checked + "&id_condominio=" + id_condominio, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            }
        }
    };
    xmlreq.send(null);
}

function ativa_desativa_revenda(valor, id_revenda) {
    var url = get_url();
    var loading = document.getElementById('loading');
    var xmlreq = CriaRequest();
    loading.style.display = "block";
    xmlreq.open("GET", url + "administrador/ativa_desativa_revenda?valor=" + valor.checked + "&id_revenda=" + id_revenda, true);
    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {

            if (xmlreq.status == 200) {
                location.reload();
            }
        }
    };
    xmlreq.send(null);
}

function qtde_digitos_apartamento(campo) {
    if (campo.value === '9db1737630d272e4fe7673185fa9db36') {
        document.getElementById('qtde_digitos_apartamento').style.display = 'block';
        document.getElementById('control_qtd_digitos').value = 1;
    } else {
        document.getElementById('qtde_digitos_apartamento').style.display = 'none';
        document.getElementById('control_qtd_digitos').value = 0;
    }

}

function selecionar_todos(class_name, tipo, btn1, btn2, btn3) {
    var inputs = document.getElementsByClassName(class_name);
    var btn_seleciona_todos = document.getElementById(btn1);
    var btn_delete_selecionados = document.getElementById(btn2);
    var btn_desmarca_todos = document.getElementById(btn3);

    if (tipo === 1) {
        var checked = true;
        btn_delete_selecionados.style.display = 'block';
    } else {
        var checked = false;
        btn_delete_selecionados.style.display = 'none';
    }
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].checked = checked;
        btn_seleciona_todos.style.display = 'none';
        btn_desmarca_todos.style.display = 'block';
    }
}

function ativa_desativa_btn_delete_selecionados(class_name) {
    var inputs = document.getElementsByClassName(class_name);
    var btn_delete_selecionados = document.getElementById("apartamentos_btn_delete_selecionados");
    var cont = 0;
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked) {
            cont++;
        }
    }
    if (cont === 0) {
        btn_delete_selecionados.style.display = 'none';
    } else {
        btn_delete_selecionados.style.display = 'block';
    }

}

function verifica_imputs_selecionados() {

}

function delete_apartamentos_selecionados() {
    var url = get_url();
    var checkbox = document.getElementsByClassName('apartamentos_checkbox');
    var result = document.getElementById('result_msg_apartamentos');
    var loading = document.getElementById('loading');
    var checkbox_array = new Array();
    var cont = 0;
    for (var i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked) {
            checkbox_array[cont] = checkbox[i].value;
            cont++;
        }

    }
    $.ajax({
        url: url + 'apartamentos/delete_selecionados',
        type: "POST",
        data: {checkbox: checkbox_array},
        dataType: "json",
        beforeSend: function () {
            $('#modal_delete_selecionados').modal('hide');
            loading.style.display = "block";
        },

        success: function (resposta) {
            if (resposta == '1') {
                location.href = url + 'apartamentos';
            } else {
                loading.style.display = "none";
                result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente!</div>";
            }

        },

        error: function () {
            loading.style.display = "none";
            result.innerHTML = "<div class='alert alert-danger' role='alert'>Houve um erro tente novamente!</div>";
        }
    });
}

function get_eventos_equipamentos(id) {
    var url = get_url();
    var xmlreq = CriaRequest();
    var result_pendentes = document.getElementById("pendentes_" + id);
    var result_ultima_comunicacao = document.getElementById("ultima_comunicacao_" + id);
    xmlreq.open("GET", url + "equipamentos/get_eventos_equipamentos?id=" + id, true);

    xmlreq.onreadystatechange = function () {

        if (xmlreq.readyState == 4) {
            if (xmlreq.status == 200) {
                var response = JSON.parse(xmlreq.responseText);
                result_pendentes.innerHTML = response.pendentes;
                result_ultima_comunicacao.innerHTML = response.ultima_comunicacao;
                setTimeout(function () {
                    get_eventos_equipamentos(id);
                }, 3000);
                
            }
        }
    };
    xmlreq.send(null);

}

function view_alert_revenda(type){
    var div_alert = document.getElementById('info_revenda');
    var input_r_senha = document.getElementById('input_r_senha');
    if(type == '1'){
        div_alert.style.display = 'block';
        input_r_senha.style.display = 'none';
    }else{
        div_alert.style.display = 'none';
        input_r_senha.style.display = 'block';
    }
}

function select_condominio(){
    $('#modal_select_condominio').modal('show')
}

function envia_form_select_condominio(){
    ativa_loading();
    $('#modal_select_condominio').modal('hide');
    document.form_select_condominio.submit();
}

function new_hash(id_condominio, cnpj){
    ativa_loading();
    var url = get_url();
    var xmlreq = CriaRequest();

    xmlreq.open("GET", url + "condominio/new_hash?id_condominio=" + id_condominio + "&cnpj=" + cnpj, true);

    xmlreq.onreadystatechange = function () {
        if (xmlreq.readyState == 4) {
            if (xmlreq.status == 200) {
                desativa_loading();
                var hash = xmlreq.responseText;
                var div = document.getElementById(id_condominio);
                div.innerHTML = "<strong>Hash: </strong>" + hash;
            }
        }
    };
    xmlreq.send(null);
}

function new_hash_revenda(id_revenda, cnpj_revenda){
    ativa_loading();
    var url = get_url();
    var xmlreq = CriaRequest();

    xmlreq.open("GET", url + "revendas/new_hash?id_revenda=" + id_revenda + "&cnpj_revenda=" + cnpj_revenda, true);

    xmlreq.onreadystatechange = function () {
        if (xmlreq.readyState == 4) {
            if (xmlreq.status == 200) {
                desativa_loading();
                var hash = xmlreq.responseText;
                var div = document.getElementById("hash_revenda");
                div.innerHTML = "<strong>Hash da revenda: </strong>" + hash;
            }
        }
    };
    xmlreq.send(null);
}