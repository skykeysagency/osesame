<?php
/**
 * RisingBambooCore Widget.
 *
 * @package RisingBambooCore
 * @version 1.0.0
 * @since 1.0.0
 */

namespace RisingBambooCore\Widgets;

use RisingBambooCore\App\App;
use RisingBambooTheme\Helper\Setting;
use WP_Widget;

/**
 * Widget Base.
 */
abstract class Base extends WP_Widget {
	/**
	 * Widget ID.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * CSS class.
	 *
	 * @var string
	 */
	public string $css_class;

	/**
	 * Widget description.
	 *
	 * @var string
	 */
	public string $description;

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Widget settings.
	 *
	 * @var array
	 */
	public array $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ]);
		$ops = [
			'classname'                   => $this->css_class,
			'description'                 => $this->description,
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		];

		parent::__construct($this->id, $this->name, $ops);

		add_action('save_post', [ $this, 'flush_widget_cache' ]);
		add_action('deleted_post', [ $this, 'flush_widget_cache' ]);
		add_action('switch_theme', [ $this, 'flush_widget_cache' ]);
	}

	/**
	 * Get cached widget.
	 *
	 * @param array $args Arguments.
	 * @return bool true if the widget is cached otherwise false
	 */
	public function get_cached_widget( array $args ): bool {
		if ( empty($args['id']) ) {
			return false;
		}

		$cache = wp_cache_get($this->get_widget_id_for_cache($this->id), 'widget');

		if ( ! is_array($cache) ) {
			$cache = [];
		}

		if ( isset($cache[ $this->get_widget_id_for_cache($args['id']) ]) ) {
			echo $cache[ $this->get_widget_id_for_cache($args['id']) ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget.
	 *
	 * @param array  $args    Arguments.
	 * @param string $content Content.
	 * @return string the content that was cached
	 */
	public function cache_widget( array $args, string $content ): string {
		if ( empty($args['id']) ) {
			return $content;
		}

		$cache = wp_cache_get($this->get_widget_id_for_cache($this->id), 'widget');

		if ( ! is_array($cache) ) {
			$cache = [];
		}

		$cache[ $this->get_widget_id_for_cache($args['id']) ] = $content;

		wp_cache_set($this->get_widget_id_for_cache($this->id), $cache, 'widget');

		return $content;
	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache(): void {
		foreach ( [ 'https', 'http' ] as $scheme ) {
			wp_cache_delete($this->get_widget_id_for_cache($this->id, $scheme), 'widget');
		}
	}

	/**
	 * Get widget id plus scheme/protocol to prevent serving mixed content from (persistently) cached widgets.
	 *
	 * @param string $widget_id The ID of the cached widget.
	 * @param string $scheme    Scheme for the widget id.
	 * @return string            Widget id including scheme/protocol.
	 */
	protected function get_widget_id_for_cache( string $widget_id, string $scheme = '' ): string {
		if ( $scheme ) {
			$widget_id_for_cache = $widget_id . '-' . $scheme;
		} else {
			$widget_id_for_cache = $widget_id . '-' . ( is_ssl() ? 'https' : 'http' );
		}

		return apply_filters('rbb_cached_widget_id', $widget_id_for_cache); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see    WP_Widget->update
	 *
	 * @param  array $new_instance New Instance.
	 * @param  array $old_instance Old Instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ): array {

		$instance = $old_instance;

		if ( empty($this->settings) ) {
			return $instance;
		}

		// Loop settings and get values to save.
		foreach ( $this->settings as $key => $setting ) {
			if ( ! isset($setting['type']) ) {
				continue;
			}

			// Format the value based on settings type.
			switch ( $setting['type'] ) {
				case 'number':
					$instance[ $key ] = absint($new_instance[ $key ]);

					if ( isset($setting['min']) && '' !== $setting['min'] ) {
						$instance[ $key ] = max($instance[ $key ], $setting['min']);
					}

					if ( isset($setting['max']) && '' !== $setting['max'] ) {
						$instance[ $key ] = min($instance[ $key ], $setting['max']);
					}
					break;
				case 'textarea':
					$instance[ $key ] = wp_kses(trim(wp_unslash($new_instance[ $key ])), wp_kses_allowed_html('post'));
					break;
				case 'checkbox':
					$instance[ $key ] = empty($new_instance[ $key ]) ? 0 : 1;
					break;
				default:
					$instance[ $key ] = sanitize_text_field($new_instance[ $key ]);
					break;
			}

			/**
			 * Sanitize the value of a setting.
			 */
			$instance[ $key ] = apply_filters('rbb_widget_settings_sanitize', $instance[ $key ], $new_instance, $key, $setting); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

			$this->flush_widget_cache();

		}

		return $instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see   WP_Widget->form
	 *
	 * @param array $instance The Instance.
	 *
	 * @return void
	 */
	public function form( $instance ): void {
		if ( empty($this->settings) ) {
			return;
		}
		$before_hook_name = $this->get_hook_name_form();

		if ( has_action($before_hook_name) ) {
			?>
			<div class="<?php echo esc_attr($before_hook_name); ?>">
			<?php
            //phpcs:ignore
			do_action($before_hook_name);
			?>
			</div>
			<?php
		}

		foreach ( $this->settings as $key => $setting ) {

			$class   = (array) ( $setting['class'] ?? [] );
			$class[] = 'rbb-widget-control-' . $key;
			if ( $this->has_depend($key) ) {
				$class[] = $this->has_depend($key);
			}

			$value        = $instance[ $key ] ?? $setting['default'];
			$condition    = $setting['condition'] ?? null;
			$wrapper_attr = [];
			if ( $condition ) {
				$wrapper_attr['data-condition'] = $condition;
				$wrapper_attr['class']          = [
					'has-condition',
				];
			}

			switch ( $setting['type'] ) {

				case 'text':
					?>
					<div <?php echo $this->render_attributes($wrapper_attr); // phpcs:ignore ?>>
						<label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo wp_kses_post($setting['label']); ?></label><?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<input class="<?php echo esc_attr(implode(' ', $class)); ?>" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="text" value="<?php echo esc_attr($value); ?>" />
						<?php if ( isset($setting['desc']) ) : ?>
							<small><?php echo esc_html($setting['desc']); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'number':
					?>
					<div <?php echo $this->render_attributes($wrapper_attr); // phpcs:ignore ?>>
						<label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></label>
						<input class="<?php echo esc_attr(implode(' ', $class)); ?>" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="number" step="<?php echo esc_attr($setting['step'] ?? ''); ?>" min="<?php echo esc_attr($setting['min'] ?? ''); ?>" max="<?php echo esc_attr($setting['max'] ?? ''); ?>" value="<?php echo esc_attr($value); ?>" />
						<?php if ( isset($setting['desc']) ) : ?>
							<small><?php echo esc_html($setting['desc']); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'select':
					?>
					<div <?php echo $this->render_attributes($wrapper_attr); // phpcs:ignore ?>>
						<label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></label>
						<select class="<?php echo esc_attr(implode(' ', $class)); ?>" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr($option_key); ?>" <?php selected($option_key, $value); ?>><?php echo esc_html($option_value); ?></option>
							<?php endforeach; ?>
						</select>
						<?php if ( isset($setting['desc']) ) : ?>
							<small><?php echo esc_html($setting['desc']); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'textarea':
					?>
					<div <?php echo $this->render_attributes($wrapper_attr); // phpcs:ignore ?>>
						<label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></label>
						<textarea class="<?php echo esc_attr(implode(' ', $class)); ?>" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" cols="20" rows="3"><?php echo esc_textarea($value); ?></textarea>
						<?php if ( isset($setting['desc']) ) : ?>
							<small><?php echo esc_html($setting['desc']); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'checkbox':
					?>
					<div <?php echo $this->render_attributes($wrapper_attr); // phpcs:ignore ?>>
						<input class="checkbox <?php echo esc_attr(implode(' ', $class)); ?>" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="checkbox" value="1" <?php checked($value, 1); ?> />
						<label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></label>
					</div>
					<?php
					break;

				default:
					do_action('rbb_widget_field_' . $setting['type'], $key, $value, $setting, $instance); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
					break;
			}
		}
		$after_hook_name = $this->get_hook_name_form('after');

		if ( has_action($after_hook_name) ) {
			?>
			<div class="<?php echo esc_attr($after_hook_name); ?>">
				<?php
                //phpcs:ignore
				do_action($after_hook_name);
				?>
			</div>
			<?php
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts(): void {
		if ( 'widgets' === get_current_screen()->id ) {
			wp_enqueue_script('rbb-widget-condition', RBB_CORE_URL . 'dist/js/admin/components/widget-condition.js', [ 'jquery' ], App::get_version(), true);
		}
	}

	/**
	 * Get Setting.
	 *
	 * @param mixed  $instance     Instance.
	 * @param string $setting_name Setting Name.
	 * @return false|mixed
	 */
	public function get_setting( $instance, string $setting_name ) {
		return $instance[ $setting_name ] ?? $this->settings[ ( $setting_name ) ]['default'] ?? null;
	}

	/**
	 * Output the html at the start of a widget.
	 *
	 * @param mixed $args Args.
	 * @param mixed $instance Instance.
	 */
	public function widget_start( $args, $instance ): void {
		$position                = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION);
		$custom_class            = '';
		$widget_content_style    = '';
		$widget_content_dropdown = '';

		if ( ! empty($instance['enable_scrollable']) ) {
			$custom_class = ' widget-scrollable';
		}

		if ( ! empty($instance['enable_collapsed']) ) {
			$custom_class          = ' collapsed';
			$widget_content_style .= 'display: none;';
		}

		if ( ! empty($custom_class) ) {
			$args['before_widget'] = preg_replace('/class="/', 'class=" ' . $custom_class . ' ', $args['before_widget'], 1);
		}
		if ( 'top' === $position ) {
			$widget_content_dropdown = 'dropdown';
		}
		$args['before_title'] = str_replace('<h2', '<h2 class="widget-title sidebar-shop-filter-title cursor-pointer text-[1rem] font-bold pb-4 text-[#222]' . ( 'top' === $position ? '' : ' act' ) . '"', $args['before_title']);
		if ( empty($instance['widget_content_only']) ) {
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_widget'];
			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
			if ( $title ) {
                //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $args['before_title'] . $title . $args['after_title'];
			}
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf('<div class="widget-content ' . $widget_content_dropdown . '" data-instance="' . esc_attr(wp_json_encode($instance)) . '" %1$s>', ! empty($widget_content_style) ? 'style="' . $widget_content_style . '"' : '');
		}

		echo '<div class="widget-content-inner">';
	}

	/**
	 * Output the html at the end of a widget.
	 *
	 * @param mixed $args Args.
	 */
	public function widget_end( $args ): void {
		echo '</div>';  // .widget-content-inner

		if ( empty($instance['widget_content_only']) ) {
			echo '</div>'; // .widget-content
            //phpcs:ignore
			echo $args['after_widget'];
		}
	}

	/**
	 * Get hook name position in admin form.
	 *
	 * @param string $position Position.
	 * @return string
	 */
	public function get_hook_name_form( string $position = 'before' ): string {
		return str_replace('-', '_', $this->id_base) . '_' . $position . '_form';
	}

	/**
	 * Render Attributes
	 *
	 * @param array $attributes Attributes.
	 * @return string
	 */
	public function render_attributes( array $attributes = [] ): string {
		$attr_str = '';

		foreach ( $attributes as $name => $value ) {
			switch ( $name ) {
				case 'href':
				case 'src':
					$attr_str .= ' ' . $name . '="' . esc_url($value) . '"';
					break;
				case 'class':
					$value = is_array($value) ? implode(' ', $value) : $value;

					$attr_str .= ' ' . $name . '="' . esc_attr($value) . '"';
					break;
				default:
					$value = is_array($value) ? wp_json_encode($value) : $value;

					$attr_str .= ' ' . $name . '="' . esc_attr($value) . '"';
					break;
			}
		}

		return $attr_str;
	}

	/**
	 * Check has dependency.
	 *
	 * @param string $control Widget Control ID.
	 * @return string
	 */
	public function has_depend( string $control ): string {
		$has_depend = '';
		foreach ( $this->settings as $control_id => $control_settings ) {
			if ( ! empty($control_settings['condition']) ) {
				foreach ( $control_settings['condition'] as $depend_control => $depend_control_condition ) {
					if ( $depend_control === $control ) {
						$has_depend = 'rbb-widget-control__has_depend';
					}
				}
			}
		}
		return $has_depend;
	}
}
