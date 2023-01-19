<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
 * @version 3.7.0
 */
defined('ABSPATH') || exit;

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);
?>

<?php if ($order->has_status('pending')) : ?>
    <p>
        <?php
        printf(
                wp_kses(
                        /* translators: %1s item is the name of the site, %2s is a html link */
                        __('An order has been created for you on %1$s. %2$s', 'woocommerce'), array(
            'a' => array(
                'href' => array(),
            ),
                        )
                ), esc_html(get_bloginfo('name', 'display')), '<a href="' . esc_url($order->get_checkout_payment_url()) . '">' . esc_html__('Pay for this order', 'woocommerce') . '</a>'
        );
        ?>
    </p>
<?php endif; ?>  
<?php
global $woocommerce;
if (!empty($woocommerce->session)) {
    $subscription = $woocommerce->session->get('subscription');
    $course = $subscription['course'];
    $cursists = $woocommerce->session->get('info_cursists');
    $type = array(0 => "Particulier", 1 => "Zakelijk");
}
?>
<p>We hebben uw inschrijving met de volgende informatie ontvangen:<br/></p>
<?php
if (isset($course) && !empty($course)) {
    $coursedata = explode(' - ', $course);
    $coursetitle = explode('- ', $coursedata[1]);
    ?>
    Deze opleiding is gepland op <strong><?= $coursedata[0] ?></strong>, <strong>adres: <?= $coursetitle[1] ?></strong><br/>
    <br />
    <?php
}
if ($cursists) {
    ?>
    <h2 style="color: rgb(245, 125, 48); display: block; font-family:Helvetica Neue, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0px 0px 18px; text-align: left;">Cursist(en) gegevens</h2>

    <table  cellspacing="0" cellpadding="6" border="1" style="color:#737973;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
        <tr>
            <th style="color:#737973;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Antal</th>
            <th style="color:#737973;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Naam</th>
            <th style="color:#737973;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Geboorteplaats</th>
            <th style="color:#737973;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Geboortedatum</th>
        </tr>
        <?php
        $i = 0;
        foreach ($cursists as $key => $cursist) {
            //print_r($cursist);
            $i++;
            ?>          
            <tr>
                <td style="color:#737973;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"><?php echo $i; ?></td>
                <td style="color:#737973;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"><?php echo $cursist['name']; ?></td>
                <td style="color:#737973;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"><?php echo $cursist['placeofbirth']; ?></td>
                <td style="color:#737973;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"><?php echo $cursist['birthdata']; ?></td>
            </tr>
        <?php }
        ?>
    </table><br/>   
<?php } ?>
<?php
/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */


/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
    echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);