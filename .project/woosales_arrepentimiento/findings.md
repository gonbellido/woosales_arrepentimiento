# Findings: Plugin WooCommerce - Reclamaciones de Arrepentimiento

## Decisiones de Diseño

### CPT vs Tabla Custom
**Decisión**: CPT `wa_reclamacion`.
**Razón**: Aprovecha la UI de WP Admin (listado, paginación, metaboxes). Los datos extra van en `post_meta`. Escala bien para un volumen moderado de reclamaciones.

### Código de Trámite
**Formato**: `{3 dígitos rand}-{nro_pedido}-{Ymd}`
**Ejemplo**: `847-4521-20260517`
**Colisión**: Extremadamente improbable (900 combinaciones × pedidos únicos × fecha). Validar unicidad con `WP_Query` antes de asignar.

### Estados (Workflow)
```
pendiente → en_revision → aprobada → reintegro_realizado
                        ↘ rechazada
```
Se almacenan como `post_status` custom o como `post_meta`. **Decisión**: `post_meta` con key `_wa_estado`, porque los `post_status` custom son más complejos de registrar y filtrar. Usamos `post_status = 'private'` para el CPT y el estado real en meta.

### Emails
Usar `wp_mail()` con templates HTML. No usar WC_Email para mantenerlo simple y no depender de la configuración de emails de WC (evita que el admin los desactive sin querer).

### Verificación de Pedido
`wc_get_order()` para validar. Si no existe, mostrar warning pero permitir continuar (casos de uso: pedidos offline, múltiples tiendas).

### Seguimiento Público
Shortcode `[wa_seguimiento]` que renderiza un input + resultado vía GET parameter `?codigo=XXX`. Sin AJAX para que el enlace del email funcione directamente.

### Footer Button
Usar hook `wp_footer` con un modal simple (CSS + JS vanilla). Alternativa: shortcode en widget de footer. **Decisión**: ambas opciones, configurable desde settings.
