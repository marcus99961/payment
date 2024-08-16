<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        return $this->middleware('auth:api');
    }
    public function index()
    {
        return response()->json(User::with('department')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    // public function update(Request $request, $id)
    // {
    //     if ($request->photo) {
    //         $position = strpos($request->photo, ';');
    //         $sub = substr($request->photo, 0, $position);
    //         $ext = explode('/', $sub)[1];

    //         $name = time().".".$ext;
    //         $img = Image::make($request->photo)->resize(600,600);
    //         $upload_path = 'images/baggages/';
    //         $image_url = $upload_path.$name;
    //         $success=$img->save($image_url);
    //         if ($success) {
                
    //             $img = DB::table('baggage')->where('id',$id)->first();
    //             $image_path = $img->photo;
    //             if($image_path){
    //             $done = unlink($image_path);
    //         }
    //             Baggage::where('id',$id)->update([
    //                 'qty'=> $request->qty,
    //                 'photo'=> $image_url,
    //                 'remark'=>$request->remark,
                   
        
        
    //             ]);
    //          }
    //         }else{
    //         Baggage::where('id',$id)->update([
    //         'qty'=> $request->qty,
    //         'remark'=>$request->remark,


    //     ]);
    // }
    // }
    public function storephoto(Request $request)
    {
        $validateData = $request->validate([
            'photo' => 'required',


           ]);
           if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $img = $image;
            $upload_path = 'images/user/';
            $image_url = $upload_path.$name;
            $img->save($image_url);
            $user = User::where('id',$request->id)->first();
            // if($user->signature){
            //     unlink($user->signature);
            // }
            User::where('id',$request->id)->update([
                'signature' => $image_url
            ]);       

    }
       
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function updatepassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $input = $request->all();
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password'=>  ['required','confirmed','min:6'],
        ])->after(function ($validator) use ($user, $input) {
            if (! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
    public function updatename(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $validateData = $request->validate([
            'name' => 'required|min:6',




        ]);
        $user->update(['name'=>$request->name]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::where('id', $id)->update([
            'name'=>$request->name,
            'role_id'=>$request->role_id,
            'department_id'=>$request->department_id,
            'password' => Hash::make($request->password),

        ]);
        // $this->fpdf = new Fpdf;
        // $this->fpdf->AddPage();
        // $this->fpdf->SetFont('Courier', 'B', 18);
        // $this->fpdf->Cell(50, 25, 'Hello World!');
        // $this->fpdf->Output();
        // exit;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
