# Specification: Plugin WooCommerce - Reclamaciones de Arrepentimiento

**Issue**: N/A (proyecto nuevo)
**Status**: draft
**Plugin slug**: woosales_arrepentimiento

## Overview

Plugin para WooCommerce que permite a clientes ejercer el derecho de arrepentimiento según Ley 24.240 (Argentina). Provee un formulario público, genera un código de trámite inmediato, envía emails automáticos, y permite al administrador gestionar el estado de cada solicitud desde el backoffice de WordPress.

## Requirements

### Must Have

- [ ] **CPT "Reclamaciones"**: Custom Post Type `wa_reclamacion` para almacenar cada solicitud
- [ ] **Formulario público vía shortcode** `[wa_formulario_arrepentimiento]`: campos para número de pedido, email, nombre; con validación AJAX
- [ ] **Generación de código de trámite**: compuesto por `{3 dígitos aleatorios}-{nro_pedido}-{fecha Ymd}`, generado inmediatamente al enviar y mostrado en pantalla
- [ ] **Email automático al cliente**: con el código de trámite + enlace de seguimiento público
- [ ] **Email automático al admin**: notificando nueva solicitud
- [ ] **Estados de la reclamación**: workflow de estados (Pendiente, En Revisión, Aprobada, Rechazada, Reintegro Realizado)
- [ ] **Página pública de seguimiento**: cliente ingresa código de trámite y ve el estado actual
- [ ] **Backoffice simple**: columna de estado en listado CPT + quick edit o metabox para cambiar estado + datos del cliente visibles en columnas
- [ ] **Widget/Botón footer**: botón "Botón de Arrepentimiento" en footer del sitio que abre el formulario en modal o redirige a la página del formulario
- [ ] **Texto legal en formulario**: incluir la aclaración de temporalidad y política de reintegro requerida por ley
- [ ] **Validación de pedido**: verificar que el número de pedido existe en WooCommerce antes de crear la reclamación
- [ ] **Datos del cliente precargados**: si el pedido existe, precargar nombre y email desde el pedido

### Should Have

- [ ] **Filtro por estado en admin**: dropdown para filtrar reclamaciones por estado en el listado
- [ ] **Log de cambios de estado**: registrar quién y cuándo cambió cada estado con nota opcional
- [ ] **Nota interna**: campo para que el admin agregue notas visibles solo en backoffice
- [ ] **Motivo de rechazo**: al cambiar estado a "Rechazada", campo obligatorio con la razón; visible para el cliente en la página de seguimiento
- [ ] **Exportación CSV**: exportar listado de reclamaciones desde admin
- [ ] **Configuración en WooCommerce > Settings**: página de settings del plugin (emails destino, textos personalizables)

### Won't Have

- Procesamiento automático de reintegros (esto es manual o vía otro sistema)
- Integración con pasarelas de pago para anular cobros
- Múltiples formularios con distintos textos legales

## Acceptance Criteria

- [ ] Cliente completa formulario con nro de pedido, recibe código de trámite inmediatamente en pantalla
- [ ] Cliente recibe email con código y enlace de seguimiento
- [ ] Cliente puede consultar estado desde el enlace de seguimiento
- [ ] Admin ve las reclamaciones en el menú "Reclamaciones" del WP Admin
- [ ] Admin puede cambiar el estado desde el listado (quick edit o metabox)
- [ ] Admin puede ver nombre, email, pedido, código y fecha del cliente en columnas del listado
- [ ] El formulario muestra el texto legal completo antes de permitir enviar
- [ ] Si el pedido no existe en WooCommerce, se muestra error (pero se permite continuar con advertencia)
- [ ] El código de trámite sigue el formato `123-4567-20260517`

## User Stories

1. Como **cliente**, quiero hacer clic en el "Botón de Arrepentimiento" del footer, completar mis datos y recibir un código de trámite inmediato, para tener constancia de que ejercí mi derecho.
2. Como **cliente**, quiero consultar el estado de mi reclamación con el código de trámite que recibí, para saber en qué etapa está.
3. Como **administrador**, quiero ver todas las reclamaciones en un listado con datos del cliente visibles, para gestionarlas rápidamente.
4. Como **administrador**, quiero cambiar el estado de una reclamación desde el listado (sin entrar al detalle), para agilizar la gestión.
5. Como **administrador**, quiero recibir un email cuando haya una nueva reclamación, para atenderla a tiempo.
6. Como **dueño del negocio**, quiero que el formulario muestre el texto legal obligatorio, para cumplir con la Ley 24.240.

## Edge Cases

| Case | Handling |
|------|----------|
| Pedido no existe en WooCommerce | Mostrar advertencia pero permitir enviar igual (pedidos offline o de otro sistema) |
| Pedido ya tiene reclamación activa | Informar que ya existe una reclamación para ese pedido |
| Email no llega | El código se muestra siempre en pantalla; email es adicional |
| Cliente no recuerda nro de pedido | Campo obligatorio pero con posibilidad de "no recuerdo" y contacto alternativo |
| Fecha de pedido > 10 días | Mostrar advertencia de que el plazo legal puede haber vencido, pero permitir enviar (el admin decide) |
| Código de trámite duplicado | Los 3 dígitos aleatorios + order + fecha hacen colisión extremadamente improbable; igualmente validar unicidad |
| Reserva ya transcurrida | Advertir en el formulario y en el email, pero permitir enviar |
