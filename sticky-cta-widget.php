<?php
/*
Plugin Name: Sticky CTA Widget
Plugin URI: https://nestamedia.com
Description: Adds a stylish floating call-to-action bar at the bottom of the screen.
Version: 1.0
Author: Nestamedia Studios
Author URI: https://nestamedia.com
License: GPLv2 or later
Text Domain: sticky-cta-widget
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class StickyCTAWidget {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_footer', array($this, 'render_widget'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }

    public function enqueue_assets() {
        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    }

    public function render_widget() {
        $options = get_option('sticky_cta_widget_settings');
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create widget container with glass effect
            const widget = document.createElement('div');
            widget.id = 'floating-action-bar';
            widget.style.position = 'fixed';
            widget.style.bottom = '20px';
            widget.style.left = '50%';
            widget.style.transform = 'translateX(-50%)';
            widget.style.zIndex = '9999';
            widget.style.display = 'flex';
            widget.style.gap = '15px';
            widget.style.padding = '15px';
            widget.style.backdropFilter = 'blur(10px)';
            widget.style.backgroundColor = 'rgba(20, 20, 20, 0.7)';
            widget.style.borderRadius = '24px';
            widget.style.border = '1px solid rgba(255, 255, 255, 0.1)';
            widget.style.boxSizing = 'border-box';
            widget.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
            widget.style.flexWrap = 'wrap';
            widget.style.justifyContent = 'center';

            // Create buttons from settings or defaults
            const buttons = [
                { 
                    text: '<?php echo esc_js($options['button1_text'] ?? "BOOK NOW"); ?>', 
                    color: '<?php echo esc_js($options['button1_color'] ?? "#000000"); ?>', 
                    icon: '<?php echo esc_js($options['button1_icon'] ?? "fas fa-calendar"); ?>',
                    url: '<?php echo esc_js($options['button1_url'] ?? "#"); ?>'
                },
                { 
                    text: '<?php echo esc_js($options['button2_text'] ?? "MENUS"); ?>', 
                    color: '<?php echo esc_js($options['button2_color'] ?? "#c7b19a"); ?>', 
                    icon: '<?php echo esc_js($options['button2_icon'] ?? "fas fa-utensils"); ?>',
                    url: '<?php echo esc_js($options['button2_url'] ?? "#"); ?>'
                },
                { 
                    text: '<?php echo esc_js($options['button3_text'] ?? "CONTACT"); ?>', 
                    color: '<?php echo esc_js($options['button3_color'] ?? "#24d366"); ?>', 
                    icon: '<?php echo esc_js($options['button3_icon'] ?? "fab fa-whatsapp"); ?>',
                    url: '<?php echo esc_js($options['button3_url'] ?? "#"); ?>'
                }
            ];

            // Calculate base sizing based on text length
            const calculateButtonSize = (text) => {
                const baseSize = 100; // Minimum base size
                const charMultiplier = 8; // Additional px per character
                return Math.min(180, baseSize + (text.length * charMultiplier));
            };

            buttons.forEach(btn => {
                const button = document.createElement('button');
                button.className = 'action-btn';
                
                // Create icon element
                const icon = document.createElement('i');
                icon.className = btn.icon;
                icon.style.fontSize = '16px';
                icon.style.marginBottom = '6px';
                
                // Create text span
                const textSpan = document.createElement('span');
                textSpan.textContent = btn.text;
                textSpan.style.fontSize = '12px';
                textSpan.style.fontWeight = '500';
                textSpan.style.letterSpacing = '0.5px';
                textSpan.style.whiteSpace = 'nowrap';
                
                // Button container
                const btnContent = document.createElement('div');
                btnContent.style.display = 'flex';
                btnContent.style.flexDirection = 'column';
                btnContent.style.alignItems = 'center';
                btnContent.style.justifyContent = 'center';
                btnContent.style.width = '100%';
                btnContent.appendChild(icon);
                btnContent.appendChild(textSpan);
                
                button.appendChild(btnContent);
                
                // Dynamic button sizing based on text
                const buttonWidth = calculateButtonSize(btn.text);
                button.style.width = `${buttonWidth}px`;
                button.style.minWidth = '100px'; // Absolute minimum
                
                // Button styling
                button.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                button.style.color = 'white';
                button.style.border = 'none';
                button.style.borderRadius = '16px';
                button.style.padding = '10px 10px'; // Reduced horizontal padding
                button.style.cursor = 'pointer';
                button.style.transition = 'all 0.3s ease';
                button.style.display = 'flex';
                button.style.flexDirection = 'column';
                button.style.alignItems = 'center';
                button.style.justifyContent = 'center';
                button.style.overflow = 'hidden';
                button.style.position = 'relative';
                button.style.flexShrink = '1'; // Allow shrinking if needed
                
                // Add a subtle gradient overlay
                const overlay = document.createElement('div');
                overlay.style.position = 'absolute';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.background = 'linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%)';
                overlay.style.pointerEvents = 'none';
                button.appendChild(overlay);
                
                // Icon specific styling
                if (btn.icon.includes('whatsapp')) {
                    icon.style.color = btn.color;
                } else {
                    icon.style.color = 'white';
                }
                
                // Hover/active effects
                button.addEventListener('mouseover', () => {
                    button.style.transform = 'translateY(-4px) scale(1.05)';
                    button.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
                    button.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.2)';
                });
                
                button.addEventListener('mouseout', () => {
                    button.style.transform = 'translateY(0) scale(1)';
                    button.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                    button.style.boxShadow = 'none';
                });
                
                button.addEventListener('click', () => {
                    window.location.href = btn.url;
                });

                widget.appendChild(button);
            });

            // Add pulse animation to the WhatsApp button
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
                .action-btn:nth-child(3) i {
                    animation: pulse 2s infinite;
                }
                @media (max-width: 768px) {
                    #floating-action-bar {
                        bottom: 10px;
                        width: calc(100% - 40px);
                        max-width: 100%;
                        gap: 8px;
                        padding: 12px;
                    }
                    .action-btn {
                        padding: 12px 8px !important;
                        width: calc(33.33% - 10px) !important;
                        min-width: unset !important;
                        box-sizing: border-box;
                    }
                    .action-btn i {
                        font-size: 16px !important;
                    }
                    .action-btn span {
                        font-size: 11px !important;
                    }
                }
                @media (max-width: 480px) {
                    .action-btn {
                        margin-bottom: 0px;
                    }
                    .action-btn:last-child {
                        margin-bottom: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Make sure content isn't hidden behind the widget
            const checkContentPadding = () => {
                const widgetHeight = widget.offsetHeight + 30;
                document.body.style.paddingBottom = `${widgetHeight}px`;
            };
            
            checkContentPadding();
            window.addEventListener('resize', checkContentPadding);
            
            // Add widget to body
            document.body.appendChild(widget);
        });
        </script>
        <?php
    }

    public function add_admin_menu() {
        add_options_page(
            'Sticky CTA Widget Settings',
            'Sticky CTA Widget',
            'manage_options',
            'sticky_cta_widget',
            array($this, 'settings_page')
        );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Sticky CTA Widget Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('sticky_cta_widget');
                do_settings_sections('sticky_cta_widget');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function settings_init() {
        register_setting('sticky_cta_widget', 'sticky_cta_widget_settings');

        // Button 1 Section
        add_settings_section(
            'sticky_cta_widget_button1_section',
            'Button 1 Settings',
            array($this, 'button1_section_callback'),
            'sticky_cta_widget'
        );

        add_settings_field(
            'button1_text',
            'Button Text',
            array($this, 'button_text_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button1_section',
            ['option' => 'button1_text', 'default' => 'BOOK NOW']
        );

        add_settings_field(
            'button1_icon',
            'Icon Class',
            array($this, 'button_icon_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button1_section',
            ['option' => 'button1_icon', 'default' => 'fas fa-calendar']
        );

        add_settings_field(
            'button1_color',
            'Icon Color',
            array($this, 'button_color_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button1_section',
            ['option' => 'button1_color', 'default' => '#000000']
        );

        add_settings_field(
            'button1_url',
            'Link URL',
            array($this, 'button_url_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button1_section',
            ['option' => 'button1_url', 'default' => '#']
        );

        // Button 2 Section
        add_settings_section(
            'sticky_cta_widget_button2_section',
            'Button 2 Settings',
            array($this, 'button2_section_callback'),
            'sticky_cta_widget'
        );

        add_settings_field(
            'button2_text',
            'Button Text',
            array($this, 'button_text_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button2_section',
            ['option' => 'button2_text', 'default' => 'MENUS']
        );

        add_settings_field(
            'button2_icon',
            'Icon Class',
            array($this, 'button_icon_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button2_section',
            ['option' => 'button2_icon', 'default' => 'fas fa-utensils']
        );

        add_settings_field(
            'button2_color',
            'Icon Color',
            array($this, 'button_color_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button2_section',
            ['option' => 'button2_color', 'default' => '#c7b19a']
        );

        add_settings_field(
            'button2_url',
            'Link URL',
            array($this, 'button_url_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button2_section',
            ['option' => 'button2_url', 'default' => '#']
        );

        // Button 3 Section
        add_settings_section(
            'sticky_cta_widget_button3_section',
            'Button 3 Settings',
            array($this, 'button3_section_callback'),
            'sticky_cta_widget'
        );

        add_settings_field(
            'button3_text',
            'Button Text',
            array($this, 'button_text_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button3_section',
            ['option' => 'button3_text', 'default' => 'CONTACT']
        );

        add_settings_field(
            'button3_icon',
            'Icon Class',
            array($this, 'button_icon_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button3_section',
            ['option' => 'button3_icon', 'default' => 'fab fa-whatsapp']
        );

        add_settings_field(
            'button3_color',
            'Icon Color',
            array($this, 'button_color_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button3_section',
            ['option' => 'button3_color', 'default' => '#24d366']
        );

        add_settings_field(
            'button3_url',
            'Link URL',
            array($this, 'button_url_render'),
            'sticky_cta_widget',
            'sticky_cta_widget_button3_section',
            ['option' => 'button3_url', 'default' => '#']
        );
    }

    public function button1_section_callback() {
        echo 'Configure the settings for the first button';
    }

    public function button2_section_callback() {
        echo 'Configure the settings for the second button';
    }

    public function button3_section_callback() {
        echo 'Configure the settings for the third button';
    }

    public function button_text_render($args) {
        $options = get_option('sticky_cta_widget_settings');
        ?>
        <input type="text" name="sticky_cta_widget_settings[<?php echo esc_attr($args['option']); ?>]" 
               value="<?php echo esc_attr($options[$args['option']] ?? $args['default']); ?>">
        <?php
    }

    public function button_icon_render($args) {
        $options = get_option('sticky_cta_widget_settings');
        ?>
        <input type="text" name="sticky_cta_widget_settings[<?php echo esc_attr($args['option']); ?>]" 
               value="<?php echo esc_attr($options[$args['option']] ?? $args['default']); ?>">
        <p class="description">Use Font Awesome icon classes (e.g., fas fa-calendar, fab fa-whatsapp)</p>
        <?php
    }

    public function button_color_render($args) {
        $options = get_option('sticky_cta_widget_settings');
        ?>
        <input type="color" name="sticky_cta_widget_settings[<?php echo esc_attr($args['option']); ?>]" 
               value="<?php echo esc_attr($options[$args['option']] ?? $args['default']); ?>">
        <?php
    }

    public function button_url_render($args) {
        $options = get_option('sticky_cta_widget_settings');
        ?>
        <input type="url" name="sticky_cta_widget_settings[<?php echo esc_attr($args['option']); ?>]" 
               value="<?php echo esc_attr($options[$args['option']] ?? $args['default']); ?>" style="width: 300px;">
        <?php
    }
}

new StickyCTAWidget();