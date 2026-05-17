=== WooSales Arrepentimiento ===
Contributors: woosales
Donate link: https://woosales.com
Tags: woocommerce, arrepentimiento, ley 24240, reclamaciones, devoluciones, argentina
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin WooCommerce para gestionar Reclamaciones de Arrepentimiento según Ley 24.240 (Argentina).

== Description ==

WooSales Arrepentimiento permite a tus clientes ejercer el derecho de arrepentimiento
de compra exigido por la Ley 24.240 de Defensa del Consumidor en Argentina.

= Funcionalidades =

* Formulario público de reclamación con texto legal obligatorio.
* Generación inmediata de código de trámite (`123-4521-20260517`).
* Email automático al cliente con enlace de seguimiento.
* Notificación por email al administrador.
* Página pública de seguimiento: el cliente consulta el estado con su código.
* Backoffice simple: listado con datos del cliente, filtro por estado, cambio de estado rápido.
* Workflow de 4 estados: Pendiente → En Revisión → Aprobada → Reintegro Realizado, o Rechazada.
* Motivo de rechazo visible para el cliente.
* Botón flotante "Botón de Arrepentimiento" en el footer.
* Validación de fechas (10 días desde compra, antelación mínima).

= Shortcodes =

* `[wa_formulario_arrepentimiento]` — Formulario de reclamación.
* `[wa_seguimiento]` — Página de seguimiento por código.

= Requisitos =

* WordPress 5.8+
* WooCommerce 5.0+
* PHP 7.4+

== Installation ==

1. Subir la carpeta `woosales_arrepentimiento` a `/wp-content/plugins/`.
2. Activar el plugin desde el menú Plugins de WordPress.
3. Ir a Reclamaciones → Configuración para elegir las páginas de formulario y seguimiento.
4. Crear una página con el shortcode `[wa_formulario_arrepentimiento]`.
5. Crear otra página con el shortcode `[wa_seguimiento]`.
6. ¡Listo! El botón de arrepentimiento aparecerá en el footer.

== Frequently Asked Questions ==

= ¿Necesito WooCommerce para usar este plugin? =

Sí. El plugin verifica pedidos contra WooCommerce y requiere que esté activo.

= ¿Qué pasa si el pedido no existe en WooCommerce? =

El formulario muestra una advertencia pero permite enviar la reclamación igual.
Esto cubre casos de pedidos offline o de otros sistemas.

= ¿Se procesa el reintegro automáticamente? =

No. El plugin gestiona el trámite administrativo. El reintegro lo realiza
manualmente el administrador según las políticas de la empresa.

= ¿El cliente puede ver el motivo de un rechazo? =

Sí. Cuando el administrador cambia una reclamación a "Rechazada", debe ingresar
un motivo que será visible para el cliente en la página de seguimiento.

== Changelog ==

= 1.0.0 =
* Versión inicial.
* CPT Reclamaciones, formulario público, código de trámite.
* Emails automáticos, página de seguimiento.
* Backoffice con columnas personalizadas y cambio de estado.
* Botón footer y configuración del plugin.

== Upgrade Notice ==

= 1.0.0 =
Primera versión. Instalar y configurar las páginas de shortcode.
