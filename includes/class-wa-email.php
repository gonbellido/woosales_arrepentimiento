<?php
/**
 * Emails transaccionales del plugin.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Email
{
    /**
     * Enviar email al cliente con código de trámite y enlace de seguimiento.
     */
    public function enviar_cliente(int $post_id, string $codigo, string $email, string $nombre): bool
    {
        $pagina_seguimiento = get_option('wa_pagina_seguimiento', '');
        $enlace_seguimiento = $pagina_seguimiento
            ? add_query_arg('codigo', $codigo, get_permalink((int) $pagina_seguimiento))
            : '';

        $sitio = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $subject = sprintf(
            '[%s] %s — %s',
            $sitio,
            __('Reclamación de Arrepentimiento', 'woosales-arrepentimiento'),
            $codigo
        );

        $message = $this->get_template_cliente($codigo, $nombre, $enlace_seguimiento);

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $sitio . ' <' . get_option('admin_email') . '>',
        ];

        return wp_mail($email, $subject, $message, $headers);
    }

    /**
     * Notificar al admin de una nueva reclamación.
     */
    public function enviar_admin(int $post_id, string $codigo, string $pedido_id, string $nombre, string $email): bool
    {
        $admin_email = get_option('wa_admin_email', get_option('admin_email'));
        $sitio = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $edit_link = admin_url('post.php?post=' . $post_id . '&action=edit');

        $subject = sprintf(
            '[%s] %s — Pedido #%s',
            $sitio,
            __('Nueva Reclamación de Arrepentimiento', 'woosales-arrepentimiento'),
            $pedido_id
        );

        $message = sprintf(
            '<h2>%s</h2>
            <p><strong>%s:</strong> %s</p>
            <p><strong>%s:</strong> %s</p>
            <p><strong>%s:</strong> #%s</p>
            <p><strong>%s:</strong> %s</p>
            <p><strong>%s:</strong> <a href="%s">%s</a></p>
            <p><strong>%s:</strong> %s</p>',
            esc_html__('Nueva Reclamación Recibida', 'woosales-arrepentimiento'),
            esc_html__('Nombre', 'woosales-arrepentimiento'),
            esc_html($nombre),
            esc_html__('Email', 'woosales-arrepentimiento'),
            esc_html($email),
            esc_html__('Pedido', 'woosales-arrepentimiento'),
            esc_html($pedido_id),
            esc_html__('Código', 'woosales-arrepentimiento'),
            esc_html($codigo),
            esc_html__('Gestionar', 'woosales-arrepentimiento'),
            esc_url($edit_link),
            esc_html__('Ver en el panel', 'woosales-arrepentimiento'),
            esc_html__('Estado', 'woosales-arrepentimiento'),
            esc_html(WA_Status::label(WA_Status::default()))
        );

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $sitio . ' <' . get_option('admin_email') . '>',
        ];

        return wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Template HTML del email al cliente.
     */
    private function get_template_cliente(string $codigo, string $nombre, string $enlace_seguimiento): string
    {
        $sitio = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $estado = WA_Status::label(WA_Status::default());

        return sprintf(
            '<div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;color:#333;">
                <div style="background:#f8f9fa;padding:20px;text-align:center;border-bottom:3px solid #0073aa;">
                    <h1 style="color:#0073aa;margin:0;">%s</h1>
                    <p style="font-size:14px;color:#666;">%s</p>
                </div>
                <div style="padding:20px;">
                    <p>%s <strong>%s</strong>,</p>
                    <p>%s</p>
                    <div style="background:#e8f5e9;border:1px solid #4caf50;border-radius:4px;padding:15px;margin:20px 0;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#666;">%s</p>
                        <p style="font-size:28px;font-weight:bold;color:#2e7d32;margin:8px 0;letter-spacing:2px;">%s</p>
                        <p style="margin:0;font-size:12px;color:#666;">%s: <strong>%s</strong></p>
                    </div>
                    <p>%s</p>
                    %s
                    <p>%s</p>
                    <p>%s<br><strong>%s</strong></p>
                </div>
                <div style="background:#f8f9fa;padding:15px;text-align:center;font-size:12px;color:#999;">
                    <p>%s — <a href="%s" style="color:#0073aa;">%s</a></p>
                </div>
            </div>',
            esc_html__('Reclamación de Arrepentimiento', 'woosales-arrepentimiento'),
            esc_html__('Ley 24.240 — Derecho de Revocación', 'woosales-arrepentimiento'),
            esc_html__('Hola', 'woosales-arrepentimiento'),
            esc_html($nombre),
            esc_html__('Hemos recibido tu solicitud de arrepentimiento. A continuación encontrarás los datos de tu trámite:', 'woosales-arrepentimiento'),
            esc_html__('CÓDIGO DE TRÁMITE', 'woosales-arrepentimiento'),
            esc_html($codigo),
            esc_html__('Estado actual', 'woosales-arrepentimiento'),
            esc_html($estado),
            esc_html__('Recibirás una notificación por correo cuando el estado de tu reclamación cambie.', 'woosales-arrepentimiento'),
            $enlace_seguimiento ? sprintf(
                '<p style="text-align:center;"><a href="%s" style="display:inline-block;background:#0073aa;color:#fff;padding:12px 24px;text-decoration:none;border-radius:4px;font-weight:bold;">%s</a></p>',
                esc_url($enlace_seguimiento),
                esc_html__('Consultar Estado de mi Reclamación', 'woosales-arrepentimiento')
            ) : '',
            esc_html__('Si tenés alguna consulta, no dudes en contactarnos.', 'woosales-arrepentimiento'),
            esc_html__('Atentamente,', 'woosales-arrepentimiento'),
            esc_html($sitio),
            esc_html($sitio),
            esc_url(home_url()),
            esc_html__('Visitar sitio', 'woosales-arrepentimiento')
        );
    }
}
