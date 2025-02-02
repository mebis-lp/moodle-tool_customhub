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
 * This page display the publication metadata form
 *
 * @package    tool_customhub
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/customhub/constants.php');

//check user access capability to this page
$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);
require_capability('tool/customhub:publishcourse', context_course::instance($courseid));

//page settings
$PAGE->set_url('/admin/tool/customhub/metadata.php', array('id' => $course->id));
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($course->fullname);

//check that the PHP xmlrpc extension is enabled
if (!extension_loaded('xmlrpc')) {
    $errornotification = $OUTPUT->doc_link('admin/environment/php_extension/xmlrpc', '');
    $errornotification .= get_string('xmlrpcdisabledpublish', 'tool_customhub');
    $context = context_course::instance($course->id);
    $shortname = format_string($course->shortname, true, ['context' => $context]);
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('publishcourse', 'tool_customhub', $shortname), 3, 'main');
    echo $OUTPUT->notification($errornotification);
    echo $OUTPUT->footer();
    die();
}

//retrieve hub name and hub url
$huburl = optional_param('huburl', '', PARAM_URL);
$hubname = optional_param('hubname', '', PARAM_TEXT);
if (empty($huburl) or !confirm_sesskey()) {
    throw new moodle_exception('missingparameter');
}

$advertise = optional_param('advertise', false, PARAM_BOOL); // Share to enrol.
$share = optional_param('share', false, PARAM_BOOL); // Share to download.

// if ( strpos($huburl, strtolower(HUB_TEACHSHARE_NAME)) !== false &&  $share) {
//     // $coursepublicationform = new tool_customhub\form\course_publication_form(
//     // In case of saving anonymized userdata, this param is not empty.
//     $form1data = optional_param('form1data', null, PARAM_ALPHANUM);
//     if ($form1data) {
//         $thisurl->param('form1data', $form1data);
//     }

//     if (!$form1data) {
//         // Show the main template form.
//         $customdata = [
//             'huburl' => $huburl,
//             'hubname' => $hubname,
//             'sesskey' => sesskey(),
//             'courseid' => $course->id,
//             'coursename' => $course->fullname,
//             'author' => $USER,
//             'edittemplate' => false,
//             'templateid' => 0,
//             'share' => $share,
//         ];
//         $coursepublicationform = new \block_mbsteachshare\form\sendtemplate(null, $customdata);
//     } else {
//         // Show the secondary 'select activities' form in case we store template with anonymized userdata.
//         $customdata = [
//             'huburl' => $huburl,
//             'hubname' => $hubname,
//             'sesskey' => sesskey(),
//             'courseid' => $course->id,
//             'courseid' => $course->fullname,
//             'form1data' => $form1data,
//             'edittemplate' => false,
//             'templateid' => 0,
//             'share' => $share,
//         ];
//         $coursepublicationform = new \block_mbsteachshare\form\sendtemplate_activities(null, $customdata);
//     }
// } else if ($advertise) {
// Set the publication forms
$coursepublicationform = new tool_customhub\form\course_publication_form(
    '',
    [
        'huburl' => $huburl,
        'hubname' => $hubname,
        'sesskey' => sesskey(),
        'course' => $course,
        'advertise' => $advertise,
        'share' => $share,
        'id' => $courseid,
        'page' => $PAGE,
        'edittemplate' => false,
    ]
);
// }

$fromform = $coursepublicationform->get_data();
//retrieve the token to call the hub
$registrationmanager = new tool_customhub\registration_manager();
$registeredhub = $registrationmanager->get_registeredhub($huburl);

//setup web service xml-rpc client
$serverurl = $huburl . "/local/hub/webservice/webservices.php";
require_once($CFG->dirroot . "/webservice/xmlrpc/lib.php");
$xmlrpcclient = new webservice_xmlrpc_client($serverurl, $registeredhub->token);

if (!empty($fromform)) {
    $publicationmanager = new tool_customhub\course_publish_manager();

    //retrieve the course information
    $courseinfo = new stdClass();
    $courseinfo->fullname = $fromform->name;
    $courseinfo->shortname = $fromform->courseshortname;
    $courseinfo->description = $fromform->description;
    // $courseinfo->language = $fromform->language;
    $courseinfo->publishername = $fromform->publishername;
    $courseinfo->publisheremail = $fromform->publisheremail;
    $courseinfo->contributornames = $fromform->contributornames;
    // $courseinfo->coverage = $fromform->coverage;
    $courseinfo->creatorname = $fromform->creatorname;
    // $courseinfo->licenceshortname = $fromform->licence;
    $courseinfo->audience = $fromform->audience;
    // $courseinfo->educationallevel = $fromform->educationallevel;
    $creatornotes = $fromform->creatornotes;
    $courseinfo->creatornotes = $creatornotes['text'];
    $courseinfo->creatornotesformat = $creatornotes['format'];
    $courseinfo->sitecourseid = $courseid;
    
    if($share) {
        $courseinfo->subject = join(",", $fromform->subject);
        $courseinfo->schooltype = json_encode($fromform->schooltype);
        $courseinfo->schoolyear = json_encode($fromform->schoolyear);
        $courseinfo->withanon = $fromform->withanon;
        $courseinfo->tags = $fromform->tags;
        $courseinfo->license = $fromform->license;
        $courseinfo->legalinfo_foreignstuff = $fromform->legalinfo_foreignstuff;
        $courseinfo->legalinfo_foreinstuffchanged = $fromform->legalinfo_foreinstuffchanged;
        $courseinfo->legalinfo_ownstuff = $fromform->legalinfo_ownstuff;
        $courseinfo->legalinfo_audioandimages = $fromform->legalinfo_audioandimages;
        $courseinfo->legalinfo_privatedata = $fromform->legalinfo_privatedata;
        $courseinfo->legalinfo_termsofuse = $fromform->legalinfo_termsofuse;
        $courseinfo->enrollable = false;
    }

    if  ($advertise) {
        // Create enrol_lti.
        $ltihelper = new \tool_customhub\local\lti_helper();
        $ltitool = $ltihelper->create_lti_enrolment(get_course($courseid));
        // print_r($ltitool);die;
        $courseinfo->ltitool = json_encode(['secret' => $ltitool->secret]);
        $courseinfo->courseurl = (string)\enrol_lti\helper::get_cartridge_url($ltitool);
        $courseinfo->enrollable = true;
    }

    $courseinfo->share = $fromform->share;

    if (!empty($fromform->deletescreenshots)) {
        $courseinfo->deletescreenshots = $fromform->deletescreenshots;
    }
    // if ($share) {
    //     // $courseinfo->demourl = $fromform->demourl;
    //     // $courseinfo->enrollable = false;
    // } else {
    //     // $courseinfo->courseurl = $fromform->courseurl;
    //     // $courseinfo->enrollable = true;
    // }

    //retrieve the outcomes of this course
    require_once($CFG->libdir . '/grade/grade_outcome.php');
    $outcomes = grade_outcome::fetch_all_available($courseid);
    if (!empty($outcomes)) {
        foreach ($outcomes as $outcome) {
            $sentoutcome = new stdClass();
            $sentoutcome->fullname = $outcome->fullname;
            $courseinfo->outcomes[] = $sentoutcome;
        }
    }

    //retrieve the content information from the course
    $coursecontext = context_course::instance($course->id);
    $courseblocks = $publicationmanager->get_block_instances_by_context($coursecontext->id, 'blockname');

    if (!empty($courseblocks)) {
        $blockname = '';
        foreach ($courseblocks as $courseblock) {
            if ($courseblock->blockname != $blockname) {
                if (!empty($blockname)) {
                    $courseinfo->contents[] = $content;
                }

                $blockname = $courseblock->blockname;
                $content = new stdClass();
                $content->moduletype = 'block';
                $content->modulename = $courseblock->blockname;
                $content->contentcount = 1;
            } else {
                $content->contentcount = $content->contentcount + 1;
            }
        }
        $courseinfo->contents[] = $content;
    }

    $activities = get_fast_modinfo($course, $USER->id);
    foreach ($activities->instances as $activityname => $activitydetails) {
        $content = new stdClass();
        $content->moduletype = 'activity';
        $content->modulename = $activityname;
        $content->contentcount = count($activities->instances[$activityname]);
        $courseinfo->contents[] = $content;
    }

    //save into screenshots field the references to the screenshot content hash
    //(it will be like a unique id from the hub perspective)
    if (!empty($fromform->deletescreenshots) or $share) {
        $courseinfo->screenshots = 0;
    } else {
        $courseinfo->screenshots = $fromform->existingscreenshotnumber;
    }
    if (!empty($fromform->screenshots)) {
        $screenshots = $fromform->screenshots;
        $fs = get_file_storage();
        $files = $fs->get_area_files(context_user::instance($USER->id)->id, 'user', 'draft', $screenshots);
        if (!empty($files)) {
            $courseinfo->screenshots = $courseinfo->screenshots + count($files) - 1; //minus the ./ directory
        }
    }

    // PUBLISH ACTION

    // Publish the course information.
    $function = 'hub_register_courses';
    $params = [
        'courses' => [$courseinfo],
        // 'wstoken' => $registeredhub->token,
    ];

    try {
        $courseids = $xmlrpcclient->call($function, $params);

        $event = \tool_customhub\event\course_registration_finished::create(
            [
                'context' => context_system::instance(),
                'other' => json_encode(['courseregids' => $courseids, 'courseinfo' => $courseinfo])
            ]
        );
        $event->trigger();

    } catch (Exception $e) {
        throw new moodle_exception(
            'errorcoursepublish',
            'tool_customhub',
            new moodle_url('/course/view.php', ['id' => $courseid]),
            $e->getMessage()
        );
    }

    if (count($courseids) != 1) {
        throw new moodle_exception('errorcoursewronglypublished', 'tool_customhub');
    }

    // Save the record into the published course table.
    $publication = $publicationmanager->get_publication($courseids[0], $huburl);
    if (empty($publication)) {
        //if never been published or if we share, we need to save this new publication record
        $publicationmanager->add_course_publication($registeredhub->huburl, $course->id, !$share, $courseids[0]);
    } else {
        //if we update the enrollable course publication we update the publication record
        $publicationmanager->update_enrollable_course_publication($publication->id);
    }

    // SEND FILES
    $curl = new curl();

    // send screenshots
    if (!empty($fromform->screenshots)) {

        if (!empty($fromform->deletescreenshots) or $share) {
            $screenshotnumber = 0;
        } else {
            $screenshotnumber = $fromform->existingscreenshotnumber;
        }
        foreach ($files as $file) {
            if ($file->is_valid_image()) {
                $screenshotnumber = $screenshotnumber + 1;
                $params = array();
                $params['filetype'] = HUB_SCREENSHOT_FILE_TYPE;
                $params['file'] = $file;
                $params['courseid'] = $courseids[0];
                $params['filename'] = $file->get_filename();
                $params['screenshotnumber'] = $screenshotnumber;
                $params['token'] = $registeredhub->token;
                $curl->post($huburl . "/local/hub/webservice/upload.php", $params);
            }
        }
    }

    // Redirect to the backup process page.
    // var_dump($share);die;
    if ($share) {
        $params = [
            'sesskey' => sesskey(),
            'id' => $courseid,
            'hubcourseid' => $courseids[0],
            'huburl' => $huburl,
            'hubname' => $hubname
        ];
        $backupprocessurl = new moodle_url("/admin/tool/customhub/backup.php", $params);
        redirect($backupprocessurl);
    } else {
        // Redirect to the index publish page.
        redirect(new moodle_url(
            '/admin/tool/customhub/publishcourse.php',
            [
                'sesskey' => sesskey(),
                'id' => $courseid,
                'published' => true,
                'hubname' => $hubname,
                'huburl' => $huburl
            ]
        ));
    }
}

/////// OUTPUT SECTION /////////////

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('publishcourseon', 'tool_customhub', !empty($hubname) ? $hubname : $huburl), 3, 'main');

//display hub information (logo, name, description)
$function = 'hub_get_info';
$params = array();
try {
    $hubinfo = $xmlrpcclient->call($function, $params);
} catch (Exception $e) {
    //only print error log in apache (for backward compatibility)
    error_log(print_r($e->getMessage(), true));
}
$renderer = $PAGE->get_renderer('tool_customhub');
if (!empty($hubinfo)) {
    echo $renderer->hubinfo($hubinfo);
}

//display metadata form
$coursepublicationform->display();
echo $OUTPUT->footer();
