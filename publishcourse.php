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
 * The user selects if he wants to publish the course on Moodle.org hub or
 * on a specific hub. The site must be registered on a hub to be able to
 * publish a course on it.
 *
 * @package    tool_customhub
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);
$hubname = optional_param('hubname', '', PARAM_TEXT);
$huburl = optional_param('huburl', '', PARAM_URL);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

require_login($course);
$context = context_course::instance($course->id);
require_capability('tool/customhub:publishcourse', $context);
$shortname = format_string($course->shortname, true, ['context' => $context]);

$PAGE->set_url('/admin/tool/customhub/publishcourse.php', ['courseid' => $course->id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($course->fullname);

//check that the PHP xmlrpc extension is enabled
if (!extension_loaded('xmlrpc')) {
    $notificationerror = $OUTPUT->doc_link('admin/environment/php_extension/xmlrpc', '');
    $notificationerror .= get_string('xmlrpcdisabledpublish', 'tool_customhub');
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('publishcourse', 'tool_customhub', $shortname), 3, 'main');
    echo $OUTPUT->notification($notificationerror);
    echo $OUTPUT->footer();
    die();
}

$publicationmanager = new tool_customhub\course_publish_manager();
$confirmmessage = '';

//update the courses status
$updatestatusid = optional_param('updatestatusid', false, PARAM_INT);
if (!empty($updatestatusid) && confirm_sesskey()) {
    //get the communication token from the publication
    $hub = $publicationmanager->get_registeredhub_by_publication($updatestatusid);
    if (empty($hub)) {
        $confirmmessage = $OUTPUT->notification(get_string('nocheckstatusfromunreghub', 'tool_customhub'));
    } else {
        //get all site courses registered on this hub
        $function = 'hub_get_courses';
        $params = [
            'search' => '',
            'downloadable' => 1,
            'enrollable' => 1,
            'options' => ['allsitecourses' => 1]
        ];
        $serverurl = $hub->huburl."/local/hub/webservice/webservices.php";
        require_once($CFG->dirroot."/webservice/xmlrpc/lib.php");
        $xmlrpcclient = new webservice_xmlrpc_client($serverurl, $hub->token);
        $result = $xmlrpcclient->call($function, $params);
        $sitecourses = $result['courses'];

        //update status for all these course
        foreach ($sitecourses as $sitecourse) {
            //get the publication from the hub course id
            $publication = $publicationmanager->get_publication($sitecourse['id'], $hub->huburl);
            if (!empty($publication)) {
                $publication->status = $sitecourse['privacy'];
                $publication->timechecked = time();
                $publicationmanager->update_publication($publication);
            } else {
                $msgparams = new stdClass();
                $msgparams->id = $sitecourse['id'];
                $msgparams->hubname = html_writer::tag('a', $hub->hubname, ['href' => $hub->huburl]);
                $confirmmessage .= $OUTPUT->notification(
                    get_string('detectednotexistingpublication', 'tool_customhub', $msgparams));
            }
        }
    }
}

//if the site os registered on no hub display an error page
$registrationmanager = new tool_customhub\registration_manager();
$registeredhubs = $registrationmanager->get_registered_on_hubs();
if (empty($registeredhubs)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('publishon', 'tool_customhub'), 3, 'main');
    echo $OUTPUT->box(get_string('notregisteredonhub', 'tool_customhub'));
    echo $OUTPUT->footer();
    die();
}

$renderer = $PAGE->get_renderer('tool_customhub');

/// UNPUBLISH
$cancel = optional_param('cancel', 0, PARAM_BOOL);
if (!empty($cancel) and confirm_sesskey()) {
    $confirm = optional_param('confirm', 0, PARAM_BOOL);
    $hubcourseid = optional_param('hubcourseid', 0, PARAM_INT);
    $publicationid = optional_param('publicationid', 0, PARAM_INT);
    $timepublished = optional_param('timepublished', 0, PARAM_INT);
    $publication = new stdClass();
    $publication->courseshortname = $course->shortname;
    $publication->courseid = $course->id;
    $publication->hubname = $hubname;
    $publication->huburl = $huburl;
    $publication->hubcourseid = $hubcourseid;
    $publication->timepublished = $timepublished;
    if (empty($publication->hubname)) {
        $publication->hubname = $huburl;
    }
    $publication->id = $publicationid;
    if($confirm) {
        //unpublish the publication by web service
        $registeredhub = $registrationmanager->get_registeredhub($huburl);
        $function = 'hub_unregister_courses';
        $params = ['courseids' => [ $publication->hubcourseid]];
        $serverurl = $huburl."/local/hub/webservice/webservices.php";
        require_once($CFG->dirroot."/webservice/xmlrpc/lib.php");
        $xmlrpcclient = new webservice_xmlrpc_client($serverurl, $registeredhub->token);
        $result = $xmlrpcclient->call($function, $params);

        //delete the publication from the database
        $publicationmanager->delete_publication($publicationid);

        //display confirmation message
        $confirmmessage = $OUTPUT->notification(get_string('courseunpublished', 'tool_customhub', $publication), 'notifysuccess');

    } else {
        //display confirmation page for unpublishing

        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('unpublishcourse', 'tool_customhub', $shortname), 3, 'main');
        echo $renderer->confirmunpublishing($publication);
        echo $OUTPUT->footer();
        die();
    }
}

//check if a course was published
if (optional_param('published', 0, PARAM_TEXT)) {
    $confirmmessage = $OUTPUT->notification(get_string(
        'coursepublished',
        'tool_customhub',
        empty($hubname) ? $huburl : $hubname
    ), 'notifysuccess');
}


/// OUTPUT
echo $OUTPUT->header();
echo $confirmmessage;

echo $OUTPUT->heading(get_string('publishcourse', 'tool_customhub', $shortname), 3, 'main');
echo $renderer->publicationselector($course->id);

$publications = $publicationmanager->get_course_publications($course->id);
if (!empty($publications)) {
    echo $OUTPUT->heading(get_string('publishedon', 'tool_customhub'), 3, 'main');
    echo $renderer->publishhublisting($course->id, $publications);
}

echo $OUTPUT->footer();
