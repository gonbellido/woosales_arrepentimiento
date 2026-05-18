=== Botón de Arrepentimiento Argentina — WooSales ===
Contributors: woosales
Donate link: https://woosales.pro
Tags: woocommerce, argentina, right-of-withdrawal, consumer-rights, refund
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce right of withdrawal plugin for Argentina (Ley 24.240). Claim form, tracking page, admin dashboard, and email notifications included.

== Description ==

**Note:** This plugin is designed for Argentine WooCommerce stores. The user interface is in Spanish. It implements the consumer right of withdrawal required by Argentine law (Ley 24.240 de Defensa del Consumidor).

### Legal Compliance Made Simple

**WooSales Arrepentimiento** is the complete solution for WooCommerce stores in Argentina to comply with the **right of withdrawal** (derecho de arrepentimiento) required by **Ley 24.240 de Defensa del Consumidor**.

Developed by [WooSales.pro](https://woosales.pro), this plugin covers the full workflow: from the withdrawal button on your site to the administrative management of each claim.

### Key Features

- **Withdrawal Button**: floating button in the footer, fully configurable.
- **Smart Form**: automatic date validation (10 days from purchase, minimum lead time based on service type).
- **Instant Claim Code**: generated and displayed on screen immediately (format `123-4521-20260517`).
- **Automated Emails**: customer confirmation with tracking link + administrator notification.
- **Public Tracking**: customers can check their claim status using their code, no login required.
- **Simple Backoffice**: list with all data visible, status filter, one-click status change.
- **Rejection Reason**: transparent for the customer, visible from the tracking page.
- **Complete Workflow**: Pending → Under Review → Approved → Refund Completed (or Rejected).
- **100% Responsive**: works on mobile, tablet, and desktop.

### Developed by WooSales.pro

At **[WooSales.pro](https://woosales.pro)** we specialize in WooCommerce for the Argentine market. We offer:

- Plugins for electronic invoicing, Mercado Pago, shipping and logistics.
- Integrations with AFIP, ARCA, and local management systems.
- Custom development and specialized technical support.

---

### Quick Installation

1. Install the plugin from the WordPress repository or upload the `boton-de-arrepentimiento-argentina-woosales` folder to `/wp-content/plugins/`.
2. Activate the plugin (requires WooCommerce active).
3. Go to **Reclamaciones → Configuración** and select:
   - The page where you placed the shortcode `[wa_formulario_arrepentimiento]`
   - The page where you placed the shortcode `[wa_seguimiento]`
4. Done! The withdrawal button appears automatically in the footer.

### Usage

#### Shortcodes

- **`[wa_formulario_arrepentimiento]`** — Displays the full form with legal text on any page.
- **`[wa_boton_arrepentimiento]`** — Displays a button that opens the form in a responsive popup. Ideal for headers, sidebars, or product pages. Optional attribute: `texto="Your text"`.
- **`[wa_seguimiento]`** — Tracking page where customers check their claim status with their code.

== Frequently Asked Questions ==

= Do I need WooCommerce to use this plugin? =

Yes. The plugin is designed to integrate with WooCommerce. It verifies orders against your store and pre-fills customer data. If WooCommerce is not active, the plugin will not initialize.

= What happens if the order does not exist in WooCommerce? =

The form shows a warning but still allows submitting the claim. This covers scenarios such as offline orders, phone reservations, or migrations from other systems.

= Is the refund processed automatically? =

No. **WooSales Arrepentimiento** manages the administrative process. The refund is performed manually by the administrator. This gives you full control over each return.

= Can the customer see why their request was rejected? =

Yes. When an administrator changes the status to "Rechazada" (Rejected), they must complete a **mandatory rejection reason** that is visible to the customer on the tracking page.

= Does it work with any theme? =

Yes. Styles are self-contained and the footer button is compatible with any theme. Tested with Storefront, Divi, Astra, GeneratePress, and custom themes.

= Does it truly comply with Ley 24.240? =

We cover all required technical aspects: visible button, accessible form, immediate delivery of claim code, and electronic communication. **We always recommend consulting a legal advisor** to validate the implementation in your specific case.

= Do you offer support? =

Yes! Post your question in the [plugin support forum](https://wordpress.org/support/plugin/boton-de-arrepentimiento-argentina-woosales/) or contact us directly at [WooSales.pro](https://woosales.pro/contacto).

= Can I show just a button instead of the full form? =

Yes. Use the shortcode `[wa_boton_arrepentimiento]` wherever you want. It shows a button that opens the form in a responsive popup. You can customize the text with `[wa_boton_arrepentimiento texto="Cancel my order"]`.

== Screenshots ==

1. Public withdrawal form with legal text and fields for order, name, email, and reservation date.
2. Success response with instant claim code and tracking link.
3. Email received by the customer with code, status, and button to check status.
4. Public tracking page where the customer checks claim status with their code.
5. Claims list in the backoffice with columns: code, order, customer, email, status, date.
6. Status change metabox with mandatory rejection reason and change history.
7. Settings page: admin email, shortcode pages, footer button.

== Changelog ==

= 1.0.0 — 2026-05-17 =
* Initial release.
* CPT Reclamaciones with 5-state workflow.
* Public form with legal date validation.
* Instant claim code (compound format).
* Automatic HTML emails (customer + admin).
* Public tracking page.
* Backoffice with custom columns, filters, and quick status change.
* Rejection reason visible to the customer.
* Floating withdrawal button in footer.
* Plugin settings page.

== Upgrade Notice ==

= 1.0.0 =
First version. Install, configure the shortcode pages, and activate the withdrawal button. We recommend testing in a staging environment before going to production.
