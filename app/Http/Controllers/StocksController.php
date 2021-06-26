<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Batch;
use App\Batch_doc;
use App\Batch_history;
use App\Box;
use App\Cap;
use App\CustomerCategory;
use App\Expenses;
use App\Image_upload;
use App\Label;
use App\Material;
use App\Preform;
use App\Process;
use App\Processcap;
use App\Processlabel;
use App\Processpreform;
use App\Stock;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if(Session::has('adminSession'))
        {

        }else
        {
            return redirect()->route('admin.login')->with('flash_msg_error','Please login to access.');
        }

        $created_at = !empty(Batch::latest('created_at')->first()) ? Batch::latest('created_at')->first()->name
            : Carbon::yesterday()->format('d/m/Y/');
        $date=Carbon::now()->format('d/m/Y/');

        //Counter with three digit
        $index_str = ((int) substr($created_at,-3))+1;   //Get the 3 digit counter and increment by one
        $first_index_str = (substr($created_at,4,11));
        $index = str_pad($index_str, 3, 0,STR_PAD_LEFT);
        $Batch_name = '';

        $user_id = Admin::getUser(Session::get('adminSession'))->id;

        if ($date == $first_index_str)
        {
            $Batch_name = $date.$index;
        }else
        {
            $Batch_name = $date . '001';
        }
//        dd($Batch_name);17/10/2020/005

        $cases = Box::all();

        if ($request->isMethod('post') && !empty($request->material)) {
            $data = $request->all();
//            dd($data);
//            $material =!empty($data['batch_name']) ? (substr($data['batch_name'],0,14)) : '';
            $material =substr($Batch_name,0,14);

            $batch = new Batch();
            $batch_history = new Batch_history();

//            $batch->name = $data['batch_name'];
            $material_id = !empty($data['material']) ? $data['material'] : 0;
            $case_id = $data['case'];
            $no_bags = $data['no_bags'];
            $kg_per_bag = $data['kg_bags'];
            $total_kg = $kg_per_bag * $no_bags;
            $company = $data['company'];
            $amount = (float) str_replace(',', '', $data['amount']);
            $comment = $data['comment'];
            $comments = '';

            // $case = Box::where('id', $case_id)->first();

            $material_g = 0;
            //Get weight per unit material (g)
            $batch_info_pre =Batch::query()->where('preform_id','!=',0)->orwhere('name',  'like', '%' . $material . '%' )->latest()->first();
            $batch_info_cap =Batch::query()->where('cap_id','!=',0)->orwhere('name',  'like', '%' . $material . '%' )->latest()->first();
            $batch_info_label =Batch::query()->where('label_id','!=',0)->orwhere('name',  'like', '%' . $material . '%' )->latest()->first();

            if ($material_id == 1) //Preform
            {
                if ($date == $first_index_str && !empty($batch_info_pre->name))
                {
                    $new_index =substr($batch_info_pre->name,-1,3)+1;
                    $index = str_pad($new_index, 3, 0,STR_PAD_LEFT);
//                    dd($index);
                    $Batch_name = 'PRE-'.$date.$index;
                }else
                {
                    $Batch_name = 'PRE-'.$date . '001';
                }

                $material_g = $data['preform_g'];
                $total_material = $total_kg * 1000 / $material_g;
                $no_material_bag = $total_material / $no_bags;

                //get the initial material_g
//                $cur_preform = Preform::where('open',0)->where('preform_g',$material_g)->where('box_id',$case_id)->first();

                /*======================================== PREFORM ========================================*/
//                if (!empty($cur_preform))
//                {
//                    $new_no_bags = $cur_preform->no_bags + $no_bags;
//                    $new_total_kg = $cur_preform->total_kg + $total_kg;
//                    $new_tot_preform = $cur_preform->tot_preform + $total_material;
//
//                    //If the case exist Update
//                    Preform::where('open',0)->where('preform_g',$material_g)->where('box_id',$case_id)->update([
//                        'no_bags'=>$new_no_bags,
//                        'total_kg'=>$new_total_kg,
//                        'tot_preform'=>$new_tot_preform,
//                    ]);
////                    $comments = 1;
//
//                }else{
                    //Create New
                    $preform = new Preform();
                    $preform->no_bags = $no_bags;
                    $preform->kg_per_bag = $kg_per_bag;
                    $preform->total_kg = $total_kg;
                    $preform->preform_g = $material_g;
                    $preform->tot_preform = $total_material;
                    $preform->no_preform = $no_material_bag;
                    $preform->box_id = $case_id;
                    $preform->company = $company;

                    $preform->save();
//                }

                $preform_id = DB::getPdo()->lastInsertId();

                $comment = !empty($comments) ? $comments : $comment;

                $batch->name =$Batch_name;
                $batch->preform_id =$preform_id;
                $batch->cap_id = 0;
                $batch->label_id = 0;
                $batch->comment = $comment;
                $batch->amount = $amount;
                $batch->save();

                $batch_id = $batch->id;

                $batch_history->batch_id=$batch_id;
                $batch_history->batch_name=$Batch_name;
                $batch_history->amount=$amount;
                $batch_history->material="Preforms";
                $batch_history->no_bags=$no_bags;
                $batch_history->kg_per_bags=$kg_per_bag;
                $batch_history->total_kg=$total_kg;
                $batch_history->unit_g=$material_g;
                $batch_history->tot_materials=$total_material;
                $batch_history->no_materials=$no_material_bag;
                $batch_history->company=$company;
                $batch_history->description=$comment;
                $batch_history->save();

                //Add amount to expenses Table
                $batch_details = Batch_history::where('batch_id', $batch_id)->first();
                $expense = new Expenses();
                $expense->amount = $batch_details->amount;
                $expense->user_id = $user_id;
                $expense->batch_code = $Batch_name;
                $expense->cash_type = 'Wired';
                $expense->description = 'A Batch of '. $batch_details->material . '
                 with batch code '. $batch_details->batch_name . ' Purchased on 
                 '. Carbon::parse($batch_details->created_at)->toFormattedDateString();
                $expense->save();

            } elseif ($material_id == 2) //Cap
            {
                if ($date == $first_index_str && !empty($batch_info_cap->name))
                {
                    $new_index =substr($batch_info_pre->name,-1,3)+1;
                    $index = str_pad($new_index, 3, 0,STR_PAD_LEFT);
                    $Batch_name = 'CAP-'.$date.$index;
                }else
                {
                    $Batch_name = 'CAP-'.$date . '001';
                }

                $material_g = $data['preform_g'];
                $total_material = $total_kg * 1000 / $material_g;
                $no_material_bag = $total_material / $no_bags;

                /*======================================== CAP ========================================*/
//                $cur_cap = Cap::where('open',0)->first();
//                if (!empty($cur_cap))
//                {
//                    $new_no_bags = $cur_cap->no_bags + $no_bags;
//                    $new_total_kg = $cur_cap->total_kg + $total_kg;
//                    $new_tot_cap = $cur_cap->tot_cap + $total_material;
//
//                    //If the case exist Update
//                    Cap::where('open',0)->update([
//                        'no_bags'=>$new_no_bags,
//                        'total_kg'=>$new_total_kg,
//                        'tot_cap'=>$new_tot_cap,
//                    ]);
//
////                    $comments = 1;
//
//                }else{
                    //Create New
                    $cap = new Cap();
                    $cap->no_bags = $no_bags;
                    $cap->kg_per_bag = $kg_per_bag;
                    $cap->total_kg = $total_kg;
                    $cap->cap_g = $material_g;
                    $cap->tot_cap = $total_material;
                    $cap->no_cap = $no_material_bag;
                    $cap->box_id = $case_id;
                    $cap->company = $company;

                    $cap->save();
//                }

                $cap_id = DB::getPdo()->lastInsertId();

                $comment = !empty($comments) ? $comments : $comment;

                $batch->name =$Batch_name;
                $batch->cap_id = $cap_id;
                $batch->preform_id = 0;
                $batch->label_id = 0;
                $batch->amount = $amount;
                $batch->comment = $comment;
                $batch->save();

                $batch_id = $batch->id;

                $batch_history->batch_id=$batch_id;
                $batch_history->batch_name=$Batch_name;
                $batch_history->amount=$amount;
                $batch_history->material="Caps";
                $batch_history->no_bags=$no_bags;
                $batch_history->kg_per_bags=$kg_per_bag;
                $batch_history->total_kg=$total_kg;
                $batch_history->unit_g=$material_g;
                $batch_history->tot_materials=$total_material;
                $batch_history->no_materials=$no_material_bag;
                $batch_history->company=$company;
                $batch_history->description=$comment;
                $batch_history->save();

                //Add amount to expenses Table
                $batch_details = Batch_history::where('batch_id', $batch_id)->first();
                $expense = new Expenses();
                $expense->amount = $batch_details->amount;
                $expense->user_id = $user_id;
                $expense->batch_code = $Batch_name;
                $expense->cash_type = 'Wired';
                $expense->description = 'A Batch of '. $batch_details->material . '
                 with batch code '. $batch_details->batch_name . ' Purchased on 
                 '. Carbon::parse($batch_details->created_at)->toFormattedDateString();
                $expense->save();

            } elseif ($material_id == 3)  //Label
            {
//                dd('Dee');
                if ($date == $first_index_str && !empty($batch_info_label->name))
                {
                    $new_index =substr($batch_info_pre->name,-1,3)+1;
                    $index = str_pad($new_index, 3, 0,STR_PAD_LEFT);
                    $Batch_name = 'LBL-'.$date.$index;
                }else
                {
                    $Batch_name = 'LBL-'.$date . '001';
                }

                $material_g = $data['preform_g'];
                $total_material = $total_kg * 1000 / $material_g;
                $no_material_bag = $total_material / $no_bags;

                /*======================================== LABEL ========================================*/
                $cur_label = Label::where('open',0)->where('label_g',$material_g)->where('box_id',$case_id)->first();

//                if (!empty($cur_label))
//                {
//                    $new_no_bags = $cur_label->no_bags + $no_bags;
//                    $new_total_kg = $cur_label->total_kg + $total_kg;
//                    $new_tot_label = $cur_label->tot_label + $total_material;
//
//                    //If the case exist Update
//                    Label::where('open',0)->where('label_g',$material_g)->where('box_id',$case_id)->update([
//                        'no_bags'=>$new_no_bags,
//                        'total_kg'=>$new_total_kg,
//                        'tot_label'=>$new_tot_label,
//                    ]);
//
////                    $comments = 1;
//
//                }else{
                    //Create New
                    $label = new Label();
                    $label->no_bags = $no_bags;
                    $label->kg_per_bag = $kg_per_bag;
                    $label->total_kg = $total_kg;
                    $label->label_g = $material_g;
                    $label->tot_label = $total_material;
                    $label->no_label = $no_material_bag;
                    $label->box_id = $case_id;
                    $label->company = $company;
                    $label->company = $company;

                    $label->save();
//                }

                $lanel_id = DB::getPdo()->lastInsertId();

                $comment = !empty($comments) ? $comments : $comment;

                $batch->name =$Batch_name;
                $batch->label_id = $lanel_id;
                $batch->preform_id = 0;
                $batch->cap_id = 0;
                $batch->amount = $amount;
                $batch->comment = $comment;
                $batch->save();

                $batch_id = $batch->id;

                $batch_history->batch_id=$batch_id;
                $batch_history->batch_name=$Batch_name;
                $batch_history->amount=$amount;
                $batch_history->material="Labels";
                $batch_history->no_bags=$no_bags;
                $batch_history->kg_per_bags=$kg_per_bag;
                $batch_history->total_kg=$total_kg;
                $batch_history->unit_g=$material_g;
                $batch_history->tot_materials=$total_material;
                $batch_history->no_materials=$no_material_bag;
                $batch_history->company=$company;
                $batch_history->description=$comment;
                $batch_history->save();

                //Add amount to expenses Table
                $batch_details = Batch_history::where('batch_id', $batch_id)->first();
                $expense = new Expenses();
                $expense->amount = $batch_details->amount;
                $expense->user_id = $user_id;
                $expense->batch_code = $Batch_name;
                $expense->cash_type = 'Wired';
                $expense->description = 'A Batch of '. $batch_details->material . '
                 with batch code '. $batch_details->batch_name . ' Purchased on 
                 '. Carbon::parse($batch_details->created_at)->toFormattedDateString();
                $expense->save();

            }

            $batch_id = DB::getPdo()->lastInsertId();

            if ($request->hasFile('files'))
            {
                if (!is_dir(public_path('/batches/documents')))
                {
                    mkdir(public_path('batches/documents'), '0755',true);
                }

                $files = Collection::wrap($request->file('files'));

                $files->each(function ($file) use ($batch_id) {
                    $baseName = $file->getClientOriginalName();
                    $original_name = time().'.'.$baseName;

                    $file->move(public_path('/batches/documents'),  $original_name);

                    Batch_doc::create([
                        'batch_id'=>$batch_id,
                        'name'=>$baseName,
                        'doc_path'=>'/batches/documents/'.$original_name
                    ]);
                });
            }

            $notification = array(
                'message' => 'New Batch Record added successfully!',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);


        }
//        dd($labels);
        $materials = Material::all();
        $batches = Batch::all();
//        $batches = json_decode(json_encode($batches));
//        echo '<pre>'; print_r($batches); die();
//        foreach ($batches as $batch) {
//            if ($batch->comment != 1)
//            {
//                if ($batch->preform_id != 0)
//                {
//                    echo $batch->id ; echo '<br>';
//                }
//
//            }
//
//        }
//        die();
        return view('admin.stock.index', compact('Batch_name','cases','materials', 'batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editBatch(Request $request, $id=null)
    {
        if(Session::has('adminSession'))
        {

        }else
        {
            return redirect()->route('admin.login')->with('flash_msg_error','Please login to access.');
        }

        if ($request->isMethod('post'))
        {
            $data = $request->all();

                $case_id = $data['case_id'];
                $material_id = Batch::where('id',$id)->first();

                //Preform
                $no_bags=0;
                $no_bags = $data['no_bags'];

                $preform_id=$material_id->preform_id;
//                echo  '<pre>'; print_r($preform_id. ', $no_bags: '.$no_bags. ' counter: '.$counter);
                if ($preform_id != 0  && $no_bags !=0)
                {
                    $kg_per_bag = $data['kg_per_bag'];
                    $no_material_per_bag = $data['no_material_bag'];
                    $material_g = Box::where('id',$case_id)->first()->preform_g;
                    $total_kg = $no_bags*$kg_per_bag;
                    $tot_material = $total_kg*1000/$material_g;
                    $no_material_per_bag = $tot_material/$no_bags;

//                    echo  '<pre>'; print_r($kg_per_bag. ' counter: '.$counter);
                    Preform::where(['id'=>$preform_id])->update(['no_bags'=>$no_bags,'kg_per_bag'=>$kg_per_bag,'total_kg'=>$total_kg,
                        'preform_g'=>$material_g,'tot_preform'=>$tot_material,'no_preform'=>$no_material_per_bag]);
                }

                //Cap
                $no_bags=0;
                $no_bags = $data['no_bags'];
                $cap_id = $material_id->cap_id;

                if ($cap_id != 0 && $no_bags !=0)
                {
                    $kg_per_bag = $data['kg_per_bag'];
                    $no_material_per_bag = $data['no_material_bag'];
                    $material_g = Box::where('id',$case_id)->first()->cap_g;
                    $total_kg = $no_bags*$kg_per_bag;
                    $tot_material = $total_kg*1000/$material_g;
                    $no_material_per_bag = $tot_material/$no_bags;

//                    dd($kg_per_bag);

                    $cap = Cap::where(['id'=>$cap_id])->update(['no_bags'=>$no_bags,'kg_per_bag'=>$kg_per_bag,'total_kg'=>$total_kg,
                        'cap_g'=>$material_g,'tot_cap'=>$tot_material,'no_cap'=>$no_material_per_bag]);
//                    dd($id);
                }

                //Label
                $no_bags=0;
                $no_bags = $data['no_bags'];
                $label_id = $material_id->label_id;

                if ($label_id != 0 && $no_bags !=0)
                {
                    $kg_per_bag = $data['kg_per_bag'];
                    $no_material_per_bag = $data['no_material_bag'];
                    $material_g = Box::where('id',$case_id)->first()->label_g;
                    $total_kg = $no_bags*$kg_per_bag;
                    $tot_material = $total_kg*1000/$material_g;
                    $no_material_per_bag = $tot_material/$no_bags;
//                    dd($label_id);
                    Label::where(['id'=>$label_id])->update(['no_bags'=>$no_bags,'kg_per_bag'=>$kg_per_bag,'total_kg'=>$total_kg,
                        'label_g'=>$label_id,'tot_label'=>$tot_material,'no_label'=>$no_material_per_bag]);
                }

            $notification = array(
                'message' => 'Batch Record has been Updated Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        }
        $batch = Batch::where('id',$id);
        return view('admin.stock.index', compact('batch'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $preform_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'PRE' . '%' )->first();
//        $cap_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'CAP' . '%' )->first();
//        $label_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'LBL' . '%' )->first();

        $preform_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'PRE' . '%' )->first();
        $cap_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'CAP' . '%' )->first();
        $label_info =Batch_history::query()->where(['batch_id'=>$id])->where('batch_name',  'like',  'LBL' . '%' )->first();

        if (!empty($preform_info))
        {
            Preform::where('id' , $preform_info->batch->preform_id)->delete();
            $rows = Batch_history::query()->where('unit_g',$preform_info->unit_g)->where('batch_name',  'like',  'PRE' . '%' )->get();
            foreach ($rows as $row) {
                //Delete Batch row
                $do_nothing_otherwise = !empty(Batch_doc::where('batch_id', $row->batch_id)) ? Batch_doc::where('batch_id', $row->batch_id)->delete() : '';
                Batch::where('id',$row->batch_id)->delete();
                Batch_history::where(['id'=>$row->id])->delete();
                Expenses::where('batch_code', $row->batch_name)->delete();
            }
        }

        if (!empty($label_info))
        {
            Label::where('id' , $label_info->batch->label_id)->delete();
            $rows = Batch_history::query()->where('unit_g',$label_info->unit_g)->where('batch_name',  'like',  'LBL' . '%' )->get();
            foreach ($rows as $row) {
                //Delete Batch row
                $do_nothing_otherwise = !empty(Batch_doc::where('batch_id', $row->batch_id)) ? Batch_doc::where('batch_id', $row->batch_id)->delete() : '';
                Batch::where('id',$row->batch_id)->delete();
                Batch_history::where(['id'=>$row->id])->delete();
                Expenses::where('batch_code', $row->batch_name)->delete();
            }

        }

        if (!empty($cap_info))
        {
            Cap::where('id' , $cap_info->batch->cap_id)->delete();
            $rows = Batch_history::query()->where('unit_g',$cap_info->unit_g)->where('batch_name',  'like',  'CAP' . '%' )->get();
            foreach ($rows as $row) {
                //Delete Batch row
                $do_nothing_otherwise = !empty(Batch_doc::where('batch_id', $row->batch_id)) ? Batch_doc::where('batch_id', $row->batch_id)->delete() : '';
                Batch::where('id',$row->batch_id)->delete();
                Batch_history::where(['id'=>$row->id])->delete();
                Expenses::where('batch_code', $row->batch_name)->delete();
            }
        }

            $notification = array(
                'message' => 'Stock record deleted! Successfully!',
                'alert-type' => 'error'
            );
            Batch::where(['id'=>$id])->delete();
            return redirect()->route('stocks')->with($notification);


    }

    public function viewPage(Request $request)
    {
        $histories = Batch_history::all();
        $method = $request->method();
        if ($request->isMethod('post'))
        {
            $from = Carbon::parse($request->input('from') . ' 01:00:00')->toDateTimeString();
            $to = Carbon::parse($request->input('to') . ' 23:59:00')->toDateTimeString();
            if ($request->has('search'))
            {
                // select search
                $search = DB::select("SELECT * FROM batch_histories WHERE created_at BETWEEN '$from' AND '$to'");
//                dd($search);
                return view('admin.stock.history',['ViewsPage' => $search]);
            }elseif ($request->has('exportPDF'))
            {
                // select PDF
                $PDFReport = Batch_history::whereBetween('created_at', [$from, $to])->get();
//                dd($PDFReport);
//                $PDFReport = DB::select("SELECT * FROM batch_histories WHERE created_at BETWEEN '$from' AND '$to'");
//                $total_cost =  array_sum(array_column($PDFReport, 'amount'));
                $total_cost = Batch_history::with('batch')->whereBetween('created_at', [$from, $to])->sum('amount');

                $preforms_amt = Batch_history::where('material','Preforms')->whereBetween('created_at', [$from, $to])->sum('amount');
                $preforms_qty = Batch_history::where('material','Preforms')->whereBetween('created_at', [$from, $to])->sum('tot_materials');
                $caps_amt = Batch_history::where('material','Caps')->whereBetween('created_at', [$from, $to])->sum('amount');
                $caps_qty = Batch_history::where('material','Caps')->whereBetween('created_at', [$from, $to])->sum('tot_materials');
                $labels_amt = Batch_history::where('material','Labels')->whereBetween('created_at', [$from, $to])->sum('amount');
                $labels_qty = Batch_history::where('material','Labels')->whereBetween('created_at', [$from, $to])->sum('tot_materials');

//                dd($preforms_close_qty);
//                $preforms_amt = DB::table('batch_histories')->where('batch', 'like', 'PRE%')->whereBetween('created_at', [$from, $to])->sum('amount');
                /*$caps_amt = DB::table('damages')->where('batch', 'like', 'CAP%')
                    ->whereBetween('created_at', [$from, $to])->sum('amount');
                $labels_amt = DB::table('damages')->where('batch', 'like', 'LBL%')
                    ->whereBetween('created_at', [$from, $to])->sum('amount');

                $preforms_qty = DB::table('damages')->where('batch', 'like', 'PRE%')
                    ->whereBetween('created_at', [$from, $to])->sum('quantity');
                $caps_qty = DB::table('damages')->where('batch', 'like', 'CAP%')
                    ->whereBetween('created_at', [$from, $to])->sum('quantity');
                $labels_qty = DB::table('damages')->where('batch', 'like', 'LBL%')
                    ->whereBetween('created_at', [$from, $to])->sum('quantity');*/
//                foreach ($PDFReport as $item) {
//                    dd($item->batch->preform->box->case);
//                }

                $pdf = PDF::loadView('admin.stock.pdfview', ['PDFReport' => $PDFReport, 'from'=>$from, 'to'=>$to, 'preforms_amt'=>$preforms_amt, 'preforms_qty'=>$preforms_qty,
                    'caps_amt'=>$caps_amt, 'caps_qty'=>$caps_qty, 'labels_amt'=>$labels_amt, 'labels_qty'=>$labels_qty,
                    'total_cost'=>$total_cost])->setPaper('a4', 'portrait');

                $pdf->setOptions([
                    "isPhpEnabled" => true,
                    'footer-center' => 'Page [page] of [toPage]',
                    'footer-right' => '[date]',
                    'footer-line' => true,
                    'footer-left' => 'cfresh.org',
                    'footer-font-size' => 8,
                    'enable-javascript' => true,
                    'javascript-delay' => 5000,
                    'enable-smart-shrinking' => true,
                    'no-stop-slow-scripts' => true,
                    'margin-top' => 10,
                ]);
                return $pdf->stream('Batch-history-report.pdf');
            }
        }
        else
        {
            //select all
            $ViewsPage = Batch_history::all();

            return view('admin.stock.history',['ViewsPage' => $ViewsPage]);
        }
    }

    public function stocksHistory()
    {
        $batches = Batch::all();
        $batche_docs = Batch_doc::all();
        $histories = Batch_history::all();
        return view('admin.stock.history', compact('batche_docs','histories', 'batches'));
    }

    public function pdfview()
    {
        $pdfView = DB::select('SELECT * FROM batch_histories');
        $pdf = PDF::loadView('admin.stock.pdfview', ['pdfViews'=> $pdfView])
            ->setPaper('a4','landscape');
        return $pdf->download('report.pdf');
   }



}
