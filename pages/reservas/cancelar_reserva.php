<?php
include("../../conectarbd.php");

// Incluir classes de email (Outlook local)
require_once("../../php/outlook-email-sender.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    // Buscar dados da reserva e morador antes de excluir
    $reserva_query = mysqli_query($conn, "
        SELECT r.*, m.nome, m.email, m.bloco, m.torre 
        FROM tb_reservas r 
        LEFT JOIN tb_moradores m ON r.id_morador = m.id_moradores 
        WHERE r.id_reservas = $id
    ");
    $reserva_data = mysqli_fetch_array($reserva_query);
    
    $sql = "DELETE FROM tb_reservas WHERE id_reservas = $id";
    
    if (mysqli_query($conn, $sql)) {
        // Enviar email de cancelamento se o morador tiver email
        if ($reserva_data && $reserva_data['email']) {
            $reservaData = [
                'local' => $reserva_data['local'],
                'data' => $reserva_data['data'],
                'horario' => $reserva_data['horario'],
                'tempo_duracao' => $reserva_data['tempo_duracao'],
                'descricao' => $reserva_data['descricao']
            ];
            
            $moradorData = [
                'nome' => $reserva_data['nome'],
                'email' => $reserva_data['email'],
                'bloco' => $reserva_data['bloco'],
                'torre' => $reserva_data['torre']
            ];
            
            $emailSender = new OutlookEmailSender();
            $result = $emailSender->sendReservaCancelamento($reservaData, $moradorData);
            
            // Se criou arquivo .eml, mostrar link para download
            if ($result['success'] && isset($result['download_url'])) {
                echo "<script>
                    alert('Reserva cancelada! Arquivo de email de cancelamento criado.');
                    window.open('" . $result['download_url'] . "', '_blank');
                    window.location = 'consultar_reservas.php';
                </script>";
                exit;
            }
        }
        
        echo "<script>alert('Reserva cancelada com sucesso!'); window.location = 'consultar_reservas.php';</script>";
    } else {
        echo "<script>alert('Erro ao cancelar reserva: " . mysqli_error($conn) . "'); window.location = 'consultar_reservas.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_reservas.php';</script>";
}

mysqli_close($conn);
?>