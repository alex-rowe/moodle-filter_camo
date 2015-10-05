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
 * Filter converting URLs in the text to HTML links
 *
 * @package    filter
 * @subpackage camo
 * @copyright  2015 Alex Rowe <arowe@studygroup.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_camo extends moodle_text_filter {
    public function filter($text, array $options = array()) {
        global $CFG;

        if (!is_string($text) or empty($text)) {
            // Non string data can not be filtered anyway.
            return $text;
        }

        if (stripos($text, '<img') === false) {
            // Performance shortcut - if no <img> tag, nothing can match.
            return $text;
        }

        $host = get_config('filter_camo', 'host');
        $key  = get_config('filter_camo', 'key');
        $site = $CFG->wwwroot;

        $newtext = $text;

        $pattern = "#<img.*?src=[\"'](http://[^\"]+)[\"'].*?/?>#i";

        if (preg_match_all($pattern, $newtext, $matches)) {
            foreach ($matches[1] as $url) {
                // Don't rewrite requests for this site.
                if (stripos($url, $site) === false) {
                    $digest = hash_hmac('sha1', $url, $key);
                    $newtext = str_replace($url, $host . '/' . $digest . '/' . bin2hex($url), $newtext);
                }
            }
        }

        if (empty($newtext) or $newtext === $text) {
            // Error or not filtered.
            return $text;
        }

        return $newtext;
    }

}