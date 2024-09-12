<?php

namespace Leantime\Domain\Timesheets\Controllers;

use Illuminate\Http\RedirectResponse;
use Leantime\Core\Controller\Controller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Domain\Timesheets\Repositories\Timesheets as TimesheetRepository;
use Symfony\Component\HttpFoundation\Response;

class DelTime extends Controller
{
    private TimesheetRepository $timesheetsRepo;

    /**
     * init - initialize private variable
     */
    public function init(TimesheetRepository $timesheetsRepo): void
    {
        $this->timesheetsRepo = $timesheetsRepo;
    }

    /**
     * run - display template and edit data
     */
    public function run(): Response|RedirectResponse
    {
        Auth::authOrRedirect([Roles::$owner, Roles::$admin, Roles::$manager, Roles::$editor], true);

        if (isset($_GET['id']) === true) {
            $id = (int) ($_GET['id']);

            if (isset($_POST['del']) === true) {
                $this->timesheetsRepo->deleteTime($id);

                $this->tpl->setNotification('notifications.time_deleted_successfully', 'success');

                $this->tpl->closeModal();
                $this->tpl->htmxRefresh();

                return $this->tpl->emptyResponse();
            }

            $this->tpl->assign('id', $id);

            return $this->tpl->displayPartial('timesheets::partials.delTime');
        } else {
            return $this->tpl->displayPartial('errors.error403');
        }
    }
}
