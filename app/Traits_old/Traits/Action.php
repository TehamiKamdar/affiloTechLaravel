<?php

namespace App\Traits;

use Illuminate\Support\Facades\Gate;

trait Action
{
    public function prepareAction($data)
    {
        $crudRoutePart = $data['crud_part'];
        $id = $data['id'];
        $viewGate = $data['view'] ?? null;
        $editGate = $data['edit'] ?? null;
        $deleteGate = $data['delete'] ?? null;
        $action = "<ul class='orderDatatable_actions mb-0 d-flex justify-content-around'>";

        // VIEW BUTTON
//        if(!empty($viewGate) && Gate::allowIf("$viewGate")) {
            // VIEW BUTTON
            $show =  '<li>
                                      <a class="view" href="'.route("$crudRoutePart.show", $id).'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                      </a>
                                  </li>';
            $action .= $show . " ";
//        }

        // EDIT BUTTON
        if(!empty($editGate) && Gate::allowIf("$editGate")) {// EDIT BUTTON
            $edit =  '<li>
                                      <a class="edit" href="'.route("$crudRoutePart.edit", $id).'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                      </a>
                                  </li>';
            $action .= $edit . " ";
        }

        // DELETE BUTTON
        if(!empty($deleteGate) && Gate::allowIf("$deleteGate")) {
            // DELETE BUTTON
//            $delete =  '
//                                    <form id="deletePermission'.$id.'" action="'.route("$crudRoutePart.destroy", $id).'" method="POST">
//                                        <input type="hidden" name="_method" value="DELETE">
//                                        <input type="hidden" name="_token" value="'.csrf_token().'">
//                                        <button class="removeBttnStyle" type="submit">
//                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
//                                        </button>
//                                    </form>';
//            $action .= $delete;
        }

        $action .= "</ul>";

        return $action;
    }
}
