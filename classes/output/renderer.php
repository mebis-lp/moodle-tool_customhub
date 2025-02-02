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
 * Renderer.
 *
 * @package    tool_customhub
 * @copyright  2017 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_customhub\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use html_writer;
use moodle_url;
use single_button;
use html_table_row;
use html_table;
use stdClass;

/**
 * Registration renderer.
 * @package    tool_customhub
 * @copyright 2010 Moodle Pty Ltd (http://moodle.com)
 * @author    Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Display the listing of registered on hub
     */
    public function registeredonhublisting($hubs) {
        global $CFG;
        $table = new html_table();
        $table->head = array(get_string('hub', 'tool_customhub'), get_string('operation', 'tool_customhub'));
        $table->size = array('80%', '20%');

        foreach ($hubs as $hub) {
            if ($hub->huburl == HUB_MOODLEORGHUBURL) {
                continue;
            }
            $hublink = html_writer::tag('a', $hub->hubname, array('href' => $hub->huburl));
            $hublinkcell = html_writer::tag('div', $hublink, array('class' => 'registeredhubrow'));

            $unregisterhuburl = new moodle_url("/" . $CFG->admin . "/tool/customhub/index.php",
                array('sesskey' => sesskey(), 'huburl' => $hub->huburl,
                    'unregistration' => 1));
            $unregisterbutton = new single_button($unregisterhuburl,
                get_string('unregister', 'tool_customhub'));
            $unregisterbutton->class = 'centeredbutton';
            $unregisterbuttonhtml = $this->output->render($unregisterbutton);

            //add button cells
            $cells = array($hublinkcell, $unregisterbuttonhtml);
            $row = new html_table_row($cells);
            $table->data[] = $row;
        }

        return html_writer::table($table);
    }


    /**
     * Display the selector to advertise or publish a course
     */
    public function publicationselector($courseid) {
        $text = '';

        $advertiseurl = new moodle_url(
            "/admin/tool/customhub/hubselector.php",
            [
                'sesskey' => sesskey(),
                'id' => $courseid,
                'courseid' => $courseid,
                'advertise' => true
            ]
        );
        $advertisebutton = new single_button($advertiseurl, get_string('advertise', 'tool_customhub'));
        $text .= $this->output->render($advertisebutton);
        $text .= html_writer::tag(
            'div',
            get_string('advertisepublication_help', 'tool_customhub'),
            ['class' => 'publishhelp']
        );

        $text .= html_writer::empty_tag('br');  /// TODO Delete

        $uploadurl = new moodle_url(
            "/admin/tool/customhub/hubselector.php",
            [
                'sesskey' => sesskey(),
                'id' => $courseid,
                'courseid' => $courseid,
                'share' => true
            ]
        );
        $uploadbutton = new single_button($uploadurl, get_string('share', 'tool_customhub'));
        $text .= $this->output->render($uploadbutton);
        $text .= html_writer::tag(
            'div',
            get_string('sharepublication_help', 'tool_customhub'),
            ['class' => 'publishhelp']
        );

        return $text;
    }

    /**
     * Display the listing of hub where a course is registered on
     */
    public function publishhublisting($courseid, $publications) {
        global $CFG;
        $table = new html_table();
        $table->head = array(get_string('type', 'tool_customhub'), get_string('hub', 'tool_customhub'),
            get_string('date'), get_string('status', 'tool_customhub'), get_string('operation', 'tool_customhub'));
        $table->size = array('10%', '40%', '20%', '%10', '%15');

        $brtag = html_writer::empty_tag('br');

        foreach ($publications as $publication) {
            // \local_hub\debug\local_hub_debug::write_to_file($publication, 'PublicationRENDERER ');

            $updatebuttonhtml = '';

            $params = [
                'sesskey' => sesskey(),
                'id' => $publication->courseid,
                'hubcourseid' => $publication->hubcourseid,
                'huburl' => $publication->huburl,
                'hubname' => $publication->hubname,
                'cancel' => true,
                'publicationid' => $publication->id,
                'timepublished' => $publication->timepublished
            ];
            $cancelurl = new moodle_url("/admin/tool/customhub/publishcourse.php", $params);
            $cancelbutton = new single_button($cancelurl, get_string('removefromhub', 'tool_customhub'));
            $cancelbutton->class = 'centeredbutton';
            $cancelbuttonhtml = $this->output->render($cancelbutton);

            if ($publication->enrollable) {
                $params = [
                    'sesskey' => sesskey(),
                    'courseid' => $publication->courseid,
                    'huburl' => $publication->huburl,
                    'hubname' => $publication->hubname,
                    'share' => !$publication->enrollable,
                    'advertise' => $publication->enrollable,
                ];
                $updateurl = new moodle_url("/admin/tool/customhub/metadata.php", $params);
                $updatebutton = new single_button($updateurl, get_string('update', 'tool_customhub'));
                $updatebutton->class = 'centeredbutton';
                $updatebuttonhtml = $this->output->render($updatebutton);

                $operations = $updatebuttonhtml . $brtag . $cancelbuttonhtml;
            } else {
                $operations = $cancelbuttonhtml;
            }

            $hubname = html_writer::tag('a',
                $publication->hubname ? $publication->hubname : $publication->huburl,
                array('href' => $publication->huburl));
            //if the publication check time if bigger than May 2010, it has been checked
            if ($publication->timechecked > 1273127954) {
                if ($publication->status == 0) {
                    $status = get_string('statusunpublished', 'tool_customhub');
                } else {
                    $status = get_string('statuspublished', 'tool_customhub');
                }

                $status .= $brtag . html_writer::tag('a', get_string('updatestatus', 'tool_customhub'),
                        array('href' => $CFG->wwwroot . '/'. $CFG->admin .'/tool/customhub/publishcourse.php?id='
                            . $courseid . "&updatestatusid=" . $publication->id
                            . "&sesskey=" . sesskey())) .
                    $brtag . get_string('lasttimechecked', 'tool_customhub') . ": "
                    . format_time(time() - $publication->timechecked);
            } else {
                $status = get_string('neverchecked', 'tool_customhub') . $brtag
                    . html_writer::tag('a', get_string('updatestatus', 'tool_customhub'),
                        array('href' => $CFG->wwwroot . '/'. $CFG->admin .'/tool/customhub/publishcourse.php?id='
                            . $courseid . "&updatestatusid=" . $publication->id
                            . "&sesskey=" . sesskey()));
            }
            //add button cells
            $cells = array($publication->enrollable ?
                get_string('advertised', 'tool_customhub') : get_string('shared', 'tool_customhub'),
                $hubname, userdate($publication->timepublished,
                    get_string('strftimedatetimeshort')), $status, $operations);
            $row = new html_table_row($cells);
            $table->data[] = $row;
        }

        $contenthtml = html_writer::table($table);

        return $contenthtml;
    }

    /**
     * Display unpublishing confirmation page
     * @param object $publication
     *      $publication->courseshortname
    $publication->courseid
    $publication->hubname
    $publication->huburl
    $publication->id
     */
    public function confirmunpublishing($publication) {
        $optionsyes = array('sesskey' => sesskey(), 'id' => $publication->courseid,
            'hubcourseid' => $publication->hubcourseid,
            'huburl' => $publication->huburl, 'hubname' => $publication->hubname,
            'cancel' => true, 'publicationid' => $publication->id, 'confirm' => true);
        $optionsno = array('sesskey' => sesskey(), 'id' => $publication->courseid);
        $publication->hubname = html_writer::tag('a', $publication->hubname,
            array('href' => $publication->huburl));
        $formcontinue = new single_button(new moodle_url("/admin/tool/customhub/publishcourse.php",
            $optionsyes), get_string('unpublish', 'tool_customhub'), 'post');
        $formcancel = new single_button(new moodle_url("/admin/tool/customhub/publishcourse.php",
            $optionsno), get_string('cancel'), 'get');
        return $this->output->confirm(get_string('unpublishconfirmation', 'tool_customhub', $publication),
            $formcontinue, $formcancel);
    }

    /**
     * Display waiting information about backup size during uploading backup process
     * @param object $backupfile the backup stored_file
     * @return $html string
     */
    public function sendingbackupinfo($backupfile) {
        $sizeinfo = new stdClass();
        $sizeinfo->total = number_format($backupfile->get_filesize() / 1000000, 2);
        $html = html_writer::tag('div', get_string('sendingsize', 'tool_customhub', $sizeinfo),
            array('class' => 'courseuploadtextinfo'));
        return $html;
    }

    /**
     * Display upload successfull message and a button to the publish index page
     * @param int $id the course id
     * @param string $huburl the hub url where the course is published
     * @param string $hubname the hub name where the course is published
     * @return $html string
     */
    public function sentbackupinfo($id, $huburl, $hubname) {
        $html = html_writer::tag('div', get_string('sent', 'tool_customhub'),
            array('class' => 'courseuploadtextinfo'));
        $publishindexurl = new moodle_url('/admin/tool/customhub/publishcourse.php',
            array('sesskey' => sesskey(), 'id' => $id,
                'published' => true, 'huburl' => $huburl, 'hubname' => $hubname));
        $continue = $this->output->render(
            new single_button($publishindexurl, get_string('continue')));
        $html .= html_writer::tag('div', $continue, array('class' => 'sharecoursecontinue'));
        return $html;
    }

    /**
     * Hub information (logo - name - description - link)
     * @param object $hubinfo
     * @return string html code
     */
    public function hubinfo($hubinfo) {
        $params = array('filetype' => HUB_HUBSCREENSHOT_FILE_TYPE);
        $imgurl = new moodle_url($hubinfo['url'] .
            "/local/hub/webservice/download.php", $params);
        $screenshothtml = html_writer::empty_tag('img',
            array('src' => $imgurl, 'alt' => $hubinfo['name']));
        $hubdescription = html_writer::tag('div', $screenshothtml,
            array('class' => 'hubscreenshot'));

        $hubdescription .= html_writer::tag('a', $hubinfo['name'],
            array('class' => 'hublink', 'href' => $hubinfo['url'],
                'onclick' => 'this.target="_blank"'));

        $hubdescription .= html_writer::tag('div', format_text($hubinfo['description'], FORMAT_PLAIN),
            array('class' => 'hubdescription'));
        $hubdescription = html_writer::tag('div', $hubdescription, array('class' => 'hubinfo'));

        return $hubdescription;
    }


}
