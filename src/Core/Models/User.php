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
 * Date: 7/10/2021
 * Time: 3:13 PM
 */

namespace Mymo\Core\Models;

use App\User as BaseUser;
use Illuminate\Database\Eloquent\Builder;

class User extends BaseUser
{
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'status'
    ];

    public static function getAllStatus()
    {
        return [
            'active' => trans('mymo::app.active'),
            'unconfirmed' => trans('mymo::app.unconfimred'),
            'banned' => trans('mymo::app.banned'),
        ];
    }

    /**
     * @param Builder $builder
     * @return Builder
     * */
    public function scopeActive($builder)
    {
        return $builder->where('status', '=', 'active');
    }

    public function getAvatar() {
        if ($this->avatar) {
            return image_url($this->avatar);
        }

        return asset('mymo/styles/images/thumb-default.png');
    }
}