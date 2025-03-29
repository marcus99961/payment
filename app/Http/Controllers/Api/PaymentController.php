<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendPaymentNotiQueue;
use App\Models\Attachment;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use NumberFormatter;
use Illuminate\Http\UploadedFile;



class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//    public function __construct()
//    {
//        return $this->middleware('auth:api');
//    }
    protected $pdf;

    public function __construct(\App\Models\Pdf $pdf)
    {
        $this->pdf = $pdf;
        return $this->middleware('auth:api');
    }
    
    public function index(Request $request)
    {
        if(Auth::user()->role_id==2){
            $payments = DB::table('payments')
                ->join('departments','payments.department_id','departments.id')
                ->select('payments.*','departments.name')
                ->where('paid_status',false)
                ->where(function($query) use ($request){
                $query->where('supplier', 'LIKE', '%'.$request->keyword.'%')
                    ->orWhere('description', 'LIKE', '%'.$request->keyword.'%')
                    ->orWhere('py_no', 'LIKE', '%'.$request->keyword.'%')
                    ->orWhere('name', 'LIKE', '%'.$request->keyword.'%');
                    })
                ->orderBy('id','desc')
                ->limit(70)
                ->get();
            return response()->json($payments);
        }else{
        $payments = DB::table('payments')
            ->where('department_id',Auth::user()->department_id)
            ->where('paid_status',false)
            ->where(function($query) use ($request){
                $query->where('supplier', 'LIKE', '%'.$request->keyword.'%')
                    ->orWhere('description', 'LIKE', '%'.$request->keyword.'%')
                    ->orWhere('name', 'LIKE', '%'.$request->keyword.'%');
            })
            ->join('departments','payments.department_id','departments.id')
            ->select('payments.*','departments.name')
            ->orderBy('id','desc')
            ->get();
        return response()->json($payments);
        }
    }
    public function record(Request $request)
    {
        if(Auth::user()->role_id==2){
            $payments = DB::table('payments')
                ->join('departments','payments.department_id','departments.id')
                ->select('payments.*','departments.name')
                ->where('paid_status',true)
                ->where(function($query) use ($request){
                    $query->where('supplier', 'LIKE', '%'.$request->keyword.'%')
                        ->orWhere('description', 'LIKE', '%'.$request->keyword.'%')
                        ->orWhere('name', 'LIKE', '%'.$request->keyword.'%');
                })
                ->orderBy('id','desc')
                ->limit(50)
                ->get();
            return response()->json($payments);
        }else{
            $payments = DB::table('payments')
                ->where('department_id',Auth::user()->department_id)
                ->where('paid_status',true)
                ->where(function($query) use ($request){
                    $query->where('supplier', 'LIKE', '%'.$request->keyword.'%')
                        ->orWhere('description', 'LIKE', '%'.$request->keyword.'%')
                        ->orWhere('name', 'LIKE', '%'.$request->keyword.'%');
                })
                ->join('departments','payments.department_id','departments.id')
                ->select('payments.*','departments.name')
                ->orderBy('id','desc')
                ->limit(50)
                ->get();
            return response()->json($payments);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $last= Payment::orderBy('id', 'DESC')->first();
        if($last){
            $last_id = $last->id + 200000 ;
        }else{
            $last_id = 200000;
        }

        $validateData = $request->validate([
            'payment_date' => 'required',
            'supplier' => 'required',
            'currency'=>'required',
            'amount'=>'required|numeric',
            'ct'=>'required|numeric',
            'ac_name'=>'required',

            'settle_by'=>'required',



        ]);
        if(!$request->ct==0){
            $ct = $request->amount * $request->ct/100;
        }else{
            $ct = 0;
        }
       
        $payment = new Payment();
        $payment->payment_date = $request->payment_date;
        $payment->supplier = $request->supplier;
        $payment->currency = $request->currency;
        $payment->amount = $request->amount;
        $payment->ct = $request->ct;
        $payment->py_no = $last_id;
        $payment->description = $request->description;
        $payment->ac_name = $request->ac_name;
        $payment->settle_by = $request->settle_by;
        $payment->department_id = Auth::user()->department_id;
        $payment->user_id = Auth::user()->id;
        $payment->signature = Auth::user()->signature;



        $payment->save();
        $cc = Auth::user()->email;
        SendPaymentNotiQueue::dispatch($payment,$cc);

        return response()->json('Success');
    }
    public function attachedfiles()
    {
       $files= Attachment::all();
       return response()->json($files);
    }
    public function fileupload(Request $request)

    {
       // dd($request->payment_id);
        $upload_path = 'upload/';
        $fileName = time().'.'.$request->file->getClientOriginalExtension();

        $request->file->move(public_path('upload'), $fileName);

        $attachment_url = $upload_path.$fileName;  
        Attachment::create([
            'payment_id' => $request->payment_id,
            'attachment' => $attachment_url,
            'user_id' => Auth::user()->id,
        ]);      

        return response()->json(['success'=>'You have successfully upload file.']);

    }

    public function deleteattachment($id)
    {
        $deleteAttachment = Attachment::find($id);
        if($deleteAttachment->user_id == Auth::user()->id){  

           
           unlink($deleteAttachment->attachment);
         
           $deleteAttachment->delete();
            return response()->json(['success' => true], 200);
        }else{
            return response()->json(['success' => false], 503);
        }



    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Payment::find($id)->update([
            'supplier'=>$request->supplier,
            'description'=>$request->description,
            'currency'=>$request->currency,
            'amount'=>$request->amount,
            'ct'=>$request->ct,
            'settle_by'=>$request->settle_by,
            'ac_name'=>$request->ac_name,

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Payment::find($id)->delete();
    }
    public function paid(string $id)
    {
        Payment::find($id)->update([
            'paid_status'=> true,
        ]);
    }
    public function form(string $id)
    {
        $approval1 = Setting::find(1)->value;
        $approval2 = Setting::find(2)->value;
        $approval3 = Setting::find(4)->value;
       // dd($approval3);
        $payment = Payment::where('id',$id)->first();
        if(!$payment->ct==0 && $payment->ct <= 15){
            $ct = $payment->amount * $payment->ct/100;
        } elseif($payment->ct > 15){
            $ct = $payment->ct;
        }
        else{
            $ct = 0;
        }
        $from = $payment->department_id;
        if($payment->settle_by=='BKTR'){

            $settText = 'arrange bank transfer';

        }
        else if($payment->settle_by==='Cash'){

            $settText = 'issue Cash';

        }
        else {

            $settText = 'issue Cheque';

        }
        $total_amt = $payment->amount + $ct ;

        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $word_net = $f->format($payment->amount);
        $total_2 = round($total_amt,2);
        $word_total =  $f->format($total_2);
        $this->pdf->AddPage();
        // $this->pdf->SetTitle();
        $width_cell=array(15,35,65,18,55,15,30,22,23);

        $this->pdf->SetFont('Arial','',11);

        $this->pdf->SetFillColor(193,229,252); // Background color of header
        // $this->pdf->Cell(270,10,$dept,0,1,'R',false);


            $this->pdf->Cell(140,7,'To : Accounts Department',0,0,'L',false); // Second header column
            $this->pdf->Cell(25,7,'Date:',0,0,'L',false); // Third header column
            $this->pdf->Cell(20,7,date('d-m-y', strtotime($payment->payment_date)) ,0,1,'R',false); // Fourth header column
            $this->pdf->Cell(20,7,'From:',0,0,'L',false); // Third header column
            $this->pdf->Cell(115,7,$payment->department->name,0,0,'L',false);
            $this->pdf->Cell(25,7,'PY_No:',0,0,'L',false); // Third header column
            $this->pdf->Cell(25,7,$payment->py_no,0,1,'R',false);
            $this->pdf->Ln(10);
            $this->pdf->Cell(120,10,'Please '.$settText.' of amount',0,0,'C',false);

//            $this->pdf->Cell(50,10,' of amount',0,0,'C',false);
            $this->pdf->Cell(20,10,$payment->currency.' -',0,0,'C',false);  // Third header column
            $this->pdf->Cell(40,10,\number_format($total_amt,2),0,1,'R',false);
            $this->pdf->Line(140,78,195,78); // Third header column
            $this->pdf->Cell(20,10,'in words ',0,0,'L',false); // Third header column
            $this->pdf->Cell(165,10,$word_total.' only',0,1,'C',false); // Third header column
            $this->pdf->Line(30,88,195,88);
            // $this->pdf->Cell(20,10,')',0,1,'L',false); // Third header

                $this->pdf->Cell(25,10,'Net Amount -',0,0,'L',false);
                $this->pdf->Cell(70,10,\number_format($payment->amount,2),0,0,'L',false);
                if($payment->ct > 15){
                    $this->pdf->Cell(50,10,'5% fixed CT -',0,0,'L',false);
                }else{
                $this->pdf->Cell(50,10,$payment->ct.'% CT -',0,0,'L',false);
                }
                $this->pdf->Cell(50,10,\number_format($ct,2),0,1,'L',false);
                $this->pdf->Line(34,98,100,98);
                $this->pdf->Line(120,98,195,98);
                if($payment->user->signature){
                    $signature = $payment->user->signature;
                $this->pdf->Image($signature,22,168,30);
                }
//            $this->pdf->Line(30,88,195,88);
            $this->pdf->Cell(25,10,'in favour of -',0,0,'L',false);
            $this->pdf->Cell(160,10,$payment->supplier,0,1,'L',false);
            $this->pdf->Line(34,108,195,108);
            $this->pdf->Cell(25,10,'for -',0,0,'L',false);
            $this->pdf->Cell(160,10,$payment->description,0,1,'L',false);
            $this->pdf->Line(25,118,195,118);
            $this->pdf->Cell(25,10,$payment->settle_by,0,0,'L',false);
            $this->pdf->Line(11,128,25,128);
            $this->pdf->Cell(35,10,'should be delivered to  -',0,0,'C',false);
            $this->pdf->Cell(120,10,$payment->ac_name,0,1,'C',false);
            $this->pdf->Line(73,128,195,128);
            $this->pdf->Cell(25,10,'.....after signing.',0,1,'L',false);
            $this->pdf->Ln(17);
            $this->pdf->Cell(190,10,'Prepared By                         Checked By                       Approved By                     Approved By',0,1,'C',false);
            $this->pdf->Ln(25);
            $this->pdf->Cell(190,10,'_____________                    ______________                ______________               ______________',0,1,'C',false);
            $this->pdf->Cell(48,10,$payment->department->name,0,0,'C',false);
            $this->pdf->Cell(48,10,$approval1,0,0,'C',false);
            $this->pdf->Cell(48,10,$approval2,0,0,'C',false);
            $this->pdf->Cell(48,10,$approval3,0,1,'C',false);

            $this->pdf->Ln(10);
            $this->pdf->Cell(185,10,'PLEASE DELETE IF NOT APPLICABLE',0,1,'L',false);
            $this->pdf->Line(12,222,195,222);
            $this->pdf->Cell(185,10,'For Accounts Department use only;',0,1,'L',false);
            $this->pdf->Ln(10);
            $this->pdf->Cell(25,10,$payment->settle_by,0,0,'L',false);
            $this->pdf->Cell(160,10,'Received By :    ....................',0,1,'R',false);
            $this->pdf->Ln(5);
            $this->pdf->Cell(185,10,'Supporting Document received on .................',0,1,'L',false);





        $this->pdf->Output();
        exit;
    }
    public function report(Request $request)
    {
        // $validateData = $request->validate([
        //     'from_date' => 'required',
        //     'to_date'=>'required'




        //    ]);

        $payments = Payment::whereBetween('payment_date', [$request->from_date, $request->to_date])
            ->where('paid_status', true)
            ->orderBy('payment_date')
            ->get();

        $this->fpdf = new Fpdf();
        $width_cell=array(10,20,32,95,55,25,25,25,20,30,219);
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetFont('Times', 'B', 11);
        $this->fpdf->SetFillColor(230,230,230);
        $this->fpdf->Cell(261,10,'Report from '.date('d-m-y', strtotime($request->from_date)).' to '.date('d-m-y', strtotime($request->to_date)),0,1,'C',true);
        $this->fpdf->Cell($width_cell[0], 10, 'Sr',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10,'Date',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Department',1,0,'C');
        $this->fpdf->Cell($width_cell[3], 10, 'Descriptions',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Supplier',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10, 'Settle by',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10, 'Currency',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Amount',1,1,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Amount',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Kilo',1,1,'C');
        $i=1;
        $this->fpdf->SetFont('Times', '', 10);
        foreach($payments as $dat) {
            $this->fpdf->Cell($width_cell[0], 8, $i++,1,0,'R');
            $this->fpdf->Cell($width_cell[1], 8, date('d-m-y', strtotime($dat->payment_date)),1,0);
            $this->fpdf->Cell($width_cell[2], 8, $dat->department->name,1,0,'L');
            $this->fpdf->Cell($width_cell[3], 8, $dat->description,1,0,'L');
            $this->fpdf->Cell($width_cell[2], 8, $dat->supplier,1,0,'C');
            $this->fpdf->Cell($width_cell[1], 8, $dat->settle_by,1,0,'C');
            $this->fpdf->Cell($width_cell[1], 8, $dat->currency,1,0,'C');
            $this->fpdf->Cell($width_cell[2], 8, $dat->amount,1,1,'R');
            //   $this->fpdf->Cell($width_cell[1], 8, $dat->kilo,1,1,'R');
        }
        // $this->fpdf->Cell($width_cell[4], 10, 'Fuel Kilo/Liter- '. $average_consume,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $last_qty->qty,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, number_format($average, 2, '.', ''),1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $sum_amt,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $kilo_total,1,1,'R');
        $this->fpdf->Output();

        exit;
    }
    public function reportbydept(Request $request)
    {
        // $validateData = $request->validate([
        //     'from_date' => 'required',
        //     'to_date'=>'required'




        //    ]);

        $payments = Payment::whereBetween('payment_date', [$request->from_date, $request->to_date])
            ->where('department_id', Auth::user()->department_id)
            ->where('currency','MMK')
            ->orderBy('payment_date')
            ->get();
            $total = Payment::whereBetween('payment_date', [$request->from_date, $request->to_date])
            ->where('department_id', Auth::user()->department_id)
            ->where('currency','MMK')
            ->selectRaw('sum(amount) as total_amount')
            ->first();
          //  dd($total);

        $this->fpdf = new Fpdf();
        $width_cell=array(10,20,32,95,55,25,25,25,20,30,219);
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetFont('Times', 'B', 11);
        $this->fpdf->SetFillColor(230,230,230);
        $this->fpdf->Cell(261,10,'MMK Report  '.date('d-m-y', strtotime($request->from_date)).'  To  '.date('d-m-y', strtotime($request->to_date)),0,1,'C',true);
        $this->fpdf->Cell($width_cell[0], 10, 'Sr',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10,'Date',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Department',1,0,'C');
        $this->fpdf->Cell($width_cell[3], 10, 'Descriptions',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Supplier',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10, 'Settle by',1,0,'C');
        $this->fpdf->Cell($width_cell[1], 10, 'Currency',1,0,'C');
        $this->fpdf->Cell($width_cell[2], 10, 'Amount',1,1,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Amount',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Kilo',1,1,'C');
        $i=1;
        $this->fpdf->SetFont('Times', '', 10);
        foreach($payments as $dat) {
            $this->fpdf->Cell($width_cell[0], 8, $i++,1,0,'R');
            $this->fpdf->Cell($width_cell[1], 8, date('d-m-y', strtotime($dat->payment_date)),1,0);
            $this->fpdf->Cell($width_cell[2], 8, $dat->department->name,1,0,'L');
            $this->fpdf->Cell($width_cell[3], 8, $dat->description,1,0,'L');
            $this->fpdf->Cell($width_cell[2], 8, $dat->supplier,1,0,'C');
            $this->fpdf->Cell($width_cell[1], 8, $dat->settle_by,1,0,'C');
            $this->fpdf->Cell($width_cell[1], 8, $dat->currency,1,0,'C');
            $this->fpdf->Cell($width_cell[2], 8, $dat->amount,1,1,'R');
            //   $this->fpdf->Cell($width_cell[1], 8, $dat->kilo,1,1,'R');
        }
        $this->fpdf->SetFont('Times', 'B', 11);
        $this->fpdf->SetFillColor(230,230,230);
        $this->fpdf->Cell(229, 8,'Grand Total',1,0,'R',true);
        $this->fpdf->Cell($width_cell[2], 8, $total->total_amount,1,1,'R',true);
        // $this->fpdf->Cell($width_cell[4], 10, 'Fuel Kilo/Liter- '. $average_consume,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $last_qty->qty,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, number_format($average, 2, '.', ''),1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $sum_amt,1,0,'R');
        // $this->fpdf->Cell($width_cell[1], 10, $kilo_total,1,1,'R');
        $this->fpdf->Output();

        exit;
    }
}
