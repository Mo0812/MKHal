<?php
namespace MK\HAL;

/**
 * HAL link resource representation
 */
class HALLink implements \JsonSerializable {

    public $href;
    public $title;
    public $name;
    public $templated;

    /**
     * Constructor with different attribute options for a link. Only href is necessary
     *
     * @param string $href
     * @param string $title
     * @param string $name
     * @param boolean $templated
     */
    function __construct(string $href, string $title = null, string $name = null, bool $templated = null) {
        $this->href = $href;
        $this->title = $title;
        $this->name = $name;
        $this->templated = $templated;
    }

    /**
     * Interface integration to export link easily to JSON string
     *
     * @return void
     */
    public function jsonSerialize() {
        $arr = array();
        
        foreach(get_object_vars($this) as $attr => $val) {
            if(!is_null($val)) {
                $arr[$attr] = $val;
            }
        }
        return $arr;
    }

}