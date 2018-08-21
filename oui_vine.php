<?php

/*
 * This file is part of oui_vine,
 * a oui_player v2+ extension to easily embed
 * Vine customizable video players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_vine
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * Vine
 *
 * @package Oui\Player
 */

namespace Oui;

if (class_exists('Oui\Player\Provider')) {

    class Vine extends Player\Embed
    {
        protected static $srcBase = '//vine.co/';
        protected static $srcGlue = array('v/', '/embed/', '?');
        protected static $script = 'https://platform.vine.co/static/scripts/embed.js';
        protected static $iniDims = array(
            'width'      => '600',
            'height'     => '600',
            'ratio'      => '',
            'responsive' => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
        );
        protected static $iniParams = array(
            'type' => array(
                'default' => 'simple',
                'valid'   => array('simple', 'postcard'),
            ),
            'audio' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
        );
        protected static $mediaPatterns = array(
            'scheme' => '#^https?://(www\.)?vine.co/(v/)?([^&?/]+)#i',
            'id'     => '3'
        );

        /**
         * {@inheritdoc}
         */

        public function getParams()
        {
            $params = array();

            foreach (self::getIniParams() as $param => $infos) {
                $pref = get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $value = isset($this->config[$param]) ? $this->config[$param] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($param === 'type') {
                    $params[] = $value ?: $pref;
                } elseif ($value === '' && $pref !== $default) {
                    $params[] = $param . '=' . $pref;
                } elseif ($value !== '') {
                    $params[] = $param . '=' . $value;
                }
            }

            return $params;
        }
    }
}
