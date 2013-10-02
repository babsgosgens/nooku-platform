<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Database Row Strategy Interface
 *
 * Provides an interface for querying activity stream data from activity database rows. Database rows implementing this
 * interface can also be casted to strings.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface DatabaseRowActivityStrategyInterface
{
    /**
     * Row setter
     *
     * @param DatabaseRowActivity $activity The activity row object.
     *
     * @return $this.
     */
    public function setRow(DatabaseRowActivity $row);

    /**
     * Row getter.
     *
     * @return DatabaseRowActivity The activity row object.
     */
    public function getRow();

    /**
     * Tells if the activity object still exists, i.e. it is still stored or reachable.
     *
     * @return boolean True if still exists, false otherwise.
     */
    public function objectExists();

    /**
     * Activity object URL getter.
     *
     * @return string|null The activity object URL, null if not linkable.
     */
    public function getObjectUrl();

    /**
     * Tells if the activity target still exists, i.e. it is still stored or reachable.
     *
     * @return boolean True if still exists, false otherwise.
     */
    public function targetExists();

    /**
     * Activity target URL getter.
     *
     * @return string|null The activity target URL, null if not linkable.
     */
    public function getTargetUrl();

    /**
     * Tells if the activity has a target.
     *
     * @return boolean True if it has a target, false otherwise.
     */
    public function hasTarget();

    /**
     * Tells if the activity actor still exists, i.e. it is still stored or reachable.
     *
     * @return boolean True if still exists, false otherwise.
     */
    public function actorExists();

    /**
     * Activity actor URL getter.
     *
     * @return string|null The activity actor URL, null if not linkable or reachable.
     */
    public function getActorUrl();

    /**
     * Activity stream data getter.
     *
     * @return array The activity stream data from the attached row object.
     */
    public function getStreamData();

    /**
     * Casts the attached activity row object to a string.
     *
     * @param boolean $html Whether to output HTML or plain text.
     *
     * @return string The string representation of the attached activity row object.
     */
    public function toString($html = true);

    /**
     * Activity icon getter.
     *
     * @return string The activity icon class.
     */
    public function getIcon();
}