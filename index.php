<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G&FHotel</title>
    <link rel="stylesheet" href="./styles.css" />

    <script src="script.js" defer></script>
</head>

<body>
    <?php require 'navbar.php'; ?>
    <div class="conteudo-principal">
        <div class="container-busca">
            <div class="abas-busca">
                <a href="#">Viajem dos <span>sonhos</span></a>    
            </div>
            <form class="formulario-busca" method="GET" action="reserva.php">
                <input type="text" name="destino" placeholder="Para onde você vai?">
                <input type="text" name="data" value="14 de out. - 20 de out.">
                <input type="text" name="hospedes" value="2 viajantes, 1 quarto">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <div class="banner-promocao">
            <h2>Reserve sua estadia perfeita em até 12x sem juros</h2>
            <div class="recursos-promocao">
                <div class="recurso-promocao">Ganhe recompensas em todas as diárias da sua estadia</div>
                <div class="recurso-promocao">Economize mais com os Preços para Associados</div>
                <div class="recurso-promocao">Opções de cancelamento grátis caso os planos mudem</div>
            </div>
        </div>
        <h2>Encontre a sua estadia ideal</h2>
        <div class="tipos-acomodacao">
            <?php
            $tiposAcomodacao = [
                ['imagem' => 'assets/spa.jpg', 'texto' => 'Tudo incluído'],
                ['imagem' => 'assets/resorte.jpg', 'texto' => 'Resort'],
                ['imagem' => 'assets/resorte.jpg', 'texto' => 'Hotel-fazenda'],
                ['imagem' => 'assets/hotelfazenda.jpg', 'texto' => 'Spa'],
                ['imagem' => 'assets/vistaparaomar.jpg', 'texto' => 'Vista para o mar']
            ];

            foreach ($tiposAcomodacao as $acomodacao) {
                echo '<div class="cartao-imagem">';
                echo '<img src="' . htmlspecialchars($acomodacao['imagem']) . '" alt="' . htmlspecialchars($acomodacao['texto']) . '">';
                echo '<span>' . htmlspecialchars($acomodacao['texto']) . '</span>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>