<?php
/**
 *
 * Appearance > Customizer
 *
 */

class Themedy_Customizer extends Genesis_Customizer_Base {

	/**
	 * Settings field.
	 */
	public $settings_field = CHILD_THEME_SETTINGS;

	/**
	 *
	 */
	public function register( $wp_customize ) {

		$this->styles( $wp_customize );

	}

	private function styles( $wp_customize ) {

		$wp_customize->add_setting(
			$this->get_field_name( 'action_color' ),
			array(
				'default' => '#425d80',
				'type'    => 'option'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'themedy_style ',
				array(
					'label'      => __( 'Primary Color', 'themedy' ),
					'section'    => 'colors',
					'settings'   => $this->get_field_name( 'action_color' ),
				)
			)
		);

	}

}

add_action( 'init', 'themedy_customizer_init' );
/**
 *
 */
function themedy_customizer_init() {
	new Themedy_Customizer;
}
