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
 * Strings for tool_customhub
 *
 * @package    tool_customhub
 * @copyright  2017 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Custom hub communication';
$string['publishcourse'] = 'Publish on hub';
$string['customhub:publishcourse'] = 'Publish courses on custom hubs';

$string['advertise'] = 'Advertise this course for people to join';
$string['advertisepublication_help'] = 'Advertising your course on a community hub server allows people to find this course and come here to enrol.';
$string['share'] = 'Share this course for people to download';
$string['sharepublication_help'] = 'Uploading this course to a community hub server will enable people to download it and install it on their own Moodle sites.';
$string['removefromhub'] = 'Remove from hub';
$string['taskregistrationcron'] = 'Update registration on custom hubs';
$string['xmlrpcdisabledregistration'] = 'The XML-RPC extension is not enabled on the server. You will not be able to unregister or update your registration until you enable it.';
$string['moodlenetnotsupported'] = 'Registration on moodle.net is not supported by this tool.';
$string['existingscreenshotnumber'] = '{$a} existing screenshots. You will be able to see these screenshots on this page, only once the hub administrator enables your course.';
$string['unpublishalladvertisedcourses'] = 'Remove all courses currently being advertised on a hub';
$string['unpublishalluploadedcourses'] = 'Removed all courses that were uploaded to a hub';
$string['forceunregisterconfirmation'] = 'Your site cannot reach {$a}. This hub could be temporarily down. Unless you are sure you want to continue to remove registration locally, please cancel and try again later.';
$string['warning'] = 'WARNING';
$string['forceunregister'] = 'Yes, clean registration data';
$string['unregistrationerror'] = 'An error occurred when the site tried to unregister from the hub: {$a}';
$string['unpublishconfirmation'] = 'Do you really want to remove the course "{$a->courseshortname}" from the hub "{$a->hubname}"';
$string['errorregistration'] = 'An error occurred during registration, please try again later. ({$a})';
$string['nocheckstatusfromunreghub'] = 'The site is not registered on the hub so the status can not be checked.';
$string['siteupdatedcron'] = 'Site registration updated on "{$a}"';
$string['errorcron'] = 'An error occurred during registration update on "{$a->hubname}" ({$a->errormessage})';
$string['errorcronnoxmlrpc'] = 'XML-RPC must be enabled in order to update the registration.';
$string['publishcourseon'] = 'Publish on {$a}';
$string['registrationconfirmed'] = 'Site registration confirmed';
$string['registrationconfirmedon'] = 'Thank you for registering your site. Registration information will be kept up to date by the \'Update registration on custom hubs\' scheduled task.';
$string['notregisteredonhub'] = 'Your administrator needs to register this site with at least one hub before you can publish a course. Contact your site administrator.';
$string['errorunpublishcourses'] = 'Due to an unexpected error, the courses could not be deleted on the hub. Try again later (recommended) or contact the hub administrator.';
$string['xmlrpcdisabledpublish'] = 'The XML-RPC extension is not enabled on the server. You can not publish courses or manage published courses.';
$string['detectednotexistingpublication'] = '{$a->hubname} is listing a course that does not exist any more. Alert this hub administrator that the publication number {$a->id} should be removed.';
$string['unregisterfrom'] = 'Unregister from {$a}';
$string['courseunpublished'] = 'The course {$a->courseshortname} is no longer published on {$a->hubname}.';
$string['errorcourseinfo'] = 'An error occurred when retrieving course metadata from the hub ({$a}). Please try again to retrieve the course metadata from the hub by reloading this page later. Otherwise you can decide to continue the registration process with the following default metadata. ';
$string['shareon'] = 'Upload this course to {$a}';
$string['advertiseon'] = 'Advertise this course on {$a}';
$string['readvertiseon'] = 'Update advertising information on {$a}';
$string['registrationinfo'] = 'Registration information';
$string['updatesite'] = 'Update registration on {$a}';
$string['registersite'] = 'Register with {$a}';
$string['unregister'] = 'Unregister';
$string['operation'] = 'Actions';
$string['publishcourseon'] = 'Publish on {$a}';
$string['publishedon'] = 'Published on';
$string['unpublishcourse'] = 'Unpublish {$a}';
$string['coursepublished'] = 'This course has been published successfully on \'{$a}\'.';
$string['siteregistrationupdated'] = 'Site registration updated';
$string['publicationinfo'] = 'Course publication information';
$string['unregister'] = 'Unregister';
$string['siteregistrationcontact'] = 'Contact form';
$string['siteregistrationcontact_help'] = 'If you allow it, other people may be able to contact you via a contact form on the hub.  They will never be able to see your email address.';
$string['siteregistrationemail'] = 'Email notifications';
$string['siteregistrationemail_help'] = 'If you enable this the hub administrator may email you to inform you of important news like security issues.';
$string['sendfollowinginfo'] = 'More information';
$string['sendfollowinginfo_help'] = 'The following information will be sent to contribute to overall statistics only.  It will not be made public on any site listing.';
$string['siteversion'] = 'Moodle version';
$string['siteversion_help'] = 'The Moodle version of this site.';
$string['siterelease'] = 'Moodle release';
$string['siterelease_help'] = 'Moodle release number of this site.';
$string['siteurl'] = 'Site URL';
$string['siteurl_help'] = 'The URL is the address of this site.  If privacy settings allow people to see site addresses then this is the URL that will be used.';
$string['sitename'] = 'Name';
$string['sitename_help'] = 'The name of the site will be shown on the site listing if the hub allows that.';
$string['sitedesc'] = 'Description';
$string['sitedesc_help'] = 'This description of your site may be shown in the site listing.  Please use plain text only.';
$string['sitelang'] = 'Language';
$string['sitelang_help'] = 'Your site language will be displayed on the site listing.';
$string['postaladdress'] = 'Postal address';
$string['postaladdress_help'] = 'Postal address of this site, or of the entity represented by this site.';
$string['sitecountry'] = 'Country';
$string['sitecountry_help'] = 'The country your organisation is in.';
$string['sitegeolocation'] = 'Geolocation';
$string['sitegeolocation_help'] = 'In future we may provide location-based searching in the hubs. If you want to specify the location for your site use a latitude/longitude value here (eg: -31.947884,115.871285).  One way to find this is to use Google Maps.';
$string['siteadmin'] = 'Administrator';
$string['siteadmin_help'] = 'The full name of the site administrator.';
$string['sitephone'] = 'Phone';
$string['sitephone_help'] = 'Your phone number will only be seen by the hub administrator.';
$string['siteemail'] = 'Email address';
$string['siteemail_help'] = 'You need to provide an email address so the hub administrator can contact you if necessary.  This will not be used for any other purpose. It is recommended to enter a email address related to a position (example: sitemanager@example.com) and not directly to a person.';
$string['statuspublished'] = 'Listed';
$string['statusunpublished'] = 'Not listed';
$string['updatestatus'] = 'Check it now.';
$string['lasttimechecked'] = 'Last time checked';
$string['neverchecked'] = 'Never checked';
$string['unpublish'] = 'Unpublish';
$string['advertised'] = 'Advertised';
$string['shared'] = 'Shared';
$string['sendingsize'] = 'Please wait the course file is uploading ({$a->total}Mb)...';
$string['sent'] = '...finished';
$string['operation'] = 'Actions';
$string['update'] = 'Update';
$string['type'] = 'Advertised / Shared';
$string['badurlformat'] = 'Bad URL format';
$string['siteprivacy'] = 'Privacy';
$string['siteprivacynotpublished'] = 'Please do not publish this site';
$string['siteprivacypublished'] = 'Publish the site name only';
$string['siteprivacylinked'] = 'Publish the site name with a link';
$string['sendingcourse'] = 'Sending course';
$string['status'] = 'Hub listing';
$string['statuspublished'] = 'Listed';
$string['statusunpublished'] = 'Not listed';

// Deprecated strings from core_admin:
$string['hubs'] = 'Hubs';
// Deprecated strings from core_hub:
$string['selecthub'] = 'Select hub';
$string['privatehuburl'] = 'Private hub URL';
$string['registerwith'] = 'Register with a hub';
$string['privacy'] = 'Privacy';
$string['privacy_help'] = 'The hub may want to display a list of registered sites. If it does then you can choose whether or not you want to appear on that list.';
$string['registeredon'] = 'Where your site is registered';
$string['hub'] = 'Hub';
$string['selecthubinfo'] = 'A community hub is a server that lists courses. You can only share your courses on hubs that this Moodle site is registered with.  If the hub you want is not listed below, please contact your site administrator.';
$string['advertiseonhub'] = 'Share this course for people to join';
$string['publishon'] = 'Share on';
$string['shareonhub'] = 'Upload this course to a hub';
$string['siteupdatedcron'] = 'Site registration updated on "{$a}"';
$string['errorcron'] = 'An error occurred during registration update on "{$a->hubname}" ({$a->errormessage})';
$string['errorcronnoxmlrpc'] = 'XML-RPC must be enabled in order to update the registration.';
