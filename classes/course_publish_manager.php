<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Class course_publish_manager.
 *
 * @package    tool_customhub
 * @copyright  2017 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_customhub;

use stdClass;
use \tool_customhub\local\lti_helper;

defined('MOODLE_INTERNAL') || die();

/**
 * Course publication library
 *
 * @package   tool_customhub
 * @copyright 2010 Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_publish_manager {

    /**
     * Record a course publication
     * @param int $hubid the hub id from the 'registered on hub' table
     * @param int $courseid the course id from site point of view
     * @param int $enrollable if the course is enrollable = 1, if downloadable = 0
     * @param int $hubcourseid the course id from the hub point of view
     */
    public function add_course_publication($huburl, $courseid, $enrollable, $hubcourseid) {
        global $DB;
        $publication = new stdClass();
        $publication->huburl = $huburl;
        $publication->courseid = $courseid;
        $publication->hubcourseid = $hubcourseid;
        $publication->enrollable = (int) $enrollable;
        $publication->timepublished = time();
        $DB->insert_record('course_published', $publication);
    }

    /**
     * Update a enrollable course publication
     * @param int $publicationid
     */
    public function update_enrollable_course_publication($publicationid) {
        global $DB;
        $publication = new stdClass();
        $publication->id = $publicationid;
        $publication->timepublished = time();
        $DB->update_record('course_published', $publication);
    }

    /**
     * Update a course publication
     * @param object $publication
     */
    public function update_publication($publication) {
        global $DB;
        $DB->update_record('course_published', $publication);
    }

    /**
     * Get courses publications
     * @param int $hubid specify a hub
     * @param int $courseid specify a course
     * @param int $enrollable specify type of publication (enrollable or downloadable)
     * @return array of publications
     */
    public function get_publications($huburl = null, $courseid = null, $enrollable = -1) {
        global $DB;
        $params = array();

        if (!empty($huburl)) {
            $params['huburl'] = $huburl;
        }

        if (!empty($courseid)) {
            $params['courseid'] = $courseid;
        }

        if ($enrollable != -1) {
            $params['enrollable'] = (int) $enrollable;
        }

        return $DB->get_records('course_published', $params);
    }

    /**
     * Get a publication for a course id on a hub
     * (which is either the id of the unique possible enrollable publication of a course,
     * either an id of one of the downloadable publication)
     * @param int $hubcourseid
     * @param string $huburl
     * @return object publication
     */
    public function get_publication($hubcourseid, $huburl) {
        global $DB;
        return $DB->get_record('course_published',
            array('hubcourseid' => $hubcourseid, 'huburl' => $huburl));
    }

    /**
     * Get a publication by a condition.
     * @param array $conditions
     * @return object publication
     */
    public function get_publication_by_condition($conditions) {
        global $DB;
        return $DB->get_record(
            'course_published',
            $conditions
        );
    }

    /**
     * Get all publication for a course
     * @param int $courseid
     * @return array of publication
     */
    public function get_course_publications($courseid) {
        global $DB;
        $sql = 'SELECT cp.id, cp.status, cp.timechecked, cp.timepublished, rh.hubname,
                       rh.huburl, cp.courseid, cp.enrollable, cp.hubcourseid
                FROM {course_published} cp, {registration_hubs} rh
                WHERE cp.huburl = rh.huburl and cp.courseid = :courseid
                ORDER BY cp.enrollable DESC, rh.hubname, cp.timepublished';
        $params = array('courseid' => $courseid);
        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get the hub concerned by a publication
     * @param int $publicationid
     * @return object the hub (id, name, url, token)
     */
    public function get_registeredhub_by_publication($publicationid) {
        global $DB;
        $sql = 'SELECT rh.huburl, rh.hubname, rh.token
                FROM {course_published} cp, {registration_hubs} rh
                WHERE cp.huburl = rh.huburl and cp.id = :publicationid';
        $params = array('publicationid' => $publicationid);
        return $DB->get_record_sql($sql, $params);
    }

    /**
     * Delete a publication
     * @param int $publicationid
     */
    public function delete_publication($publicationid) {
        global $DB;
        $publication = $this->get_publication_by_condition(['id' => $publicationid]);
        if ($publication->enrollable) {
            $this->remove_lti_enrolment($publication->courseid);
        }
        $DB->delete_records('course_published', array('id' => $publicationid));
    }

    /**
     * Removes the LTI enrolment of a given publication.
     * @param int $publicationid
     */
    public function remove_lti_enrolment($courseid) {
        global $DB;
        
        if(empty($courseid)){
            return;
        }

        $plugins   = enrol_get_plugins(true);
        $instances = enrol_get_instances($courseid, false);

        foreach ($instances as $instance) {
            // With LTI_CUSTOMINT1_IDENTIFIER we take sure that we only delete enrol_lti instances created by tool_customhub.
            if (
                $instance->enrol == lti_helper::ENROL_LTI
                && $instance->customint1 == lti_helper::LTI_CUSTOMINT1_IDENTIFIER
                ) {
                $plugin = $plugins[$instance->enrol];
                $plugin->delete_instance($instance);
            }
        }
    }

    /**
     * Delete publications for a hub
     * @param string $huburl
     * @param int $enrollable
     */
    public function delete_hub_publications($huburl, $enrollable = -1) {
        global $DB;

        $params = array();

        if (!empty($huburl)) {
            $params['huburl'] = $huburl;
        }

        if ($enrollable != -1) {
            $params['enrollable'] = (int) $enrollable;
        }

        $DB->delete_records('course_published', $params);
    }

    /**
     * Get an array of all block instances for a given context
     * @param int $contextid a context id
     * @return array of block instances.
     */
    public function get_block_instances_by_context($contextid, $sort = '') {
        global $DB;
        return $DB->get_records('block_instances', array('parentcontextid' => $contextid), $sort);
    }

    /**
     * Retrieve all the sorted course subjects
     * @return array $subjects
     */
    public function get_sorted_subjects() {
        $subjects = get_string_manager()->load_component_strings('edufields', current_language());

        // Sort the subjects.
        $return  = [];
        asort($subjects);
        foreach ($subjects as $key => $option) {
            $keylength = strlen($key);
            if ($keylength == 12) {
                $return[$key] = $option; // We want only selectable categories.
            }
        }
        return $return;
    }

}
