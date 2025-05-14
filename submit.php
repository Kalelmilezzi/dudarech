<?php
// Configurações para o envio de e-mail
$destinatario = "contato@dudarech.com.br";
$assunto = "Novo contato do site";

// Recebendo dados do formulário
$nome = isset($_POST['nome']) ? filter_var($_POST['nome'], FILTER_SANITIZE_STRING) : '';
$whatsapp = isset($_POST['whatsapp']) ? filter_var($_POST['whatsapp'], FILTER_SANITIZE_STRING) : '';
$mensagem = isset($_POST['mensagem']) ? filter_var($_POST['mensagem'], FILTER_SANITIZE_STRING) : '';

// Validação simples
$erro = false;
$mensagemErro = '';

if (empty($nome)) {
    $erro = true;
    $mensagemErro .= "O nome é obrigatório. ";
}

if (empty($whatsapp)) {
    $erro = true;
    $mensagemErro .= "O WhatsApp é obrigatório. ";
}

if (empty($mensagem)) {
    $erro = true;
    $mensagemErro .= "A mensagem é obrigatória. ";
}

// Se não houver erros, envia o e-mail
if (!$erro) {
    // Corpo do e-mail
    $corpo = "Nome: " . $nome . "\n";
    $corpo .= "WhatsApp: " . $whatsapp . "\n";
    $corpo .= "Mensagem: " . $mensagem . "\n";
    
    // Cabeçalhos do e-mail
    $headers = "From: site@dudarech.com.br" . "\r\n";
    $headers .= "Reply-To: " . $whatsapp . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Tentativa de envio
    $enviado = mail($destinatario, $assunto, $corpo, $headers);
    
    if ($enviado) {
        // Sucesso no envio
        $resposta = array(
            'status' => 'sucesso',
            'mensagem' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        );
    } else {
        // Falha no envio
        $resposta = array(
            'status' => 'erro',
            'mensagem' => 'Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente ou entre em contato pelo WhatsApp.'
        );
    }
} else {
    // Retorna os erros de validação
    $resposta = array(
        'status' => 'erro',
        'mensagem' => $mensagemErro
    );
}

// Retorna a resposta como JSON para requisições AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($resposta);
    exit;
} else {
    // Redirecionamento para requisições normais
    if ($resposta['status'] == 'sucesso') {
        header('Location: index.html?enviado=1#contato');
    } else {
        header('Location: index.html?erro=1&msg=' . urlencode($resposta['mensagem']) . '#contato');
    }
    exit;
}
?> 