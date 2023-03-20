<?php

namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \Elementor\Repeater;
use Elementor\Group_Control_Typography;

class Achievement extends Widget_Base
{

    public function get_name()
    {
        return 'achievement';
    }
    public function get_title()
    {
        return __('Achievement', 'resido-core');
    }
    public function get_icon()
    {
        return 'sds-widget-ico';
    }
    public function get_categories()
    {
        return array("resido");
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'general',
            array(
                'label' => esc_html__('General', 'resido-core'),
            )
        );

        $this->add_control(
            'heading',
            array(
                'label'     => __('Header', 'resido-core'),
                'type'      => \Elementor\Controls_Manager::TEXT,
            )
        );

        $this->add_control(
            'title',
            array(
                'label'     => __('Title', 'resido-core'),
                'separator' => 'after',
                'type'      => \Elementor\Controls_Manager::TEXT,
            )
        );

        $this->add_control(
            'column',
            array(
                'label'     => esc_html__('Column', 'resido-core'),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'col-lg-12 col-md-12'   => esc_html__('1', 'resido-core'),
                    'col-lg-6 col-md-6'   => esc_html__('2', 'resido-core'),
                    'col-lg-4 col-md-4'   => esc_html__('3', 'resido-core'),
                    'col-lg-3 col-md-3'   => esc_html__('4', 'resido-core'),
                ),
                'default'   => 'col-lg-4 col-md-4',

            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_background',
            array(
                'label'   => esc_html__('BG Image', 'resido-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),

            )
        );

        $repeater->add_control(
            'item_title',
            [
                'label' => esc_html__('Title', 'resido-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'item_desc',
            [
                'label' => esc_html__('Description', 'resido-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );


        $this->add_control(
            'items',
            [
                'label' => esc_html__('Repeater List', 'resido-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_content' => esc_html__('Title #1', 'resido-core'),
                    ],
                    [
                        'item_content' => esc_html__('Title #2', 'resido-core'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        resido_public_heading_control($this, 'h4');

        resido_public_title_control($this, 'p');

        //Typography Section

        $this->start_controls_section(
            'typography_section',
            array(
                'label' => __('Typography Section', 'resido'),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => __('Item Title ', 'resido'),
                'selector' => '{{WRAPPER}} .achievement-content h4',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'label'    => __('Item Description', 'resido'),
                'selector' => '{{WRAPPER}} .achievement-content p',
            )
        );


        $this->end_controls_section();

        //Color Section

        $this->start_controls_section(
            'color_section',
            array(
                'label' => __('Color Section', 'resido'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => __('Item Title Color', 'resido'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .achievement-content h4' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'description_color',
            array(
                'label'     => __('Item Description Color', 'resido'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .achievement-content p' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Header Func inp
        $public_header_tag = $settings['public_header_tag'];
        $heading = $settings['heading'];
        $this->add_render_attribute('heading', 'class', 'typo-header-text');
        $this->add_inline_editing_attributes('heading', 'none');
        // Header Func inp

        // title Func inp
        $public_title_tag = $settings['public_title_tag'];
        $title = $settings['title'];
        $this->add_render_attribute('title', 'class', 'typo-title-text');
        $this->add_inline_editing_attributes('title', 'none');
        // title Func inp

?>

        <!-- ============================ Achievement Start ================================== -->
        <section>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-10 text-center">
                        <div class="sec-heading center mb-4">
                            <!-- Header Func outp -->
                            <<?php echo $public_header_tag; ?> <?php echo $this->get_render_attribute_string('heading'); ?>>
                                <?php echo $heading; ?>
                            </<?php echo $public_header_tag; ?>>
                            <!-- Title Func outp -->
                            <<?php echo $public_title_tag; ?> <?php echo $this->get_render_attribute_string('title'); ?>>
                                <?php echo $title; ?>
                            </<?php echo $public_title_tag; ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php
                    $i = 1;
                    foreach ($settings["items"] as $item) {
                        $item_title = $item["item_title"];
                        $item_desc = $item["item_desc"];
                        $item_background     = ($item['item_background']['id'] != '') ? wp_get_attachment_image($item['item_background']['id'], 'full') : $item['item_background']['url'];
                        $item_background_alt = get_post_meta($item['item_background']['id'], '_wp_attachment_image_alt', true);
                    ?>
                        <div class="<?php echo $settings['column']; ?>">
                            <div class="achievement-wrap">
                                <div class="achievement-content">
                                    <?php
                                    if (!empty($item_background)) { ?>
                                        <div class="icon mb-4">
                                            <?php
                                            if (wp_http_validate_url($item_background)) { ?>
                                                <img src="<?php echo esc_url($item_background); ?>" class="img-fluid" alt="<?php esc_url($item_background_alt); ?>">
                                            <?php } else {
                                                echo $item_background;
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <h4><?php echo $item_title; ?></h4>
                                    <p><?php echo $item_desc; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php $i++;
                    } ?>
                </div>
            </div>
        </section>

<?php
    }
    protected function content_template()
    {
    }
}
