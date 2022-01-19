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
 * Class registration_manager.
 *
 * @package    tool_customhub
 * @copyright  2017 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_customhub;

use stdClass;
use Exception;
use moodle_exception;
use webservice_xmlrpc_client;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

/**
 *
 * Site registration library
 *
 * @package    tool_customhub
 * @copyright  2010 Jerome Mouneyrac <jerome@mouneyrac.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class registration_manager {

    /**
     * Automatically update the registration on all hubs
     */
    public function cron() {
        global $CFG;
        if (!$hubs = $this->get_registered_on_hubs()) {
            return;
        }

        if (!extension_loaded('xmlrpc')) {
            mtrace(get_string('errorcronnoxmlrpc', 'tool_customhub'));
        }

        $function = 'hub_update_site_info';
        require_once($CFG->dirroot . "/webservice/xmlrpc/lib.php");

        // Update all hubs where the site is registered.
        foreach ($hubs as $hub) {
            // Update the registration.
            $siteinfo = $this->get_site_info($hub->huburl);
            $params = array('siteinfo' => $siteinfo);
            $serverurl = $hub->huburl . "/local/hub/webservice/webservices.php";
            $xmlrpcclient = new webservice_xmlrpc_client($serverurl, $hub->token);
            try {
                $result = $xmlrpcclient->call($function, $params);
                $this->update_registeredhub($hub); // To update timemodified.
                mtrace(get_string('siteupdatedcron', 'tool_customhub', $hub->hubname));
            } catch (Exception $e) {
                $errorparam = new stdClass();
                $errorparam->errormessage = $e->getMessage();
                $errorparam->hubname = $hub->hubname;
                mtrace(get_string('errorcron', 'tool_customhub', $errorparam));
            }
        }
    }

    /**
     * Return the site secret for a given hub
     * site identifier is assigned to Mooch
     * each hub has a unique and personal site secret.
     * @param string $huburl
     * @return string site secret
     */
    public function get_site_secret_for_hub($huburl) {
        global $DB;

        $this->is_moodlenet($huburl, true);

        $existingregistration = $DB->get_record('registration_hubs', array('huburl' => $huburl));

        if (!empty($existingregistration)) {
            return $existingregistration->secret;
        }

        $siteidentifier = random_string(32) . $_SERVER['HTTP_HOST'];
        return $siteidentifier;

    }

    /**
     * When the site register on a hub, he must call this function
     * @param object $hub where the site is registered on
     * @return integer id of the record
     */
    public function add_registeredhub($hub) {
        global $DB;
        $this->is_moodlenet($hub->huburl, true);
        $hub->timemodified = time();
        $id = $DB->insert_record('registration_hubs', $hub);
        return $id;
    }

    /**
     * When a site unregister from a hub, he must call this function
     * @param string $huburl the huburl to delete
     */
    public function delete_registeredhub($huburl) {
        global $DB;
        $this->is_moodlenet($huburl, true);
        $DB->delete_records('registration_hubs', array('huburl' => $huburl));
    }

    /**
     * Get a hub on which the site is registered for a given url
     * Mostly use to check if the site is registered on a specific hub
     * @param string $huburl
     * @param bool $allowmoodlenet
     * @return stdClass object the  hub
     */
    public function get_registeredhub($huburl, $allowmoodlenet = false) {
        global $DB;
        return $DB->get_record('registration_hubs', ['huburl' => $huburl, 'confirmed' => 1]);
    }

    /**
     * Get the hub which has not confirmed that the site is registered on,
     * but for which a request has been sent
     * @param string $huburl
     * @return stdClass object the  hub
     */
    public function get_unconfirmedhub($huburl) {
        global $DB;
        return $DB->get_record('registration_hubs', ['huburl' => $huburl, 'confirmed' => 0]);
    }

    /**
     * Update a registered hub (mostly use to update the confirmation status)
     * @param object $hub the hub
     */
    public function update_registeredhub($hub) {
        global $DB;
        $hub->timemodified = time();
        $DB->update_record('registration_hubs', $hub);
    }

    /**
     * Return all hubs where the site is registered
     */
    public function get_registered_on_hubs() {
        global $DB;
        $hubs = $DB->get_records('registration_hubs', array('confirmed' => 1));
        foreach ($hubs as $id => $hub) {
            if ($this->is_moodlenet($hub->huburl)) {
                unset($hubs[$id]);
            }
        }
        return $hubs;
    }

    /**
     * Return site information for a specific hub
     * @param string $huburl
     * @return array site info
     */
    public function get_site_info($huburl) {
        global $CFG, $DB;

        $this->is_moodlenet($huburl, true);

        $siteinfo = array();
        $cleanhuburl = clean_param($huburl, PARAM_ALPHANUMEXT);
        $siteinfo['name'] = get_config('hub', 'site_name_' . $cleanhuburl);
        $siteinfo['description'] = get_config('hub', 'site_description_' . $cleanhuburl);
        $siteinfo['contactname'] = get_config('hub', 'site_contactname_' . $cleanhuburl);
        $siteinfo['contactemail'] = get_config('hub', 'site_contactemail_' . $cleanhuburl);
        $siteinfo['contactphone'] = get_config('hub', 'site_contactphone_' . $cleanhuburl);
        $siteinfo['imageurl'] = get_config('hub', 'site_imageurl_' . $cleanhuburl);
        $siteinfo['privacy'] = get_config('hub', 'site_privacy_' . $cleanhuburl);
        $siteinfo['street'] = get_config('hub', 'site_address_' . $cleanhuburl);
        $siteinfo['regioncode'] = get_config('hub', 'site_region_' . $cleanhuburl);
        $siteinfo['countrycode'] = get_config('hub', 'site_country_' . $cleanhuburl);
        $siteinfo['geolocation'] = get_config('hub', 'site_geolocation_' . $cleanhuburl);
        $siteinfo['contactable'] = get_config('hub', 'site_contactable_' . $cleanhuburl);
        $siteinfo['emailalert'] = get_config('hub', 'site_emailalert_' . $cleanhuburl);
        if (get_config('hub', 'site_coursesnumber_' . $cleanhuburl) == -1) {
            $coursecount = -1;
        } else {
            $coursecount = $DB->count_records('course') - 1;
        }
        $siteinfo['courses'] = $coursecount;
        if (get_config('hub', 'site_usersnumber_' . $cleanhuburl) == -1) {
            $usercount = -1;
        } else {
            $usercount = $DB->count_records('user', array('deleted' => 0));
        }
        $siteinfo['users'] = $usercount;

        if (get_config('hub', 'site_roleassignmentsnumber_' . $cleanhuburl) == -1) {
            $roleassigncount = -1;
        } else {
            $roleassigncount = $DB->count_records('role_assignments');
        }
        $siteinfo['enrolments'] = $roleassigncount;
        if (get_config('hub', 'site_postsnumber_' . $cleanhuburl) == -1) {
            $postcount = -1;
        } else {
            $postcount = $DB->count_records('forum_posts');
        }
        $siteinfo['posts'] = $postcount;
        if (get_config('hub', 'site_questionsnumber_' . $cleanhuburl) == -1) {
            $questioncount = -1;
        } else {
            $questioncount = $DB->count_records('question');
        }
        $siteinfo['questions'] = $questioncount;
        if (get_config('hub', 'site_resourcesnumber_' . $cleanhuburl) == -1) {
            $resourcecount = -1;
        } else {
            $resourcecount = $DB->count_records('resource');
        }
        $siteinfo['resources'] = $resourcecount;
        // Badge statistics.
        require_once($CFG->libdir . '/badgeslib.php');
        if (get_config('hub', 'site_badges_' . $cleanhuburl) == -1) {
            $badges = -1;
        } else {
            $badges = $DB->count_records_select('badge', 'status <> ' . BADGE_STATUS_ARCHIVED);
        }
        $siteinfo['badges'] = $badges;
        if (get_config('hub', 'site_issuedbadges_' . $cleanhuburl) == -1) {
            $issuedbadges = -1;
        } else {
            $issuedbadges = $DB->count_records('badge_issued');
        }
        $siteinfo['issuedbadges'] = $issuedbadges;
        //TODO
        require_once($CFG->dirroot . "/course/lib.php");
        if (get_config('hub', 'site_participantnumberaverage_' . $cleanhuburl) == -1) {
            $participantnumberaverage = -1;
        } else {
            $participantnumberaverage = average_number_of_participants();
        }
        $siteinfo['participantnumberaverage'] = $participantnumberaverage;
        if (get_config('hub', 'site_modulenumberaverage_' . $cleanhuburl) == -1) {
            $modulenumberaverage = -1;
        } else {
            $modulenumberaverage = average_number_of_courses_modules();
        }
        $siteinfo['modulenumberaverage'] = $modulenumberaverage;
        $siteinfo['language'] = get_config('hub', 'site_language_' . $cleanhuburl);
        $siteinfo['moodleversion'] = $CFG->version;
        $siteinfo['moodlerelease'] = $CFG->release;
        $siteinfo['url'] = $CFG->wwwroot;

        return $siteinfo;
    }

    /**
     * Retrieve the site privacy string matching the define value
     * @param string $privacy must match the define into moodlelib.php
     * @return string
     */
    public function get_site_privacy_string($privacy) {
        switch ($privacy) {
            case \core\hub\registration::HUB_SITENOTPUBLISHED:
                $privacystring = get_string('siteprivacynotpublished', 'tool_customhub');
                break;
            case \core\hub\registration::HUB_SITENAMEPUBLISHED:
                $privacystring = get_string('siteprivacypublished', 'tool_customhub');
                break;
            case \core\hub\registration::HUB_SITELINKPUBLISHED:
                $privacystring = get_string('siteprivacylinked', 'tool_customhub');
                break;
        }
        if (empty($privacystring)) {
            throw new moodle_exception('unknownprivacy');
        }
        return $privacystring;
    }

    /**
     * Checks if hub URL is moodle.net
     *
     * @param string $huburl
     * @param bool $showexception throw exception if huburl is moodle.net
     * @return bool
     */
    public function is_moodlenet($huburl, $showexception = false) {
        $hostname = parse_url(strtolower($huburl), PHP_URL_HOST);
        $ismoodlenet = ($hostname === 'moodle.net' || $hostname === 'hub.moodle.org');
        if ($ismoodlenet && $showexception) {
            throw new moodle_exception('moodlenetnotsupported', 'tool_customhub',
                new moodle_url('/admin/registration/index.php'));
        }
        return $ismoodlenet;
    }
}