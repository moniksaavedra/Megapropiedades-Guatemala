<?php
namespace Resido\Helper\Elementor\Widgets;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;

class Contact_Form extends Widget_Base {


	public function __construct( array $data = array(), array $args = null ) {
		// Widget_Base::__construct( $data, $args );
		parent::__construct( $data, $args );
		wp_register_script( 'validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js', array( 'jquery' ), '1.19.2', false );
	}

	public function get_script_depends() {
		return array( 'validate', 'sds-contact-script' );
	}

	public function get_name() {
		return 'contact_form';
	}
	public function get_title() {
		return __( 'Contact Form', 'resido-core' );
	}
	public function get_icon() {
		return 'sds-widget-ico';
	}
	public function get_categories() {
		return array( 'resido' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'resido-core' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Form Title', 'resido-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
			)
		);

		$this->add_control(
			'button_title',
			array(
				'label'       => __( 'Button Title', 'resido-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'field_id',
			array(
				'label' => __( 'Field Id', 'resido-core' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'field_label',
			array(
				'label'   => esc_html__( 'Label', 'resido-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
		$repeater->add_control(
			'field_placeholder',
			array(
				'label' => __( 'Placeholder Text', 'resido-core' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'field_type',
			array(
				'label'   => __( 'Field Type', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => array(
					'text'     => __( 'Text', 'resido-core' ),
					'email'    => __( 'Email', 'resido-core' ),
					'textarea' => __( 'Textarea', 'resido-core' ),
					'url'      => __( 'URL', 'resido-core' ),
					'tel'      => __( 'Tel', 'resido-core' ),
					'number'   => __( 'Number', 'resido-core' ),
					'date'     => __( 'Date', 'resido-core' ),
					'dropdown' => __( 'Dropdown', 'resido-core' ),
					'checkbox' => __( 'Checkbox', 'resido-core' ),
					'radio'    => __( 'Radio', 'resido-core' ),
					'file'     => __( 'File', 'resido-core' ),
				),
			)
		);
		$repeater->add_responsive_control(
			'width',
			array(
				'label'   => esc_html__( 'Column Width', 'resido-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''    => esc_html__( 'Default', 'resido-core' ),
					'100' => '100%',
					'80'  => '80%',
					'75'  => '75%',
					'66'  => '66%',
					'60'  => '60%',
					'50'  => '50%',
					'40'  => '40%',
					'33'  => '33%',
					'25'  => '25%',
					'20'  => '20%',
				),
				'default' => '100',
			)
		);

		$repeater->add_control(
			'rows',
			array(
				'label'      => esc_html__( 'Rows', 'resido-core' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 4,
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'textarea',
							),
						),
					),
				),
			)
		);
		$repeater->add_control(
			'dropdown_item',
			array(
				'label'       => esc_html__( 'Dropdown Item', 'resido-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Dropdown Item should be line break', 'resido-core' ),
				'default'     => esc_html__(
					'First
				Secound'
				),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'dropdown',
							),
						),
					),
				),
			)
		);
		$repeater->add_control(
			'checkbox_item',
			array(
				'label'      => esc_html__( 'Checkbox Item', 'resido-core' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => esc_html__(
					'First
					Secound'
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'checkbox',
							),
						),
					),
				),
			)
		);
		$repeater->add_control(
			'radio_item',
			array(
				'label'      => esc_html__( 'Radio Item', 'resido-core' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => esc_html__(
					'First
					Secound'
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'radio',
							),
						),
					),
				),
			)
		);
		$repeater->add_control(
			'required',
			array(
				'label'        => esc_html__( 'Required', 'resido-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(),
						),
					),
				),
			)
		);
		$repeater->add_control(
			'add_class',
			array(
				'label'   => esc_html__( 'Add Class', 'resido-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
		$this->add_control(
			'fields',
			array(
				'label'       => __( 'Fields', 'resido-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ field_id }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_email_settings',
			array(
				'label' => esc_html__( 'Email', 'houzez-theme-functionality' ),
			)
		);

		$this->add_control(
			'email_to',
			array(
				'label'       => esc_html__( 'To', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => get_option( 'admin_email' ),
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title'       => esc_html__( 'Separate emails with commas', 'houzez-theme-functionality' ),
				'render_type' => 'none',
			)
		);

		$default_message = sprintf( esc_html__( 'New message from "%s"', 'houzez-theme-functionality' ), get_option( 'blogname' ) );

		$this->add_control(
			'email_subject',
			array(
				'label'       => esc_html__( 'Subject', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
			)
		);

		$this->add_control(
			'email_to_cc',
			array(
				'label'       => esc_html__( 'Cc', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'title'       => esc_html__( 'Separate emails with commas', 'houzez-theme-functionality' ),
				'render_type' => 'none',
			)
		);

		$this->add_control(
			'email_to_bcc',
			array(
				'label'       => esc_html__( 'Bcc', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'title'       => esc_html__( 'Separate emails with commas', 'houzez-theme-functionality' ),
				'render_type' => 'none',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_field',
			array(
				'label' => __( 'Field', 'resido-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'form_field_bg',
			array(
				'label'     => __( 'Field Background Color', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-wrapper .form-field' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'form_field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-wrapper .form-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'form_field_margin',
			array(
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-wrapper .form-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'form_field_padding',
			array(
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-wrapper .form-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_submit_button',
			array(
				'label' => __( 'Submit Button', 'resido-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'form_button_bg',
			array(
				'label'     => __( 'Button Background Color', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-wrapper .btn-theme-light-2.btn' => 'background: {{VALUE}}!important',
				),
			)
		);
		$this->add_control(
			'form_button_color',
			array(
				'label'     => __( 'Button Text Color', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-wrapper .btn-theme-light-2.btn' => 'color: {{VALUE}}!important',
				),
			)
		);

		$this->add_control(
			'form_button_bg_hover',
			array(
				'label'     => __( 'Button  Hover Background Color', 'plugin-domain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-wrapper .btn-theme-light-2.btn:hover' => 'background: {{VALUE}}!important',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();

		$email_to      = ! empty( $settings['email_to'] ) ? $settings['email_to'] : get_option( 'admin_email' );
		$email_subject = ! empty( $settings['email_subject'] ) ? $settings['email_subject'] : '';
		$email_to_cc   = ! empty( $settings['email_to_cc'] ) ? $settings['email_to_cc'] : '';
		$email_to_bcc  = ! empty( $settings['email_to_bcc'] ) ? $settings['email_to_bcc'] : '';
		?>
		<div class="form-wrapper">
			<form class="elementor-form" id="sds-form-<?php echo $this->get_id(); ?>" name='registration' method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?> action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" enctype="multipart/form-data">
				<?php
				wp_nonce_field( 'resido_contact', 'resido_contact_nonce' );

				// attachFiles

				?>
				<input type="hidden" name="email_to" value="<?php echo esc_attr( $email_to ); ?>" />
				<input type="hidden" name="email_subject" value="<?php echo esc_attr( $email_subject ); ?>" />
				<input type="hidden" name="email_to_cc" value="<?php echo esc_attr( $email_to_cc ); ?>" />
				<input type="hidden" name="email_to_bcc" value="<?php echo esc_attr( $email_to_bcc ); ?>" />

				<h5 class="mb-3"><?php echo esc_html( $settings['title'] ); ?></h5>
				<div class="elementor-form-fields-wrapper elementor-labels-above">
					<?php
					foreach ( $settings['fields'] as $field ) {

						$width     = $field['width'];
						$required  = $field['required'];
						$add_class = $field['add_class'];

						if ( empty( $add_class ) ) {
							$add_class = 'form-control';
						}

						if ( $required ) {
							$required_class = 'elementor-field-required elementor-mark-required';
							$requiredtag    = 'required="required"';
						} else {
							$required_class = '';
							$requiredtag    = '';
						}

						if ( 'text' == $field['field_type'] || 'email' == $field['field_type'] || 'url' == $field['field_type'] || 'tel' == $field['field_type'] || 'number' == $field['field_type'] || 'date' == $field['field_type'] ) {
							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo $width . ' ' . $required_class; ?>">
								<?php if ( $field['field_label'] ) { ?>
									<label for="<?php echo esc_attr( $field['field_id'] ); ?>" class="elementor-field-label"><?php echo esc_attr( $field['field_label'] ); ?></label>
								<?php } ?>
								<input id="<?php echo esc_attr( $field['field_id'] ); ?>" name="<?php echo esc_attr( $field['field_id'] ); ?>" oninvalid="this.setCustomValidity('<?php echo esc_html__( 'Invalid field Input', 'resido-core' ); ?>')" oninput="setCustomValidity('')" type="<?php echo $field['field_type']; ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" placeholder="<?php echo esc_attr( $field['field_placeholder'] ); ?>" <?php echo $requiredtag; ?>></input>
							</div>
							<?php
						} elseif ( 'textarea' == $field['field_type'] ) {
							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo $width . ' ' . $required_class; ?>">
								<?php if ( $field['field_label'] ) { ?>
									<label for="<?php echo esc_attr( $field['field_id'] ); ?>" class="elementor-field-label"><?php echo esc_attr( $field['field_label'] ); ?></label>
								<?php } ?>
								<textarea id="<?php echo esc_attr( $field['field_id'] ); ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" name="<?php echo esc_attr( $field['field_id'] ); ?>" rows="4" placeholder="<?php echo esc_attr( $field['field_placeholder'] ); ?>" <?php echo $requiredtag; ?>></textarea>
							</div>
							<?php
						} elseif ( 'dropdown' == $field['field_type'] ) {
							$dropdown_item = explode( PHP_EOL, $field['dropdown_item'] );
							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo esc_attr( $width . ' ' . $required_class ); ?>">
								<?php if ( $field['field_label'] ) { ?>
									<label for="<?php echo esc_attr( $field['field_id'] ); ?>" class="elementor-field-label"><?php echo esc_attr( $field['field_label'] ); ?></label>
								<?php } ?>
								<select name="<?php echo esc_attr( $field['field_id'] ); ?>" id="<?php esc_attr( $field['field_id'] ); ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" <?php echo $requiredtag; ?>>
									<?php foreach ( $dropdown_item as $item ) { ?>
										<option value="<?php echo esc_attr( $item ); ?>"><?php echo esc_html( $item ); ?></option>
									<?php } ?>
								</select>
							</div>
							<?php
						} elseif ( 'checkbox' == $field['field_type'] ) {
							$checkbox_item = explode( PHP_EOL, $field['checkbox_item'] );
							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo esc_attr( $width . ' ' . $required_class ); ?>">
								<?php
								$i = 0;
								foreach ( $checkbox_item as $item ) {
									$i++;
									?>
									<label>
										<input type="checkbox" name="<?php echo esc_attr( $item ); ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" value="<?php echo esc_attr( $item ); ?>" 
																				<?php
																				if ( $i == 1 ) :
																					?>
																																																			 checked <?php endif; ?>>
										<?php echo esc_html( $item ); ?>
									</label>
								<?php } ?>
							</div>
							<?php
						} elseif ( 'radio' == $field['field_type'] ) {
							$radio_item = explode( PHP_EOL, $field['radio_item'] );

							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo esc_attr( $width . ' ' . $required_class ); ?>">
								<?php
								$i = 0;
								foreach ( $radio_item as $item ) {
									$i++;
									?>
									<label for="<?php echo esc_attr( $item ); ?>">
										<input type="radio" id="<?php echo esc_attr( $item ); ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" name="radio" value="<?php echo esc_attr( $item ); ?>" 
																		   <?php
																			if ( $i == 1 ) :

																				?>
																																																					 checked <?php endif; ?>>
										<?php echo esc_html( $item ); ?></label>
								<?php } ?>
							</div>
							<?php
						} elseif ( 'file' == $field['field_type'] ) {
							?>
							<div class="elementor-field-group elementor-column form-group elementor-field-group-name elementor-col-<?php echo esc_attr( $width . ' ' . $required_class ); ?>">
								<input type="file" name="attachment[]" size="40" id="<?php echo esc_attr( $field['field_id'] ); ?>" class="form-field <?php echo esc_attr( $add_class ); ?>" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.odt,.avi,.ogg,.m4a,.mov,.mp3,.mp4,.mpg,.wav,.wmv" <?php echo $requiredtag; ?>>
							</div>
							<?php
						}
					}
					?>
				</div>
				<button type="submit" class="contact_button btn btn-theme-light-2 rounded"><?php echo esc_html( $settings['button_title'] ); ?></button>
				<div class="ele-form-messages"></div>
				<div class="error-container"></div>
			</form>
		</div>
		<?php
	}
}
