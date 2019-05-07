<?php
if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<h3>Importação de arquivos padrão MC-Soft</h3>
<form class="formularios" method="post" action="<?= base_url('importacao/upload_mc_soft') ?>" enctype="multipart/form-data">
    <input type="hidden" name="id_condominio" id="id_condominio" value="<?= $id_condominio ?>">
    <input type="hidden" id="bloco_selecionado" value="0">
    <div class="panel panel-default">
        <div class="panel-heading">Arquivo para importação</div>
        <div class="panel-body">
            <input id="textNome" name="file_mc_soft"  placeholder="Selecione o arquivo" type="file"><br/>
            <?php
            if (!$this->session->userdata('bloco_unico')) {
                ?>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Bloco</div>
                        <select name="id_bloco" id="id_bloco" class="form-control">
                            <option>Carregando...</option>
                        </select>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <input type="hidden" name="id_bloco" value="<?= $this->session->userdata('id_bloco_unico') ?>">
                <?php
            }
            ?>
            <input type="checkbox" id="input_sobrescrever" name="sobrescrever" data-toggle="modal" data-target="#modal_conf_sobrescrever"><label for="input_sobrescrever"> Sobrescrever os telefones já existentes</label><br/><br/>
            <a class="teste" href="#" data-toggle="modal" data-target="#modal_exemplo_csv">
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
                <p>Na primeira coluna do arquivo você deve colocar os números dos apartamentos, somente números;</p><br/>
                <p> Segunda coluna você deve colocar os números do telefone, somente números e com o código de área, exemplo: 51999999999.</p><br/>
                </p> 
                <img src="<?= base_url('assets/images/exemplo_csv_mc_soft.jpg') ?>">
            </div>
        </div>
    </div>
</div>
<?php
if (!$this->session->userdata('bloco_unico')) {
    ?>
    <script>
        get_blocos(2);
    </script>
    <?php
}
?>
