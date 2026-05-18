# Plan: Plugin WooCommerce - Reclamaciones de Arrepentimiento

**Issue**: N/A (proyecto nuevo)
**Phase**: implement
**Started**: 2026-05-17

## Goal

Crear un plugin WooCommerce completo para gestionar reclamaciones de arrepentimiento (Ley 24.240 Argentina). El plugin debe ser autónomo, funcionar con cualquier theme, y ser fácil de instalar y usar.

## Approach

**Tech Stack**: PHP 7.4+, WordPress 6.x, WooCommerce 8.x, jQuery (incluido en WP), CSS vanilla.

**Arquitectura**: Plugin modular con clases separadas por responsabilidad. CPT como núcleo de datos. Shortcode para frontend. AJAX para interactividad. `wp_mail()` para emails.

**Persistencia**: WordPress CPT `wa_reclamacion` con post_meta para datos extendidos (código de trámite, pedido_id, email, estado, notas, log de cambios).

## Estructura de Archivos

```
woosales_arrepentimiento/
├── woosales_arrepentimiento.php          # Bootstrap del plugin
├── readme.txt                            # Documentación para WP.org
├── includes/
│   ├── class-wa-loader.php               # Autoloader y registro de hooks
│   ├── class-wa-post-type.php            # CPT + taxonomía de estado + metaboxes
│   ├── class-wa-form-handler.php         # Shortcode + validación AJAX + generación código
│   ├── class-wa-email.php                # Emails transaccionales
│   ├── class-wa-admin.php                # Columnas admin, filtros, estilos
│   ├── class-wa-status.php              # Workflow de estados
│   ├── class-wa-tracking.php            # Página pública de seguimiento
│   └── class-wa-settings.php            # Página de configuración
├── assets/
│   ├── css/
│   │   ├── wa-form.css                  # Estilos del formulario
│   │   └── wa-admin.css                 # Estilos del admin
│   └── js/
│       ├── wa-form.js                   # AJAX + validación frontend
│       └── wa-admin.js                  # Quick edit estados
├── templates/
│   ├── form-reclamacion.php             # Template del formulario público
│   ├── form-success.php                 # Respuesta exitosa con código
│   ├── tracking-page.php                # Página de seguimiento
│   └── email-cliente.php                # Template email cliente
└── languages/
    └── woosales_arrepentimiento.pot     # i18n
```

## Implementation Steps

### Fase 1: Fundación del Plugin
- [ ] 1.1: Crear estructura de carpetas
- [ ] 1.2: Crear `woosales_arrepentimiento.php` (plugin header, checks, constants)
- [ ] 1.3: Crear `class-wa-loader.php` (autoloader simple, registro de hooks)

### Fase 2: Custom Post Type y Estados
- [ ] 2.1: Crear `class-wa-post-type.php` (CPT `wa_reclamacion`, no pública, con soporte title+editor)
- [ ] 2.2: Crear `class-wa-status.php` (estados: pendiente, en_revision, aprobada, rechazada, reintegro_realizado + funciones helper)

### Fase 3: Formulario Público y Código de Trámite
- [ ] 3.1: Crear `class-wa-form-handler.php` (shortcode `[wa_formulario_arrepentimiento]`)
- [ ] 3.2: Crear template `form-reclamacion.php` con texto legal y campos
- [ ] 3.3: Crear `wa-form.js` con validación y envío AJAX
- [ ] 3.4: Implementar generación de código de trámite (`{3digitos}-{pedido}-{fechaYmd}`)
- [ ] 3.5: Crear template `form-success.php` mostrando código inmediato
- [ ] 3.6: Integrar verificación de pedido en WooCommerce

### Fase 4: Emails Automáticos
- [ ] 4.1: Crear `class-wa-email.php`
- [ ] 4.2: Email al cliente (código + enlace seguimiento) usando template HTML
- [ ] 4.3: Email al admin (notificación nueva reclamación)

### Fase 5: Seguimiento Público
- [ ] 5.1: Crear `class-wa-tracking.php` (shortcode `[wa_seguimiento]`)
- [ ] 5.2: Template `tracking-page.php` con buscador por código
- [ ] 5.3: Mostrar estado actual y timeline si existe

### Fase 6: Backoffice Admin
- [ ] 6.1: Crear `class-wa-admin.php` (columnas personalizadas, filtros por estado)
- [ ] 6.2: Quick edit o metabox para cambiar estado desde listado
- [ ] 6.3: Botón footer vía hook WordPress `wp_footer` o shortcode en widget
- [ ] 6.4: Estilos admin `wa-admin.css`

### Fase 7: Assets y Configuración
- [ ] 7.1: Crear `wa-form.css` (responsive, accesible)
- [ ] 7.2: Crear `class-wa-settings.php` (página en WooCommerce > Settings)
- [ ] 7.3: Crear `readme.txt` documentación

## Current Step

Fase 1: Inicializar estructura y archivo principal del plugin.

## Blockers

Ninguno.

## Validation

- [ ] Plugin se activa sin errores fatales
- [ ] Shortcode `[wa_formulario_arrepentimiento]` renderiza formulario
- [ ] Envío de formulario crea CPT con código de trámite y envía emails
- [ ] Shortcode `[wa_seguimiento]` permite consultar estado por código
- [ ] Admin puede cambiar estados desde listado
