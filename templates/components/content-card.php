<?php
/**
 * Componente reutilizable de card de contenido.
 *
 * Variables esperadas:
 * $card_title
 * $card_image
 * $card_subtitle
 * $card_description
 * $card_address
 * $card_location
 * $card_phone
 * $card_email
 * $card_schedule
 * $card_show_button
 * $card_button_url
 * $card_button_text
 */

if (!defined('ABSPATH')) {
    exit;
}

$card_show_button = isset($card_show_button) ? (bool) $card_show_button : false;
$card_button_text = !empty($card_button_text) ? $card_button_text : 'Ver más';
$card_variant = !empty($card_variant)
    ? sanitize_html_class($card_variant)
    : 'horizontal';
?>

<article class="st3m-content-card st3m-content-card--<?php echo esc_attr($card_variant); ?>">

    <?php if (!empty($card_image)) : ?>
        <div class="st3m-content-card__image">
            <?php echo wp_kses_post($card_image); ?>
        </div>
    <?php endif; ?>

    <div class="st3m-content-card__content">

        <div class="st3m-content-card__header">

            <?php if (!empty($card_title)) : ?>
                <h3 class="st3m-content-card__title">
                    <?php echo esc_html($card_title); ?>
                </h3>
            <?php endif; ?>

            <?php if (!empty($card_subtitle)) : ?>
                <p class="st3m-content-card__subtitle">
                    <?php echo esc_html($card_subtitle); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($card_description)) : ?>
                <p class="st3m-content-card__description">
                    <?php echo esc_html($card_description); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($card_address)) : ?>
                <p class="st3m-content-card__address">
                    <?php echo esc_html($card_address); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($card_location)) : ?>
                <p class="st3m-content-card__location">
                    <?php echo esc_html($card_location); ?>
                </p>
            <?php endif; ?>

        </div>

        <?php if (!empty($card_phone) || !empty($card_email) || !empty($card_schedule)) : ?>
            <div class="st3m-content-card__info-grid">

                <?php if (!empty($card_phone)) : ?>
                    <div class="st3m-content-card__info-item">
                        <span class="st3m-content-card__icon">☎</span>
                        <div>
                            <strong>Teléfono</strong>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $card_phone)); ?>">
                                <?php echo esc_html($card_phone); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($card_email)) : ?>
                    <div class="st3m-content-card__info-item">
                        <span class="st3m-content-card__icon">✉</span>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:<?php echo esc_attr($card_email); ?>">
                                <?php echo esc_html($card_email); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($card_schedule)) : ?>
                    <div class="st3m-content-card__info-item">
                        <span class="st3m-content-card__icon">◷</span>
                        <div>
                            <strong>Horario</strong>
                            <span><?php echo nl2br(esc_html($card_schedule)); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <?php if ($card_show_button && !empty($card_button_url)) : ?>
            <a 
                class="st3m-content-card__button" 
                href="<?php echo esc_url($card_button_url); ?>" 
                target="_blank" 
                rel="noopener noreferrer"
            >
                <?php echo esc_html($card_button_text); ?>
            </a>
        <?php endif; ?>

    </div>

</article>