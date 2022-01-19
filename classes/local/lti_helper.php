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
 * LTI helper class
 *
 * @package    tool_customhub
 * @copyright  2021, ISB Bayern
 * @author     Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace tool_customhub\local;

use context_course;
use stdClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * LTI helper class
 *
 * @package    tool_customhub
 * @copyright  2021, ISB Bayern
 * @author     Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
class lti_helper {
    
    /* Enrolment instance default name */
    const LTI_INSTANCE_DEFAULT_NAME = 'Einschreibung über teachSHARE';

    const ENROL_LTI = 'lti';

    /* A task syncs users, enroling and unenroling them again. */
    const LTI_SYNC_USERS = 1; 
    const LTI_ENROL_AND_UNENROL = 1;
    const LTI_ENROL_NEW_USERS = 2;
    const LTI_UNENROL_MISSINT = 3;
    const LTI_GRADE_SYNC = 1;
    const LTI_NO_GRADE_SYNC = 0;
    const LTI_CUSTOMINT1_IDENTIFIER = 15021984;

    /**
     * Create enrol_lti methode
     * @param object $course
     * @param string $type enrolment methode
     * @return object enrolment instance
     */
    public function create_lti_enrolment($course) {
        global $DB, $CFG;

        // ToDo: This is bad practice but ok for PoC.
        set_config('allowframembedding', 1);

        if (!$enrolinstance = $DB->get_record('enrol', ['courseid' => $course->id, 'enrol' => self::ENROL_LTI])) {
            require_once($CFG->dirroot . '/enrol/lti/lib.php');
            $ltiplugin = new \enrol_lti_plugin();

            $teacherroleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);
            $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student'], MUST_EXIST);

            $fields = [
                'contextid' => context_course::instance($course->id)->id,
                'roleinstructor' => $teacherroleid,
                'rolelearner' => $studentroleid,
                'secret' => random_string(32),
                'name' => self::LTI_INSTANCE_DEFAULT_NAME,
                'membersync' => self::LTI_SYNC_USERS,
                'membersyncmode' => self::LTI_ENROL_AND_UNENROL,
                'customint1' => self::LTI_CUSTOMINT1_IDENTIFIER,
            ];

            $enrolinstance = new stdClass();
            $enrolinstance->id = $ltiplugin->add_instance($course, $fields);
        }
        return $this->get_lti_tool($enrolinstance->id);
    }

    /**
     * Returns the LTI tool.
     *
     * @param int $enrolid
     * @return \stdClass the tool
     */
    private function get_lti_tool($enrolid) {
        global $DB;

        $sql = "SELECT elt.*, e.name, e.courseid, e.status, e.enrolstartdate, e.enrolenddate, e.enrolperiod
                  FROM {enrol_lti_tools} elt
                  JOIN {enrol} e
                    ON elt.enrolid = e.id
                 WHERE e.id = :eid";

        return $DB->get_record_sql($sql, array('eid' => $enrolid), MUST_EXIST);
    }
    
//     $data = new stdClass();
// $data->enrolstartdate = time();
// $data->secret = 'secret';
// $tool = $this->getDataGenerator()->create_lti_tool($data);

}