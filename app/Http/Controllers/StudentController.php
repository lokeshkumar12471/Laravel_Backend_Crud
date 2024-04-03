<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    public function index(){

    $student =Student::get();
    if(!$student){
         return response()->json([
    'message'=>'product not successfully displayed',
    'status'=>404
    ]);
    }
    return response()->json([
     'student'=>$student,
    'message'=>'product successfully displayed',
    'status'=>200
    ]);
    }

    public function store(Request $request){
     $validator = Validator::make($request->all(),[
        'name'=>'required',
        'image'=>'required',
        'email'=>'required|unique:students',
        'description'=>'required',
        'mobile'=>'required|numeric',
      ]);

      if($validator->fails()){
        return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'status' => 422,
            ], 422);
      }

    try{
        $student = new Student();
        $student->name=$request->name;
        $student->description=$request->description;
        $student->email=$request->email;
        $student->mobile=$request->mobile;
       if($request->hasFile('image'))
      $file=$request->file('image');
      $extension=$file->getClientOriginalExtension();
      $filename=time().'.'.$extension;
      $file->move('upload/images',$filename);
      $student->image=$filename;
     $student->save();
      return response()->json([
        'message'=>'Product Was Successfully Stored',
         'status'=>200,
      ]);
    }catch(\Exception $e){
          return response()->json([
        'message'=>'Product Was Not Successfully Stored',
         'status'=>404,
      ]);
    }
}

public function show($id){
    $student= Student::find($id);
    if(!$student){
        return response()->json([
'message'=>'something went wrong data was not display',
'status'=>404,
        ]);
    }

     return response()->json([
'student'=>$student,
'message'=>'student details displaying successfully',
'status'=>200,
        ]);
}

public function update(Request $request, $id){

 try{
    $student= Student::find($id);
    $student->name=$request->name;
    $student->description=$request->description;
     $student->email=$request->email;
    $student->mobile=$request->mobile;
    if($request->hasFile('image')){
        $file=$request->file('image');
        $extension=$file->getClientOriginalExtension();
        $filename=time().'.'.$extension;
        $file->move('upload/images',$filename);
        $student->image=$filename;
    }
    $student->update();
    return response()->json([
        'message'=>'student details successfully updated',
        'status'=>200,
    ]);
 }catch(error){
    return response()->json(['message'=>'Error in updating student']);
 }
}

public function delete($id){
$student = Student::find($id);
if(!$student){
    return response()->json([
    'message'=> 'Student Record Not Deleted',
    'status'=>404,
     ]);
}
    $student->delete();
     return response()->json([
    'message'=> 'Student Record Deleted Successfully',
    'status'=>200,
     ]);
}

}