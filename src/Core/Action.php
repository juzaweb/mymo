<?php
/**
 * MYMO CMS - The Best Laravel CMS
 *
 * @package    mymocms/mymocms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/mymocms/mymocms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 6/14/2021
 * Time: 9:52 AM
 */

namespace Mymo\Core;


class Action
{
    const THEME_HEADER = 'theme_header';
    const THEME_FOOTER = 'theme_footer';

    const BACKEND_DASHBOARD_VIEW = 'backend.dashboard.view';

    const BACKEND_CALLACTION = 'backend.call_action';
    const FRONTEND_CALLACTION = 'frontend.call_action';
}