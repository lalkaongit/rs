<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Lecture;

class LectureEditController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $test = Lecture::select()
             ->orderBy('id')
             ->get()
             ;

         //$test_columns = Schema::getColumnListing('tests');
         $test_model = new Lecture();
         $fillable_columns = $test_model->getFillable();
         foreach ($fillable_columns as $key => $value) {
             $test_columns[$value] = $value;
         }

         return view('test.index')
             ->with('test', $test)
             ->with('test_columns', $test_columns)
         ;
     }

    public function update(Request $request, $id)
    {
        $serie = Lecture::findOrFail($id);
        $sum_points = $request->get('sum_points');
        return $serie->save();
    }

    public function bulk_update(Request $request)
    {
        if (Input::has('ids_to_edit') && Input::has('bulk_name') && Input::has('bulk_value')) {
            $ids = Input::get('ids_to_edit');
            $bulk_name = Input::get('bulk_name');
            $bulk_value = Input::get('bulk_value');
            foreach ($ids as $id) {
                $test = Lecture::select()
                    ->where('id', '=', $id)
                    ->update([$bulk_name => $bulk_value]);
            }
            // return Redirect::route('client/leads')->with('message', $message);
            $message = "Done";
        } else {
            $message = "Error. Empty or Wrong data provided.";
            return Redirect::back()->withErrors(array('message' => $message))->withInput();
        }
        return Redirect::back()->with('message', $message);
    }

}
