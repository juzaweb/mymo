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
 * Date: 6/13/2021
 * Time: 11:09 AM
 */

namespace Mymo\Backend\Http\Controllers\Backend;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Mymo\Backend\Http\Controllers\BackendController;
use Mymo\Updater\UpdaterManager;

class UpdateController extends BackendController
{
    protected $updater;

    public function __construct(UpdaterManager $updater)
    {
        $this->updater = $updater;
    }

    public function index()
    {
        return view('mymo::backend.update', [
            'title' => trans('mymo::app.updates'),
            'updater' => $this->updater
        ]);
    }

    public function update()
    {
        if (!$this->updater->source()->isNewVersionAvailable()) {
            return $this->error([
                'message' => trans('mymo::app.no_new_version_available'),
            ]);
        }

        Artisan::call('down');
        DB::beginTransaction();
        try {
            $versionAvailable = $this->updater->source()->getVersionAvailable();
            $release = $this->updater->source()->fetch($versionAvailable);
            $this->updater->source()->update($release);
            Artisan::call('migrate', ['--force'=> true]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error([
                'message' => $e->getMessage(),
            ]);
        }
        Artisan::call('up');

        return $this->success([
            'message' => trans('mymo::app.updated_successfully'),
        ]);
    }
}