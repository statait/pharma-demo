<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Events\PurchaseOutStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SalePharma;
use Illuminate\Support\Carbon;
use PDF;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'sales';
        if($request->ajax()){
            $sales = SalePharma::latest();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    // ->addColumn('product',function($sale){
                    //     $image = '';
                    //     if(!empty($sale->product)){
                    //         $image = null;
                    //         if(!empty($sale->product->purchase->image)){
                    //             $image = '<span class="avatar avatar-sm mr-2">
                    //             <img class="avatar-img" src="'.asset("storage/purchases/".$sale->product->purchase->image).'" alt="image">
                    //             </span>';
                    //         }
                    //         return $sale->product->purchase->product. ' ' . $image;
                    //     }                 
                    // })
                    ->addColumn('date',function($row){
                        return date_format(date_create($row->created_at),'d M, Y');
                    })
                    ->addColumn('grand_total',function($sale){                   
                        return settings('app_currency','BDT').' '. $sale->grand_total;
                    })

                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("sale.details", $row->id).'" class="editbtn"><button class="btn btn-primary"><i class="fas fa-edit"></i></button></a>';
                        $downloadbtn = '<a href="'.route("sale.download", $row->id).'" class="btn btn-danger" title="Download Sale"><i class="fa fa-download"></i></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('sales.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-sale')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-sale')) {
                            $deletebtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('download-sale')) {
                            $downloadbtn = '';
                        }
                            $btn = $editbtn.' '.$deletebtn.' '. $downloadbtn;
                        return $btn;
                    })
                    // ->rawColumns(['grand_total','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.sales.index',compact(
            'title','products',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create sales';
        $products = Product::get();
        return view('admin.sales.create',compact(
            'title','products'
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
        $salePharmas_id = SalePharma::insertGetId([
            'date' => $request->date,
            'sub_total' => $request->subtotal,
            'grand_total' => $request->grandtotal,
            'discount_percentage' => $request->dper,
            'discount_flat' => $request->dflat,
            'paid' => $request->paidamount,
            'due' => $request->dueamount,
            'created_at' => Carbon::now(),   
  
        ]);

        $item = $request->input('item');
        $unit_cost = $request->input('unit_cost');
        $stock = $request->input('stock');
        $qty = $request->input('qty');
        $amount = $request->input('amount');
        
        foreach ($item as $key => $value) {

            $product = Product::findOrFail($value);
            $purchase_id = $product->purchase_id;

            Purchase::findOrFail($purchase_id)->update([
                'quantity' => $stock[$key] - $qty[$key],
            ]);
        // }

            Sale::create([
                'product_id' => $value,
                'salePharma_id' => $salePharmas_id,
                'quantity' => $qty[$key],
                'price' => $unit_cost[$key],
                // 'description' => $description[$key],
                'total_price' => $amount[$key],
            ]);
        }
    
		// return redirect()->back();
        $notification = array(
			'message' => 'Medicine has been sold',
			'alert-type' => 'success'
		);

        return redirect()->back()->with($notification);

    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        $title = 'edit sale';

        $sale_item = Sale::where('salePharma_id',$sale)->get();
        // $sale_item = Sale::get();
        return view('admin.sales.edit',compact(
            'title','sale','sale_item'
        ));
    }

    // Quotation View 
    public function SaleDetails($sale_id){

        $salePharma = SalePharma::findOrFail($sale_id);
        $saleItems = Sale::where('salePharma_id',$sale_id)->get();
        // $supplier = Supplier::where('purchase_id',$purchase_id)->get();
        // $products = Product::orderBy('product_name','ASC')->get();

        return view('admin.sales.edit',compact('salePharma','saleItems'));

} // end method 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|integer|min:1'
        ]);
        $sold_product = Product::find($request->product);
        /**
         * update quantity of sold item from purchases
        **/
        $purchased_item = Purchase::find($sold_product->purchase->id);
        if(!empty($request->quantity)){
            $new_quantity = ($purchased_item->quantity) - ($request->quantity);
        }
        $new_quantity = $sale->quantity;
        $notification = '';
        if (!($new_quantity < 0)){
            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            if(!empty($request->quantity)){
                $total_price = ($request->quantity) * ($sold_product->price);
            }
            $total_price = $sale->total_price;
            $sale->update([
                'product_id'=>$request->product,
                'quantity'=>$request->quantity,
                'total_price'=>$total_price,
            ]);

            $notification = notify("Product has been updated");
        } 
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification 
            $product = Purchase::where('quantity', '<=', 1)->first();
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = notify("Product is running out of stock!!!");
            
        }
        return redirect()->route('sales.index')->with($notification);
    }

    /**
     * Generate sales reports index
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request){
        $title = 'sales reports';
        return view('admin.sales.reports',compact(
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
        $title = 'sales reports';
        $sales = SalePharma::whereBetween(DB::raw('DATE(created_at)'), array($request->from_date, $request->to_date))->get();
        return view('admin.sales.reports',compact(
            'sales','title'
        ));
    }

    public function getProductPrice(Request $request){

        $selectedOption = $request->input('option');
        $data = Product::findOrFail($selectedOption);

        return $data;
    }

    public function getDataStock(Request $request){

        $selectedOption = $request->input('option');
        $product = Product::findOrFail($selectedOption);
        $purchase_id = $product->purchase_id;

        $data = Purchase::findOrFail($purchase_id);
    
        return $data;
    
    }

    public function AdminSaleDownload($sale_id){

		$salePharma = SalePharma::where('id',$sale_id)->first();
    	$saleItems = Sale::with('product','purchase')->where('salePharma_id',$sale_id)->orderBy('id','DESC')->get();

		$pdf = PDF::loadView('admin.sales.download_sale',compact('salePharma','saleItems'))->setPaper('a4')->setOptions([
				'tempDir' => public_path(),
				'chroot' => public_path(),
		]);
		return $pdf->download('Sale.pdf');

	} // end method 

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return SalePharma::findOrFail($request->id)->delete();
    }
}
