<link rel="stylesheet" href="alertas.css">

<div class="md-overlay" id="md-overlay" onclick="if(event.target===this)mdClose()">
    <div class="md-dialog">
        <div class="md-header">
            <div class="md-icon-wrap" id="md-icon-wrap">
                <span id="md-icon"></span>
            </div>
            <p class="md-title" id="md-title"></p>
        </div>
        <div class="md-body">
            <p class="md-msg" id="md-msg"></p>
        </div>
        <div class="md-actions">
            <button class="md-btn" id="md-okbtn" onclick="mdClose()">Aceptar</button>
        </div>
    </div>
</div>
<div class="md-overlay" id="md-confirm-overlay">
    <div class="md-dialog">
        <div class="md-header">
            <div class="md-icon-wrap" id="md-confirm-icon"></div>
            <p class="md-title" id="md-confirm-title"></p>
        </div>
        <div class="md-body">
            <p class="md-msg" id="md-confirm-msg"></p>
        </div>
        <div class="md-actions">
            <button class="md-btn md-btn-cancel" onclick="mdConfirmClose()">Cancelar</button>
            <button class="md-btn md-btn-confirm" id="md-confirm-ok" onclick="mdConfirmOk()">Confirmar</button>
        </div>
    </div>
</div>
<script src="alertas.js"></script>

<?php if (isset($_SESSION['alerta'])): 
    $a = $_SESSION['alerta'];
    unset($_SESSION['alerta']);
?>
<script>
    mdAlert('<?= $a['tipo'] ?>', '<?= $a['titulo'] ?>', '<?= $a['msg'] ?>');
</script>
<?php endif; ?>