<?php

namespace DagaSmart\CloudStorage\Http\Controllers;

use Dagasmart\BizAdmin\Controllers\AdminController;
use Dagasmart\BizAdmin\Renderers\Form;

class MultiCloudController extends AdminController
{
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }
}
