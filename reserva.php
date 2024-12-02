<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $documento = filter_input(INPUT_POST, 'documento', FILTER_SANITIZE_STRING);
    $dataEntrada = filter_input(INPUT_POST, 'data-entrada', FILTER_SANITIZE_STRING);
    $dataSaida = filter_input(INPUT_POST, 'data-saida', FILTER_SANITIZE_STRING);
    $numeroQuarto = filter_input(INPUT_POST, 'numero-quarto', FILTER_VALIDATE_INT);
    $metodoPagamento = filter_input(INPUT_POST, 'pagamento', FILTER_SANITIZE_STRING);

   
    $erros = [];

    if (empty($nome)) {
        $erros[] = "Nome é obrigatório";
    }

    if (empty($documento)) {
        $erros[] = "Documento (CPF/CNPJ) é obrigatório";
    }

    if (empty($dataEntrada)) {
        $erros[] = "Data de entrada é obrigatória";
    }

    if (empty($dataSaida)) {
        $erros[] = "Data de saída é obrigatória";
    }

    if ($dataEntrada >= $dataSaida) {
        $erros[] = "Data de saída deve ser posterior à data de entrada";
    }

    if ($numeroQuarto === false) {
        $erros[] = "Número do quarto inválido";
    }

    if (empty($metodoPagamento)) {
        $erros[] = "Selecione um método de pagamento";
    }

    if (empty($erros)) {
       
        $_SESSION['reserva_sucesso'] = true;
        $_SESSION['reserva_dados'] = [
            'nome' => $nome,
            'documento' => $documento,
            'data_entrada' => $dataEntrada,
            'data_saida' => $dataSaida,
            'numero_quarto' => $numeroQuarto,
            'metodo_pagamento' => $metodoPagamento
        ];

        
        header("Location: confirmacao.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G&FHotel - Reserva</title>
    <link rel="stylesheet" href="./styles.css" />

    <script src="script.js"></script>
</head>

<body>
    <?php require 'navbar.php'; ?>
    <div class="conteudo-principal">
        <div class="container-formulario">
            <h2>Faça sua Reserva</h2>
            
            <?php
           
            if (!empty($erros)) {
                echo '<div class="mensagem-erro">';
                foreach ($erros as $erro) {
                    echo '<p>' . htmlspecialchars($erro) . '</p>';
                }
                echo '</div>';
            }
            ?>

            <form class="formulario-reserva" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="grupo-campo">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>">
                </div>

                <div class="grupo-campo">
                    <label for="documento">CPF ou CNPJ</label>
                    <input type="text" id="documento" name="documento" required value="<?php echo isset($documento) ? htmlspecialchars($documento) : ''; ?>">
                </div>

                <div class="grupo-campo">
                    <label for="data-entrada">Data de Entrada</label>
                    <input type="date" id="data-entrada" name="data-entrada" required value="<?php echo isset($dataEntrada) ? htmlspecialchars($dataEntrada) : ''; ?>">
                </div>

                <div class="grupo-campo">
                    <label for="data-saida">Data de Saída</label>
                    <input type="date" id="data-saida" name="data-saida" required value="<?php echo isset($dataSaida) ? htmlspecialchars($dataSaida) : ''; ?>">
                </div>

                <div class="grupo-campo">
                    <label for="numero-quarto">Número do Quarto</label>
                    <input type="number" id="numero-quarto" name="numero-quarto" required value="<?php echo isset($numeroQuarto) ? htmlspecialchars($numeroQuarto) : ''; ?>">
                </div>

                <div class="grupo-campo">
                    <label>Método de Pagamento</label>
                    <div class="opcoes-pagamento">
                        <div class="opcao">
                            <input type="radio" id="pix" name="pagamento" value="pix" 
                                <?php echo (isset($metodoPagamento) && $metodoPagamento == 'pix') ? 'checked' : ''; ?>>
                            <label for="pix">PIX</label>
                        </div>
                        <div class="opcao">
                            <input type="radio" id="cartao" name="pagamento" value="cartao"
                                <?php echo (isset($metodoPagamento) && $metodoPagamento == 'cartao') ? 'checked' : ''; ?>>
                            <label for="cartao">Cartão</label>
                        </div>
                        <div class="opcao">
                            <input type="radio" id="dinheiro" name="pagamento" value="dinheiro"
                                <?php echo (isset($metodoPagamento) && $metodoPagamento == 'dinheiro') ? 'checked' : ''; ?>>
                            <label for="dinheiro">Dinheiro</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="botao-reservar">Confirmar Reserva</button>
            </form>
        </div>
    </div>
</body>

</html>