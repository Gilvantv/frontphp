<?php

$host = 'localhost';
$dbname = 'gfhotel';
$username = 'seu_usuario';
$password = 'sua_senha';

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function validarCPF($cpf) {
 
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    

    if (strlen($cpf) != 11) return false;
    

    if (preg_match('/^(\d)\1*$/', $cpf)) return false;
    
 
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }
    $resto = $soma % 11;
    $digitoVerificador1 = ($resto < 2) ? 0 : (11 - $resto);
    
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }
    $resto = $soma % 11;
    $digitoVerificador2 = ($resto < 2) ? 0 : (11 - $resto);
    

    return ($cpf[9] == $digitoVerificador1 && $cpf[10] == $digitoVerificador2);
}


function validarTelefone($telefone) {
 
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    

    return (strlen($telefone) == 10 || strlen($telefone) == 11);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $erros = [];

    $nome = sanitizeInput($_POST['nome'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $telefone = sanitizeInput($_POST['telefone'] ?? '');
    $cpf = sanitizeInput($_POST['cpf'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';


    if (empty($nome) || strlen($nome) < 2) {
        $erros[] = "Nome inválido";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }

    if (!validarTelefone($telefone)) {
        $erros[] = "Telefone inválido";
    }

    if (!validarCPF($cpf)) {
        $erros[] = "CPF inválido";
    }

    if (strlen($senha) < 8) {
        $erros[] = "Senha deve ter no mínimo 8 caracteres";
    }

    if ($senha !== $confirmaSenha) {
        $erros[] = "Senhas não coincidem";
    }

    
    if (empty($erros)) {
        try {
            
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, cpf, senha) VALUES (?, ?, ?, ?, ?)");
            
       
            $resultado = $stmt->execute([$nome, $email, $telefone, $cpf, $senhaHash]);

            if ($resultado) {
               
                echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
                exit();
            } else {
                $erros[] = "Erro ao realizar cadastro";
            }
        } catch(PDOException $e) {
            
            $erros[] = "Erro de conexão: " . $e->getMessage();
        }
    }

    
    if (!empty($erros)) {
        echo json_encode(['sucesso' => false, 'erros' => $erros]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <?php require 'navbar.php'; ?>

    <div class="conteudo-principal">
        <div class="container-formulariocads">
            <h2>Cadastro</h2>
            <form id="form-cadastro" class="formulario-cadastro" method="POST" action="">
                <div class="grupo-campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="grupo-campo">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="grupo-campo">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>
                <div class="grupo-campo">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>
                <div class="grupo-campo">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="grupo-campo">
                    <label for="confirma_senha">Confirma Senha</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" required>
                </div>
                <div class="grupo-campo">
                    <input type="checkbox" id="nao-sou-robo" required>
                    <label for="nao-sou-robo">Não sou um robô</label>
                </div>
                <a href="login.php"><button type="submit" class="botao-cadastrar">Criar Cadastro</button></a>
            </form>
            <div id="mensagem" style="display: none; margin-top: 20px; color: green;">Cadastro realizado!</div>
            
      
            <div id="erros" style="color: red;"></div>
        </div>
    </div>

    <div class="rodape">
        <div class="texto-rodape">Todos os direitos reservados</div>
    </div>

    <script>
    document.getElementById('form-cadastro').addEventListener('submit', function(e) {
        e.preventDefault();
        
        
        document.getElementById('mensagem').style.display = 'none';
        document.getElementById('erros').innerHTML = '';

       
        var formData = new FormData(this);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                document.getElementById('mensagem').style.display = 'block';
                document.getElementById('form-cadastro').reset();
            } else {

                var errosDiv = document.getElementById('erros');
                data.erros.forEach(function(erro) {
                    var p = document.createElement('p');
                    p.textContent = erro;
                    errosDiv.appendChild(p);
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    });
    </script>
</body>
</html>