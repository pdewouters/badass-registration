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
	 * @param $fields
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

	/**
	 * @var
	 */
	protected $id_attr;

	/**
	 * @var
	 */
	protected $name_attr;

	/**
	 * @var
	 */
	protected $classes_attr;

	/**
	 * @var
	 */
	protected $value_attr;

	/**
	 * @var
	 */
	protected $label_text;

	/**
	 * @param $attribute
	 * @param $value
	 */
	public function __set( $attribute, $value ) {

		$this->$attribute = $value;
	}

	/**
	 * @param $attribute
	 *
	 * @return mixed
	 */
	public function __get( $attribute ) {
		return $this->$attribute;
	}

}

/**
 * Class Text_Field
 * @package BadassRegistration
 */
class Text_Field extends Field {

	/**
	 * @param $attributes
	 */
	public function __construct( array $attributes ) {

		$this->id_attr      = $attributes['id'];
		$this->name_attr    = $attributes['name'];
		$this->classes_attr = $attributes['classes'];
		$this->value_attr   = $attributes['value'];
		$this->label_text   = $attributes['label_text'];

	}

	/**
	 * @param $value
	 *
	 * @return array|string
	 */
	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf(
			'<label>%1$s</label><br><input type="text" id="%2$s" name="%3$s" value="%4$s" class="%5$s" />',
			esc_attr( $this->label_text ),
			esc_attr( $this->id_attr ),
			esc_attr( $this->name_attr ),
			esc_attr( $this->value_attr ),
			esc_attr( $this->classes_attr )
		);
	}
}
