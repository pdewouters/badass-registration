<?php namespace BadassRegistration;

/**
 * Class Fields
 * @package BadassRegistration
 */

/**
 * Class Fields
 * @package BadassRegistration
 */
class Fields implements \Iterator {

	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @var
	 */
	protected $_fields = array();

	/**
	 *
	 */
	public function __construct() {

		$this->position = 0;

	}

	/**
	 *
	 */
	public function rewind() {
		$this->position = 0;
	}

	/**
	 * @return mixed
	 */
	public function current() {
		return $this->_fields[ $this->position ];
	}


	/**
	 * @return int
	 */
	public function key() {
		return $this->position;
	}

	/**
	 *
	 */
	public function next() {
		$this->position += 1;
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return isset( $this->_fields[ $this->position ] );
	}

	/**
	 * @return string
	 */
	public function __toString() {

		ob_start();

		foreach ( $this->_fields as $field ) {
			echo $field;
		}

		return ob_get_clean();
	}

	/**
	 * @param Field $field
	 */
	public function add( Field $field ) {
		$this->_fields[] = $field;
	}

}

/**
 * Class Field
 * @package BadassRegistration
 */
abstract class Field {

	protected $attributes = array();

	/**
	 * @param array $attributes
	 *
	 * @throws \Exception
	 */
	public function __construct( array $attributes ) {

		if ( count( $attributes ) == 0 ) {
			throw new \Exception( 'Attributes array is empty.' );
		}
		
		$this->attributes = $attributes;

	}

	public function get_attribute( $key ) {
		return $this->attributes[ $key ];
	}

	public function set_attribute( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

}

/**
 * Class Text_Field
 * @package BadassRegistration
 */
class Input_Field extends Field {

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf(
			'<label>%1$s</label><br><input type="%2$s" id="%3$s" name="%4$s" value="%5$s" class="%6$s" />',
			esc_attr( $this->get_attribute( 'label' ) ),
			esc_attr( $this->get_attribute( 'type' ) ),
			esc_attr( $this->get_attribute( 'id' ) ),
			esc_attr( $this->get_attribute( 'name' ) ),
			esc_attr( $this->get_attribute( 'value' ) ),
			esc_attr( implode( ' ', $this->get_attribute( 'classes' ) ) )
		);
	}
}

/**
 * Class Select_Field
 * @package BadassRegistration
 */
class Select_Field extends Field {

	/**
	 * @return string
	 */
	public function __toString() {

		$options = '';

		foreach ( $this->attributes['option'] as $key => $option ) {

			$option = sprintf(
				'<option value="%d" %s>%s</option>',
				esc_attr( $key ),
				( array_key_exists( 'selected', $option ) ) ? 'selected' : '',
				esc_attr( $option['text'] )
			);

			$options .= $option;
		}

		return sprintf(
			'<label>%1$s</label><br><select id="%2$s" name="%3$s" class="%4$s" />' . $options . '</select>',
			esc_attr( $this->get_attribute( 'label' ) ),
			esc_attr( $this->get_attribute( 'id' ) ),
			esc_attr( $this->get_attribute( 'name' ) ),
			esc_attr( implode( ' ', $this->get_attribute( 'classes' ) ) )
		);
	}

}
