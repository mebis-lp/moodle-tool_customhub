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
 * On this page the user selects where he wants to publish the course
 *
 * @package    tool_customhub
 * @copyright  Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$PAGE->set_url('/admin/tool/customhub/hubselector.php', ['courseid' => $course->id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($course->fullname);

$registrationmanager = new tool_customhub\registration_manager();
$registeredhubs = $registrationmanager->get_registered_on_hubs();
if (empty($registeredhubs)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('publishon', 'tool_customhub'), 3, 'main');
    echo $OUTPUT->box(get_string('notregisteredonhub', 'tool_customhub'));
    echo $OUTPUT->footer();
    die();
}

$share = optional_param('share', false, PARAM_BOOL);
$advertise = optional_param('advertise', false, PARAM_BOOL);

if (count($registeredhubs) == 1) {
    $registeredhub = array_shift($registeredhubs);
    $params = [
        'sesskey' => sesskey(),
        'courseid' => $courseid,
        'huburl' => $registeredhub->huburl,
        'hubname' => $registeredhub->hubname,
        'share' => $share,
        'advertise' => $advertise,
    ];
    redirect(new moodle_url(
        "/admin/tool/customhub/metadata.php",
        $params
    ));
}

$hubselectorform = new tool_customhub\hub_publish_selector_form(
    '',
    [
        'courseid' => $courseid,
        'share' => $share,
        'advertise' => $advertise
    ]
);
$fromform = $hubselectorform->get_data();

//// Redirect to the registration form if an URL has been chosen ////
$huburl = optional_param('huburl', false, PARAM_URL);

//redirect
if (!empty($huburl) and confirm_sesskey()) {
    $hubname = optional_param(clean_param($huburl, PARAM_ALPHANUMEXT), '', PARAM_TEXT);
    $params = [
        'sesskey' => sesskey(),
        'courseid' => $courseid,
        'huburl' => $huburl,
        'hubname' => $hubname,
        'share' => $share,
        'advertise' => $advertise
    ];
    redirect(new moodle_url(
        "/admin/tool/customhub/metadata.php",
        $params
    ));
}


//// OUTPUT ////


echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('publishon', 'tool_customhub'), 3, 'main');
$hubselectorform->display();
echo $OUTPUT->footer();
