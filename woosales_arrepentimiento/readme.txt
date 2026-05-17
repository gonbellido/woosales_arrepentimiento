=== Botón de Arrepentimiento Argentina — WooSales ===
Contributors: woosales
Donate link: https://woosales.pro
Tags: woocommerce, arrepentimiento, ley 24240, reclamaciones, devoluciones, argentina, defensa del consumidor, boton de arrepentimiento, derecho de revocacion, cancelacion de compra
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin #1 en Argentina para gestionar Reclamaciones de Arrepentimiento en WooCommerce. Usá [wa_formulario_arrepentimiento] para el formulario y [wa_seguimiento] para el tracking. Cumplí con la Ley 24.240. Por **WooSales.pro**.

== Description ==

### ⚖️ Cumplimiento Legal Simplificado

**WooSales Arrepentimiento** es la solución definitiva para que tu tienda WooCommerce cumpla con el **derecho de arrepentimiento** exigido por la **Ley 24.240 de Defensa del Consumidor en Argentina**.

Desarrollado por [WooSales.pro](https://woosales.pro), este plugin cubre todo el flujo: desde el botón de arrepentimiento en tu sitio hasta la gestión administrativa de cada solicitud.

### ✨ Funcionalidades Principales

- **🔘 Botón de Arrepentimiento**: flotante en el footer de tu sitio, 100% configurable.
- **📝 Formulario Inteligente**: validación automática de fechas (10 días desde compra, antelación mínima según tipo de servicio).
- **🎫 Código de Trámite Inmediato**: generado y mostrado en pantalla al instante (formato `123-4521-20260517`).
- **📧 Emails Automáticos**: confirmación al cliente con enlace de seguimiento + notificación al administrador.
- **🔍 Seguimiento Público**: el cliente consulta el estado de su reclamación con su código, sin necesidad de login.
- **📊 Backoffice Simple**: listado con todos los datos a la vista, filtro por estado, cambio de estado con un clic.
- **❌ Motivo de Rechazo**: transparente para el cliente, visible desde la página de seguimiento.
- **📋 Workflow Completo**: Pendiente → En Revisión → Aprobada → Reintegro Realizado (o Rechazada).
- **📱 100% Responsive**: funciona en mobile, tablet y desktop.

### 🚀 Desarrollado por WooSales.pro

En **[WooSales.pro](https://woosales.pro)** somos especialistas en WooCommerce para el mercado argentino. Ofrecemos:

- Plugins para facturación electrónica, Mercado Pago, envíos y logística.
- Integraciones con AFIP, ARCA y sistemas de gestión locales.
- Desarrollo a medida y soporte técnico especializado.

👉 **¿Necesitás adaptar este plugin a tu negocio?** [Contactanos en WooSales.pro](https://woosales.pro/contacto)

---

### 📦 Instalación Rápida

1. Instalá el plugin desde el repositorio de WordPress o subí la carpeta `woosales_arrepentimiento` a `/wp-content/plugins/`.
2. Activá el plugin (requiere WooCommerce activo).
3. Andá a **Reclamaciones → Configuración** y seleccioná:
   - La página donde pusiste el shortcode `[wa_formulario_arrepentimiento]`
   - La página donde pusiste el shortcode `[wa_seguimiento]`
4. ¡Listo! El botón de arrepentimiento aparece automáticamente en el footer.

### 📖 Uso

#### Shortcodes

- **`[wa_formulario_arrepentimiento]`** — Colocalo en una página para mostrar el formulario de reclamación con el texto legal completo.
- **`[wa_seguimiento]`** — Colocalo en otra página para que los clientes puedan consultar el estado de su trámite.

#### Widget / Bloque

También podés usar el shortcode en cualquier widget de texto o bloque de HTML en Gutenberg.

== Frequently Asked Questions ==

= ¿Necesito WooCommerce para usar este plugin? =

Sí. El plugin está diseñado para integrarse con WooCommerce. Verifica los pedidos contra tu tienda y precarga datos del cliente. Si WooCommerce no está activo, el plugin no se inicializará.

= ¿Qué pasa si el pedido no existe en WooCommerce? =

El formulario muestra una advertencia pero permite enviar la reclamación igual. Esto cubre escenarios como pedidos offline, reservas telefónicas o migraciones desde otros sistemas.

= ¿Se procesa el reintegro automáticamente? =

No. **WooSales Arrepentimiento** gestiona el trámite administrativo. El reintegro lo realiza manualmente el administrador. Esto te da control total sobre cada devolución.

= ¿El cliente puede ver por qué se rechazó su solicitud? =

Sí. Cuando un administrador cambia el estado a "Rechazada", debe completar un **motivo de rechazo obligatorio** que es visible para el cliente en la página de seguimiento. Transparencia total.

= ¿Funciona con cualquier theme? =

Sí. Los estilos son autocontenidos y el botón del footer es compatible con cualquier theme. Probado con Storefront, Divi, Astra, GeneratePress y temas personalizados.

= ¿Cumple realmente con la Ley 24.240? =

Cubrimos todos los requisitos técnicos exigidos: botón visible, formulario accesible, entrega inmediata de código de trámite, y comunicación electrónica. **Siempre recomendamos consultar con un asesor legal** para validar la implementación en tu caso particular.

= ¿Ofrecen soporte? =

¡Sí! Dejá tu consulta en el [foro de soporte del plugin](https://wordpress.org/support/plugin/woosales_arrepentimiento/) o contactanos directamente en [WooSales.pro](https://woosales.pro/contacto).

= ¿Puedo personalizar los textos legales? =

Los textos se basan en la Ley 24.240. Si necesitás adaptarlos, [contactanos](https://woosales.pro/contacto) para un desarrollo a medida.

== Screenshots ==

1. Formulario público de arrepentimiento con texto legal y campos de pedido, nombre, email y fecha de reserva.
2. Respuesta exitosa con código de trámite inmediato y enlace de seguimiento.
3. Email que recibe el cliente con código, estado y botón para consultar.
4. Página de seguimiento público donde el cliente consulta el estado con su código.
5. Listado de reclamaciones en el backoffice con columnas: código, pedido, cliente, email, estado, fecha.
6. Metabox de cambio de estado con motivo de rechazo obligatorio e historial de cambios.
7. Página de configuración: email admin, páginas de shortcode, botón footer.

== Changelog ==

= 1.0.0 — 2026-05-17 =
* 🚀 Lanzamiento inicial.
* CPT Reclamaciones con workflow de 5 estados.
* Formulario público con validación legal de fechas.
* Código de trámite inmediato (formato compuesto).
* Emails HTML automáticos (cliente + admin).
* Página de seguimiento público.
* Backoffice con columnas personalizadas, filtros y cambio rápido de estado.
* Motivo de rechazo visible para el cliente.
* Botón de arrepentimiento flotante en footer.
* Página de configuración del plugin.

== Upgrade Notice ==

= 1.0.0 =
Primera versión. Instalá, configurá las páginas de shortcode y activá el botón de arrepentimiento. Recomendamos probar en un entorno de staging antes de pasar a producción.

== ¿Te gusta WooSales Arrepentimiento? ==

Si este plugin te ayudó a cumplir con la Ley 24.240 y simplificó la gestión de reclamaciones en tu tienda, **¡dejanos una review!** ⭐⭐⭐⭐⭐

👉 [Dejar una reseña en WordPress.org](https://wordpress.org/support/plugin/woosales_arrepentimiento/reviews/#new-post)

Tu valoración nos ayuda a mantener el plugin actualizado y gratuito. ¡Gracias por apoyar el software libre argentino!

---

**WooSales Arrepentimiento** es desarrollado y mantenido por **[WooSales.pro](https://woosales.pro)** — Soluciones profesionales de WooCommerce para Argentina.
