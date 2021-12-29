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
 * Backup helper class
 *
 * @package    tool_customhub
 * @copyright  2021, ISB Bayern
 * @author     Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace tool_customhub\local;
defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * Backup helper class
 *
 * @package    tool_customhub
 * @copyright  2021, ISB Bayern
 * @author     Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
class backup_helper {

    /**
     * @var object Backup tempdirectory.
     */
    const BACKUPDIR = "hub_temp_backups";

    /**
     * @var object Backup file path.
     */
    private $backupdir;

    /**
     * Creates a Course Backup if necessary.
     * @param int $courseid
     * @return string backupfilepath
     */
    public static function create_backup($courseid, $withuserdata = false, $anonymised = true) {
        global $CFG;

        require_once($CFG->dirroot . '/backup/controller/backup_controller.class.php');

        $admin = get_admin();

        $bc = new \backup_controller(
            \backup::TYPE_1COURSE,
            $courseid,
            \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_YES,
            \backup::MODE_GENERAL,
            $admin->id
        );
        // Set the default filename.
        $format = $bc->get_format();
        $type = $bc->get_type();
        $id = $bc->get_id();
        $filename = \backup_plan_dbops::get_default_backup_filename($format, $type, $id, $withuserdata, $anonymised);
        $bc->get_plan()->get_setting('filename')->set_value($filename);

        // Execution.
        $bc->finish_ui();
        $bc->execute_plan();
        $results = $bc->get_results();
        $bc->destroy();
        $file = $results['backup_destination']; // May be empty if file already moved to target location.

        $dir = rtrim($CFG->backuptempdir . "/" . self::BACKUPDIR);
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($CFG->backuptempdir . "/" . self::BACKUPDIR);
        }
        if (!is_writable($dir)) {
            mtrace("Backup destination directory ist not writable.");
            die;
        }

        if ($file) {
            mtrace("Writing " . $dir . '/' . $filename);
            $backupdir = $dir . '/' . $filename;
            if ($file->copy_content_to($backupdir)) {
                $file->delete();
                mtrace("Backup completed.");
            } else {
                mtrace("Backup destination directory does not exist or is not writable.
Leaving the backup in the course backup file area.");
            }
        }
        return [$filename, $backupdir];
    }
}
