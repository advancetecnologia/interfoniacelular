<?php
if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<h3>Importação de arquivos</h3>
<form class="formularios" method="post" action="<?= base_url('importacao/upload') ?>" enctype="multipart/form-data">
    <div class="panel panel-default">
        <div class="panel-heading">Arquivo para importação</div>
        <div class="panel-body">
            <input id="textNome" name="file"  placeholder="Selecione o arquivo" type="file"><br/>
            <input type="checkbox" id="input_sobrescrever" name="sobrescrever" data-toggle="modal" data-target="#modal_conf_sobrescrever"><label for="input_sobrescrever"> Sobrescrever os telefones já existentes</label><br/><br/>
            <a href="#" data-toggle="modal" data-target="#modal_exemplo_csv">
                <div class="alert alert-info" role="alert">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Clique e veja mais instruções para a importação
                </div>
            </a>
        </div>
    </div>
    <button type="submit" onclick="ativa_loading()"value="upload" class="btn btn-success">Importar arquivo</button>
</form>
<!-- Modal -->
<div class="modal fade" id="modal_conf_sobrescrever" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Aviso !</h4>
            </div>
            <div class="modal-body">
                Caso o arquivo a ser importado contenha telefones já cadastrados no sistema, marcar esta opção fará com que os dados do sistema sejam substituídos pelos dados do arquivo. 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar mensagem</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_exemplo_csv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Instruções para importação</h4>
            </div>
            <div class="modal-body">
                <p><strong>Arquivos aceitos:</strong>
                    .CSV</p>
                <p><strong>Tamanho máximo:</strong>
                    5 MB</p>
                <p><strong>Exemplo de arquivo:</strong></p>
                <p>Na primeira coluna do arquivo você deve colocar os blocos, somente números;</p><br/>
                <p>Segunda coluna você deve colocar os números dos apartamentos, somente números;</p><br/>
                <p> Terceira coluna você deve colocar os números do 1º telefone, somente números e com o código de área, exemplo: 51999999999;</p><br/>
                <p>Quarta coluna você deve colocar os números do 2º telefone, somente números e com o código de área, exemplo:51999999999;</p><br/>
                <p>OBS: O número do 2º celular não é obrigatórios.</p>
                </p> 
                <img src="<?= base_url('assets/images/exemplo_csv.jpg') ?>">
            </div>
        </div>
    </div>
</div>