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

defined('MOODLE_INTERNAL') || die;

$string['addscreenshots'] = 'Bildschirmfotos hinzufügen';
$string['advertise'] = 'Diesen Kurs für andere zur Einschreibung freigeben.';
$string['advertised'] = 'Geteilt';
$string['advertiseon'] = 'Diesen Kurs auf {$a} registrieren';
$string['advertiseonhub'] = 'Teile diesen Kurs, damit andere daran teilnehmen können';
$string['advertisepublication_help'] = 'Teilen Sie diesen Kurs auf teachSHARE, damit andere diesen Kurs finden und sich einschreiben können.';
$string['Publikum'] = 'Publikum';
$string['audience_help'] = 'Wählen Sie die Zielgruppe für den Kurs aus.';
$string['audienceadmins'] = 'Moodle-Administratoren';
$string['audienceeducators'] = 'Pädagogen';
$string['audiencestudents'] = 'Studenten';
$string['badgesnumber'] = 'Anzahl der Badges ({$a})';
$string['badurlformat'] = 'Schlechtes URL-Format';
$string['contributornames'] = 'Andere Mitwirkende';
$string['contributornames_help'] = 'Sie können dieses Feld verwenden, um die Namen anderer Personen aufzulisten, die zu diesem Kurs beigetragen haben';
$string['coursename'] = 'Name';
$string['coursepublished'] = 'Dieser Kurs wurde erfolgreich veröffentlicht.';
$string['courseshortname'] = 'Kurzname';
$string['courseshortname_help'] = 'Geben Sie einen Kurznamen für Ihren Kurs ein. Er muss nicht eindeutig sein.';
$string['coursesnumber'] = 'Anzahl der Kurse ({$a})';
$string['courseunpublished'] = 'Der Kurs {$a->courseshortname} ist nicht mehr auf {$a->hubname} veröffentlicht.';
$string['courseurl'] = 'Kurs-URL';
$string['courseurl_help'] = 'Dies ist die URL Ihres Kurses. Diese URL wird als Link in einem Suchergebnis angezeigt.';
$string['creatorname'] = 'Ersteller';
$string['creatorname_help'] = 'Der Creator ist der Ersteller des Kurses.';
$string['creatornotes'] = 'Anmerkungen des Erstellers';
$string['creatornotes_help'] = 'Die Erstellerhinweise sind ein Leitfaden für Lehrer zur Verwendung des Kurses.';
$string['customhub:publishcourse'] = 'Kurse auf benutzerdefinierten Hubs veröffentlichen';
$string['deletescreenshots'] = 'Diese Bildschirmfotos löschen';
$string['deletescreenshots_help'] = 'Alle aktuell hochgeladenen Screenshots löschen.';
$string['demourl'] = 'Demo URL';
$string['demourl_help'] = 'Geben Sie die Demo-URL Ihres Kurses ein. Standardmäßig ist dies die URL Ihres Kurses. Die Demo-URL wird als Link in einem Suchergebnis angezeigt.';
$string['description'] = 'Beschreibung';
$string['description_help'] = 'Dieser Beschreibungstext wird in der Kursliste im Hub angezeigt.';
$string['detectednotexistingpublication'] = '{$a->hubname} listet einen Kurs auf, der nicht mehr existiert. Informieren Sie den Hub-Administrator, dass die Publikationsnummer {$a->id} entfernt werden sollte.';
$string['educationallevel'] = 'Bildungsniveau';
$string['educationallevel_help'] = 'Wählen Sie das am besten geeignete Bildungsniveau, in das der Kurs passt.';
$string['edulevel'] = 'Beliebig';
$string['edulevelassociation'] = 'Vereinigung';
$string['edulevelcorporate'] = 'Unternehmen';
$string['edulevelgovernment'] = 'Regierung';
$string['edulevelother'] = 'Sonstige';
$string['edulevelprimary'] = 'Primär';
$string['edulevelsekundär'] = 'Sekundär';
$string['eduleveltertiary'] = 'Tertiär';
$string['errorcourseinfo'] = 'Beim Abrufen von Kurs-Metadaten aus dem Hub ({$a}) ist ein Fehler aufgetreten. Bitte versuchen Sie erneut, die Kurs-Metadaten aus dem Hub abzurufen, indem Sie diese Seite später erneut laden. Andernfalls können Sie den Registrierungsprozess mit den folgenden Standard-Metadaten fortsetzen. ';
$string['errorcoursepublish'] = 'Bei der Veröffentlichung des Kurses ({$a}) ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.';
$string['errorcoursewronglypublished'] = 'Ein Veröffentlichungsfehler wurde vom Hub zurückgegeben. Bitte versuchen Sie es später noch einmal.';
$string['errorcron'] = 'Bei der Aktualisierung der Registrierung auf "{$a->hubname}" ist ein Fehler aufgetreten. ({$a->errormessage})';
$string['errorcronnoxmlrpc'] = 'XML-RPC muss aktiviert sein, um die Registrierung zu aktualisieren.';
$string['errorregistration'] = 'Bei der Registrierung ist ein Fehler aufgetreten, bitte versuchen Sie es später noch einmal. ({$a})';
$string['errorunpublishcourses'] = 'Aufgrund eines unerwarteten Fehlers konnten die Kurse im Hub nicht gelöscht werden. Versuchen Sie es später noch einmal (empfohlen) oder kontaktieren Sie den Hub-Administrator.';
$string['existingscreenshotnumber'] = '{$a} existierende Bildschirmfotos. Sie können diese Screenshots auf dieser Seite erst sehen, wenn der Hub-Administrator Ihren Kurs freigibt.';
$string['existingscreenshots'] = 'Vorhandene Screenshots';
$string['forceunregister'] = 'Ja, Registrierungsdaten löschen';
$string['forceunregisterconfirmation'] = 'Ihre Website kann {$a} nicht erreichen. Dieser Hub könnte vorübergehend außer Betrieb sein. Wenn Sie nicht sicher sind, dass Sie die Registrierung weiterhin lokal entfernen wollen, brechen Sie bitte ab und versuchen Sie es später erneut.';
$string['hub'] = 'Hub';
$string['hubs'] = 'Hubs';
$string['issuedbadgesnumber'] = 'Anzahl der ausgestellten Badges ({$a})';
$string['language'] = 'Sprache';
$string['language_help'] = 'Die Hauptsprache dieses Kurses.';
$string['lasttimechecked'] = 'Zuletzt geprüft';
$string['licence'] = 'Lizenz';
$string['licence_help'] = 'Wählen Sie die Lizenz, unter der Sie Ihren Kurs vertreiben möchten.';
$string['licence_link'] = 'Lizenzen';
$string['modulenumberaverage'] = 'Durchschnittliche Anzahl der Kursmodule ({$a})';
$string['moodlenetnotsupported'] = 'Die Registrierung auf moodle.net wird von diesem Tool nicht unterstützt.';
$string['mustselectsubject'] = 'Sie müssen ein Fach auswählen';
$string['name'] = 'Name';
$string['name_help'] = 'Dieser Name wird in der Kursliste angezeigt.';
$string['neverchecked'] = 'Niemals geprüft';
$string['nocheckstatusfromunreghub'] = 'Die Seite ist nicht im Hub registriert, daher kann der Status nicht überprüft werden.';
$string['notregisteredonhub'] = 'Ihr Administrator muss diese Site bei mindestens einem Hub registrieren, bevor Sie einen Kurs veröffentlichen können. Wenden Sie sich an Ihren Website-Administrator.';
$string['operation'] = 'Aktionen';
$string['participantnumberaverage'] = 'Durchschnittliche Anzahl der Teilnehmer ({$a})';
$string['pluginname'] = 'Hub-Registrierung';
$string['postaladdress'] = 'Postanschrift';
$string['postaladdress_help'] = 'Postanschrift dieser Site oder der durch diese Site vertretenen Einrichtung';
$string['postsnumber'] = 'Anzahl der Beiträge ({$a})';
$string['previousregistrationdeleted'] = 'Die vorherige Registrierung wurde von {$a} gelöscht. Sie können den Registrierungsprozess erneut starten. Vielen Dank.';
$string['privacy'] = 'Datenschutz';
$string['privacy_help'] = 'Es kann sein, dass der Hub eine Liste der registrierten Sites anzeigen möchte. Wenn dies der Fall ist, können Sie wählen, ob Sie in dieser Liste erscheinen wollen oder nicht.';
$string['privatehuburl'] = 'Private Hub-URL';
$string['publicationinfo'] = 'Informationen zur Kursveröffentlichung';
$string['publishcourse'] = 'Auf teachSHARE veröffentlichen';
$string['publishcourseon'] = 'Veröffentlichen am {$a}';
$string['publishedon'] = 'Veröffentlicht am';
$string['publisheremail'] = 'Herausgeber-E-Mail';
$string['publisheremail_help'] = 'Die Herausgeber-E-Mail-Adresse ermöglicht es dem Hub-Administrator, den Herausgeber über Änderungen am Status des veröffentlichten Kurses zu informieren.';
$string['publishername'] = 'Herausgeber';
$string['publishername_help'] = 'Der Herausgeber ist die Person oder Organisation, die der offizielle Herausgeber des Kurses ist.  Wenn Sie den Kurs nicht im Auftrag einer anderen Person herausgeben, sind das normalerweise Sie selbst.';
$string['publishon'] = 'Teilen am';
$string['questionsnumber'] = 'Anzahl der Fragen ({$a})';
$string['readvertiseon'] = 'Werbeinformationen aktualisieren auf {$a}';
$string['registeredon'] = 'Wo Ihre Website registriert ist';
$string['registersite'] = 'Registrieren Sie sich bei {$a}';
$string['registerwith'] = 'Registrieren Sie sich mit einem Hub';
$string['registrationconfirmed'] = 'Registrierung der Website bestätigt';
$string['registrationconfirmedon'] = 'Vielen Dank, dass Sie Ihre Website registriert haben. Die Registrierungsinformationen werden durch die geplante Aufgabe \'Update registration on hubs\' auf dem neuesten Stand gehalten.';
$string['registrationinfo'] = 'Registrierungsinformationen';
$string['removefromhub'] = 'Aus dem Hub entfernen';
$string['renewregistration'] = 'Registrierung erneuern';
$string['resourcesnumber'] = 'Anzahl der Ressourcen ({$a})';
$string['restartregistration'] = 'Registrierung neu starten';
$string['roleassignmentsnumber'] = 'Anzahl der Rollenzuweisungen ({$a})';
$string['screenshots'] = 'Bildschirmfotos';
$string['screenshots_help'] = 'Alle Bildschirmfotos des Kurses werden in den Suchergebnissen angezeigt.';
$string['selecthub'] = 'Hub auswählen';
$string['selecthubinfo'] = 'Ein Community-Hub ist ein Server, der Kurse auflistet. Sie können Ihre Kurse nur auf Hubs freigeben, bei denen diese Moodle-Site registriert ist.  Wenn der von Ihnen gewünschte Hub hier nicht aufgeführt ist, wenden Sie sich bitte an den Administrator Ihrer Website.';
$string['sendfollowinginfo'] = 'Weitere Informationen';
$string['sendfollowinginfo_help'] = 'Die folgenden Informationen werden nur als Beitrag zur Gesamtstatistik gesendet.  Sie werden nicht auf einer Website veröffentlicht.';
$string['sendingcourse'] = 'Kurs senden';
$string['sendingize'] = 'Bitte warten, die Kursdatei wird hochgeladen ({$a->total}Mb)...';
$string['sent'] = '...finished';
$string['share'] = 'Diesen Kurs für andere zum Download freigeben';
$string['shared'] = 'Geteilt';
$string['shareon'] = 'Diesen Kurs auf {$a} hochladen';
$string['shareonhub'] = 'Diesen Kurs auf einen Hub hochladen';
$string['sharepublication_help'] = 'Diesen Kurs auf einen Community-Hub-Server hochladen, damit andere ihn herunterladen und auf ihren eigenen Moodle-Seiten installieren können';
$string['siteadmin'] = 'Administrator';
$string['siteadmin_help'] = 'Der vollständige Name des Website-Administrators.';
$string['sitecountry'] = 'Land';
$string['sitecountry_help'] = 'Das Land, in dem sich Ihre Organisation befindet.';
$string['sitedesc'] = 'Beschreibung';
$string['sitedesc_help'] = 'Diese Beschreibung Ihrer Website kann in der Website-Liste angezeigt werden.  Bitte nur einfachen Text verwenden.';
$string['siteemail'] = 'E-Mail Adresse';
$string['siteemail_help'] = 'Sie müssen eine E-Mail-Adresse angeben, damit der Hub-Administrator Sie bei Bedarf kontaktieren kann.  Diese Adresse wird nicht für andere Zwecke verwendet. Es wird empfohlen, eine E-Mail-Adresse anzugeben, die sich auf eine Position bezieht (Beispiel: sitemanager@example.com) und nicht direkt auf eine Person.';
$string['sitegeolocation'] = 'Geolokalisierung';
$string['sitegeolocation_help'] = 'In Zukunft werden wir möglicherweise eine standortbezogene Suche in den Hubs anbieten. Wenn Sie den Standort Ihres Standorts angeben möchten, geben Sie hier einen Breitengrad/Längengrad an (z. B. -31.947884,115.871285).  Eine Möglichkeit, dies herauszufinden, ist die Verwendung von Google Maps.';
$string['sitelang'] = 'Sprache';
$string['sitelang_help'] = 'Die Sprache Ihrer Website wird in der Website-Liste angezeigt.';
$string['sitename'] = 'Name';
$string['sitename_help'] = 'Der Name der Site wird in der Site-Liste angezeigt, wenn der Hub dies zulässt.';
$string['sitephone'] = 'Telefon';
$string['sitephone_help'] = 'Ihre Telefonnummer wird nur vom Hub-Administrator gesehen.';
$string['siteprivacy'] = 'Datenschutz';
$string['siteprivacylinked'] = 'Veröffentliche den Site-Namen mit einem Link';
$string['siteprivacynotpublished'] = 'Bitte veröffentlichen Sie diese Seite nicht';
$string['siteprivacypublished'] = 'Nur den Seitennamen veröffentlichen';
$string['siteregistrationcontact'] = 'Kontakt-Formular';
$string['siteregistrationcontact_help'] = 'Wenn Sie es zulassen, können andere Personen Sie über ein Kontaktformular im Hub kontaktieren.  Sie werden niemals deine E-Mail-Adresse sehen können.';
$string['siteregistrationemail'] = 'E-Mail-Benachrichtigungen';
$string['siteregistrationemail_help'] = 'Wenn Sie dies aktivieren, kann der Hub-Administrator Sie per E-Mail über wichtige Neuigkeiten wie Sicherheitsprobleme informieren.';
$string['siteregistrationupdated'] = 'Website-Registrierung aktualisiert';
$string['siterelease'] = 'Moodle-Version';
$string['siterelease_help'] = 'Moodle-Versionsnummer dieser Website.';
$string['siteupdatedcron'] = 'Website-Registrierung aktualisiert am "{$a}"';
$string['siteurl'] = 'Website-URL';
$string['siteurl_help'] = 'Die URL ist die Adresse dieser Website.  Wenn die Privatsphäre-Einstellungen es erlauben, Site-Adressen zu sehen, ist dies die URL, die verwendet wird.';
$string['siteversion'] = 'Moodle-Version';
$string['siteversion_help'] = 'Die Moodle-Version dieser Website.';
$string['status'] = 'Hub-Auflistung';
$string['statuspublished'] = 'Aufgelistet';
$string['statusunpublished'] = 'Nicht gelistet';
$string['subject'] = 'Betreff';
$string['subject_help'] = 'Wählen Sie das Hauptfachgebiet, das der Kurs abdeckt.';
$string['tags'] = 'Tags';
$string['tags_help'] = 'Tags helfen dabei, Ihren Kurs weiter zu kategorisieren und ihn besser zu finden. Bitte verwenden Sie einfache, aussagekräftige Wörter und trennen Sie diese mit einem Komma. Beispiel: Mathe, Algebra, Geometrie';
$string['taskregistrationcron'] = 'Registrierung auf Hubs aktualisieren';
$string['type'] = 'Angekündigt / Geteilt';
$string['unpublish'] = 'Unpublish';
$string['unpublishalladvertisedcourses'] = 'Alle Kurse entfernen, die derzeit auf einem Hub beworben werden';
$string['unpublishalluploadedcourses'] = 'Entfernt alle Kurse, die auf einen Hub hochgeladen wurden';
$string['unpublishconfirmation'] = 'Wollen Sie wirklich den Kurs "{$a->Kursortname}" aus dem Hub "{$a->Hubname}" entfernen';
$string['unpublishcourse'] = 'Unpublish {$a}';
$string['unregister'] = 'Abmelden';
$string['unregisterfrom'] = 'Abmelden von {$a}';
$string['unregistrationerror'] = 'Ein Fehler ist aufgetreten, als die Site versucht hat, die Registrierung vom Hub aufzuheben: {$a}';
$string['update'] = 'Aktualisieren';
$string['updatesite'] = 'Registrierung aktualisieren auf {$a}';
$string['updatestatus'] = 'Jetzt prüfen.';
$string['urlalreadyregistered'] = 'Ihre Seite scheint bereits auf diesem Hub registriert zu sein, was bedeutet, dass etwas schief gelaufen ist. Bitte kontaktieren Sie den Hub-Administrator, um Ihre Registrierung zurückzusetzen, damit Sie es erneut versuchen können.';
$string['usersnumber'] = 'Anzahl der Benutzer ({$a})';
$string['warning'] = 'WARNUNG';
$string['wrongtoken'] = 'Die Registrierung ist aus einem unbekannten Grund (Netzwerk?) fehlgeschlagen. Bitte versuchen Sie es erneut.';
$string['xmlrpcdisabledpublish'] = 'Die XML-RPC-Erweiterung ist auf dem Server nicht aktiviert. Sie können keine Kurse veröffentlichen oder veröffentlichte Kurse verwalten.';
$string['xmlrpcdisabledregistration'] = 'Die XML-RPC-Erweiterung ist auf dem Server nicht aktiviert. Sie können sich nicht abmelden oder Ihre Registrierung aktualisieren, bis Sie sie aktivieren.';
$string['eventname_backup_sending_success'] = 'Die Sicherungsdatei wurde erfolgreich gesendet.';
$string['eventname_backup_sending_failed'] = 'Das Senden der Sicherungsdatei ist fehlgeschlagen.';
$string['eventname_course_registration_finished'] = 'Der Kurs wurde am Hub registriert.';
