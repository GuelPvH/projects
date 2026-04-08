<?php
namespace luca\dinner;

class CookieHelper {
    
    public static function restaurarSessao() {
        // Se a sessão principal expirou mas os cookies persistem, restaura.
        if (!isset($_SESSION['usuario_nome']) && isset($_COOKIE['app_cliente_nome']) && isset($_COOKIE['app_cliente_tel'])) {
            $_SESSION['usuario_nome'] = $_COOKIE['app_cliente_nome'];
            $_SESSION['usuario_telefone'] = $_COOKIE['app_cliente_tel'];
        }
    }

    public static function registrarCookiesLogin($nome, $telefone) {
        // Salva por 30 dias (HttpOnly ativado)
        setcookie('app_cliente_nome', $nome, time() + 86400 * 30, '/', '', false, true);
        setcookie('app_cliente_tel', $telefone, time() + 86400 * 30, '/', '', false, true);
    }

    public static function destruirCookies() {
        // Tempo no passado para forçar o navegador a deletar
        setcookie('app_cliente_nome', '', time() - 3600, '/');
        setcookie('app_cliente_tel', '', time() - 3600, '/');
    }

}
