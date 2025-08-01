<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SpecialNote;
use DB;



class EcommerceSettingsDetails extends Controller
{
  public function index()
  {

        $SpecialNoteData = DB::table('special_notes')
        ->where('status', '=', 1)
        ->get(); 

    return view('content.apps.app-settings-details', ['SpecialNoteData' => $SpecialNoteData]);
  }

  public function getSpecialNote($note_id)
  {

        $SpecialNoteData = DB::table('special_notes')
        ->where('status', '=', 1)
        ->where('id', '=', $note_id)
        ->get(); 

      return response()->json(['success'=> true, 'SpecialNoteData' => $SpecialNoteData[0]]);

  }

    public function updateSpecialNote($note_id)
  {

      $SpecialNoteData = DB::table('special_notes')
      ->where('status', '=', 1)
      ->where('id', '=', $note_id)
      ->get(); 

     SpecialNote::where("id", $note_id)->update(
      [
        "value" => $_GET['modalNote'],
      ]);

 
    return response()->json( ['SpecialNoteData' => $SpecialNoteData, 'success'=> true, 'message' => 'Note has been updated!']);
  }


  
    public function saveSpecialNote() {
        $c = new SpecialNote();
        $c->value = ((isset($_GET['modalNote'])) ? $_GET['modalNote'] : "");
        $c->save();

       return response()->json( ['success' => true, 'message' => 'Note has been added!']);

    }

  
  
    public function showSpecialNote() {
       $SpecialNoteData = DB::table('special_notes')
        ->where('status', '=', 1)
        ->get(); 

        $key = 1;
      foreach($SpecialNoteData as $key => $data) {
        $key++;
        $specianNoteHtml[] = '<tr>
        <td>'.$key.'</td>
          <td>'.$data->value.'</td>
          <td class="text-end">
            <div class="dropdown pe-3">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addNewSpecialNote" onclick="editNote('.$data->id.')"><i class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                <a class="dropdown-item" onclick="promptDeleteNote('.$data->id.')"><i class="mdi mdi-delete-outline me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        </tr>';
      }

      return response()->json( ['specianNoteHtml' => $specianNoteHtml]);
    }



     public function deleteSpecialNote($note_id) {
      $delete = SpecialNote::where('id',$note_id)->delete();
      return response()->json( ['success' => true, 'message' => 'Note has been deleted!']);
    }


    
}
