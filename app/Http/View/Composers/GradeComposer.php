<?php

namespace App\Http\View\Composers;

use App\Models\Grade;
use Illuminate\View\View;

class GradeComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('grades', Grade::all());
    }
}
