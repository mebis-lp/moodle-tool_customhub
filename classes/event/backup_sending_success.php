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
 * Class for event to log when a backupfile was send successfully.
 *
 * @package    tool_customhub
 * @since      Moodle 2.7
 * @copyright  2022 ISB Bayern
 * @author     Peter Mayer <peter.mayer@isb.bayern.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_customhub\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for event to log when a backupfile was send successfully.
 *
 * @package    tool_customhub
 * @since      Moodle 2.7
 * @copyright  2022 ISB Bayern
 * @author     Peter Mayer <peter.mayer@isb.bayern.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_sending_success extends \core\event\base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'r'; // Read.
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventname_backup_sending_success', 'tool_customhub');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The event 'tool_customhub/event/backup_sending_success' has been called.";
    }
}