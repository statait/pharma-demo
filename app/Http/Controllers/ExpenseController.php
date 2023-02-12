<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request){
		$title = 'expenses';
        if($request->ajax()){
            $expenses = Expense::get();
            return DataTables::of($expenses)
                    ->addIndexColumn()
                    ->addColumn('created_at',function($expense){
                        return date_format(date_create($expense->created_at),"d M,Y");
                    })
                    ->addColumn('action',function ($row){
                        $editbtn = '<a data-id="'.$row->id.'" data-date="'.$row->date.'" data-type="'.$row->type.'" data-amount="'.$row->amount.'" data-details="'.$row->details.'" href="javascript:void(0)" class="editbtn"><button class="btn btn-primary"><i class="fas fa-edit"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('expenses.destroy',$row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if(!auth()->user()->hasPermissionTo('edit-expenses')){
                            $editbtn = '';
                        }
                        if(!auth()->user()->hasPermissionTo('destroy-expenses')){
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.expense.expense',compact(
            'title'
        ));
	}
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'date'=>'required|max:100',
        // ]);
        
        Expense::create($request->all());
        $notification=array("Expense has been added");
        return back()->with($notification);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $this->validate($request,['name'=>'required|max:100']);
        $expense = Expense::find($request->id);
        $expense->update([
            'date'=>$request->date,
            'type'=>$request->type,
            'amount'=>$request->amount,
            'details'=>$request->details,
        ]);
        $notification = notify("Expense has been updated");
        return back()->with($notification);
    }

     /**
     * Generate sales reports index
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request){
        $title = 'expense reports';
        return view('admin.expense.reports',compact(
            'title'
        ));
    }

    /**
     * Generate sales report form post
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request){
        $this->validate($request,[
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $title = 'expense reports';
        $expenses = Expense::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->get();
        return view('admin.expense.reports',compact(
            'expenses','title'
        ));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Expense::findOrFail($request->id)->delete();
    }
}
