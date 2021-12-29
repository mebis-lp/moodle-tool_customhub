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
 * This page display the publication backup form
 *
 * @package    tool_customhub
 * @copyright  Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

define('NO_OUTPUT_BUFFERING', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/moodle2/backup_plan_builder.class.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/customhub/constants.php');

//retrieve initial page parameters
$id = required_param('id', PARAM_INT);
$hubcourseid = required_param('hubcourseid', PARAM_INT);
$huburl = required_param('huburl', PARAM_URL);
$hubname = optional_param('hubname', '', PARAM_TEXT);

//some permissions and parameters checking
$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_login($course);
require_capability('tool/customhub:publishcourse', context_course::instance($id));
require_sesskey();

//page settings
$PAGE->set_url('/admin/tool/customhub/backup.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($course->fullname);

//BEGIN backup processing
// $backupid = optional_param('backup', false, PARAM_ALPHANUM);
// if (!($bc = backup_ui::load_controller($backupid))) {
//     $bc = new backup_controller(backup::TYPE_1COURSE, $id, backup::FORMAT_MOODLE,
//         backup::INTERACTIVE_YES, backup::MODE_HUB, $USER->id);
// }
// $backup = new backup_ui(
//     $bc,
//     [
//         'id' => $id,
//         'hubcourseid' => $hubcourseid,
//         'huburl' => $huburl,
//         'hubname' => $hubname
//     ]
// );
// $backup->process();
// if ($backup->get_stage() == backup_ui::STAGE_FINAL) {
//     $backup->execute();
// } else {
//     $backup->save_controller();
// }
list($filename, $backupfiledir) = \tool_customhub\local\backup_helper::create_backup($course->id);

$filerecord = new stdClass();
$filerecord->component = 'tool_customhub';
$filerecord->contextid = CONTEXT_SYSTEM;
$filerecord->filearea  = 'teachshare';
$filerecord->filename  = $filename;
$filerecord->filepath  = '/';
$filerecord->itemid = 15021984;

$fs = new file_storage();
$backupfile = $fs->create_file_from_pathname($filerecord, $backupfiledir);

// if ($backup->get_stage() !== backup_ui::STAGE_COMPLETE) {
//     $renderer = $PAGE->get_renderer('core', 'backup');
//     echo $OUTPUT->header();
//     echo $OUTPUT->heading(get_string('publishcourseon', 'tool_customhub', !empty($hubname) ? $hubname : $huburl), 3, 'main');
//     if ($backup->enforce_changed_dependencies()) {
//         debugging('Your settings have been altered due to unmet dependencies', DEBUG_DEVELOPER);
//     }
//     echo $renderer->progress_bar($backup->get_progress_bar());
//     echo $backup->display($renderer);
//     echo $OUTPUT->footer();
//     die();
// }

//$backupfile = $backup->get_stage_results();
// $backupfile = $bc->get_results();
// $backupfile = $backupfile['backup_destination'];
//END backup processing

// $fo = fopen(__DIR__ . "/../../../local/hub/log.txt", "a+");
// fwrite($fo, "\n" . json_encode($backupfile));

//retrieve the token to call the hub
$registrationmanager = new tool_customhub\registration_manager();
$registeredhub = $registrationmanager->get_registeredhub($huburl);

//display the sending file page
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('sendingcourse', 'tool_customhub'), 3, 'main');
$renderer = $PAGE->get_renderer('tool_customhub');
echo $renderer->sendingbackupinfo($backupfile);
if (ob_get_level()) {
    ob_flush();
}
flush();

// Send backup file to the hub.
// fwrite($fo, "\n Backup send start.");
// fwrite($fo, "\n Backup-Token: " . $registeredhub->token);
// fwrite($fo, "\n Backup-FilePath: " . $backupfiledir);

$ch = curl_init();

$params = [];
curl_setopt($ch, CURLOPT_POST, 1);


// Get the response from cURL.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set the url.
curl_setopt($ch, CURLOPT_URL, $huburl . "/local/hub/webservice/upload.php");

$params['file'] = new CurlFile($backupfiledir, 'application/vnd.moodle.backup');
$params['filetype'] = HUB_BACKUP_FILE_TYPE;
$params['courseid'] = $hubcourseid;
$params['token'] = $registeredhub->token;

// Following line is compulsary to add as it is.
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$response = curl_exec($ch);
curl_close($ch);
// fwrite($fo, "\n Response:" . json_encode($response));

// Delete the temp backup file from user_tohub aera.
$backupfile->delete();
// $bc->destroy();

// Output sending success.
echo $renderer->sentbackupinfo($id, $huburl, $hubname);

echo $OUTPUT->footer();
