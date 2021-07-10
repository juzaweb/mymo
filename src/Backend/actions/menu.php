<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    mymocms/mymocms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/mymocms/mymocms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/26/2021
 * Time: 9:18 PM
*/

use Mymo\Core\Facades\HookAction;

HookAction::addAdminMenu(
    trans('mymo::app.dashboard'),
    'dashboard',
    [
        'icon' => 'fa fa-dashboard',
        'position' => 1
    ]
);

/*HookAction::addAdminMenu(
    'mymo::app.dashboard',
    'dashboard',
    [
        'icon' => 'fa fa-dashboard',
        'position' => 1,
        'parent' => 'dashboard',
    ]
);

HookAction::addAdminMenu(
    'mymo::app.updates',
    'updates',
    [
        'icon' => 'fa fa-refresh',
        'position' => 2,
        'parent' => 'dashboard',
    ]
);
*/

/*HookAction::addAdminMenu(
    trans('mymo::app.media'),
    'media',
    [
        'icon' => 'fa fa-image',
        'position' => 10
    ]
);*/

HookAction::addAdminMenu(
    trans('mymo::app.users'),
    'users',
    [
        'icon' => 'fa fa-users',
        'position' => 60
    ]
);

HookAction::addAdminMenu(
    trans('mymo::app.setting'),
    'setting',
    [
        'icon' => 'fa fa-cogs',
        'position' => 70
    ]
);

HookAction::addAdminMenu(
    trans('mymo::app.general_setting'),
    'setting.system',
    [
        'icon' => 'fa fa-cogs',
        'position' => 1,
        'parent' => 'setting',
    ]
);

/*HookAction::addAdminMenu(
    trans('mymo::app.translations'),
    'setting.language',
    [
        'icon' => 'fa fa-language',
        'position' => 5,
        'parent' => 'setting',
    ]
);*/






