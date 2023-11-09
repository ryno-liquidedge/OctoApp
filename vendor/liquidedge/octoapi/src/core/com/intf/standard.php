<?php

namespace octoapi\core\com\intf;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
abstract class standard {
	//--------------------------------------------------------------------------------
	/**
	 * The display name of the class. This could be used on the UI.
	 *
	 * @var string
	 */
	protected $name = null;
	/**
	 * Specifies whether this class is a singleton. Singletons can only have one
	 * instance of itself created at any time. To use this feature - the entire line
	 * declaration has to be copied to the extending class and then set to true.
	 *
	 * @var bool
	 */
	protected static $is_singleton = null;
	/**
	 * When this class is set to be a singleton, this holds a reference to each instance
	 * that was created.
	 *
	 * @var standard[]
	 */
	protected static $singleton_arr = [];
	//--------------------------------------------------------------------------------
	/**
	 * Constructs the instance of the class. It is protected and cannot be instanced via
	 * the new keyword from an external source.
	 *
	 * @param array $options A set of key value pairs that needs to be initialized on creation.
	 */
	protected function __construct($options = []) {
	}
	//--------------------------------------------------------------------------------
	/**
	 * Returns the class name of this instance. For example when the full name is
	 * \com\core\db\hello - this function will simply return 'hello'.
	 *
	 * @return string
	 */
	public function get_class() {
		return get_called_class();
	}
	//--------------------------------------------------------------------------------
	/**
	 * Returns the readable display name of the class.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Creates a new instance of the class. When this class is set to be a singleton,
	 * only one instance of this class will be created and subsequent calls will return
	 * the instance that was created previously.
	 *
	 * @param array $options A set of key value pairs that needs to be initialized on creation.
	 *
	 * @return static
	 */
	public static function make($options = []) {
		// create new instance every time
		if (!static::$is_singleton) return new static($options);

		// did we create a new instance already
		$class = get_called_class();
		if (isset(static::$singleton_arr[$class])) return static::$singleton_arr[$class];

		// create and return new instance
		static::$singleton_arr[$class] = new static($options);
		return static::$singleton_arr[$class];
	}
	//--------------------------------------------------------------------------------
}