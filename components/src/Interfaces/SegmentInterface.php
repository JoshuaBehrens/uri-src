<?php
/**
* This file is part of the League.url library
*
* @license http://opensource.org/licenses/MIT
* @link https://github.com/thephpleague/url/
* @version 3.0.0
* @package League.url
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace League\Url\Interfaces;

interface SegmentInterface extends ComponentArrayInterface
{
    /**
     * Append data to the component
     *
     * @param mixed   $data         the data can be a array, a Traversable or a string
     * @param string  $whence       where the data should be prepended to
     * @param integer $whence_index the recurrence index of $whence
     */
    public function append($data, $whence = null, $whence_index = null);

    /**
     * Prepend data to the component
     *
     * @param mixed   $data         the data can be a array, a Traversable or a string
     * @param string  $whence       where the data should be prepended to
     * @param integer $whence_index the recurrence index of $whence
     */
    public function prepend($data, $whence = null, $whence_index = null);

    /**
     * Remove part of the component
     *
     * @param mixed $data the data can be a array, a Traversable or a string
     */
    public function remove($data);
}
