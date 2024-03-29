<?php

namespace App\Http\Controllers;

use Illuminate\Auth\user;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Catagory;

class CatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catagories = Catagory::all();
        return view('admin.catagory.catagory',compact('catagories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        $user = user::create([
            'email' =>$data["email"],
            'password'=>Hash::make($data['password'])
        ]);

        if($user){
            profile::create([
            'user_id' => $user->id,
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // dd($request);

        $category  = catagory::create([

            'title' => $request->title,
            'description' => $request->description,
            'slug' => $request->slug,

        ]);

        $category->parents()->attach($request->parent_id);

        if($category){
            return back()->with('message','Catagory has been added Successfully');
        }
        return back()->with('message','Catagory cannot be added');

        // return back()->with($request-dd());

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Catagory  $catagory
     * @return \Illuminate\Http\Response
     */
    public function show(Catagory $catagory)
    {
        
    }


    public function catagoryTable(){
        $catagory = Catagory::all();
        return view('admin.catagory.index',compact('catagory'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Catagory  $catagory
     * @return \Illuminate\Http\Response
     */
    public function edit(Catagory $catagory)
    {
        $ids = arr::pluck($catagory->parents, 'id');
        $Editcatagory = Catagory::where('id','!=',$catagory->id)->get();
        return view('admin.catagory.catagory',['editcatagory'=> $Editcatagory, 'catagory'=>$catagory, 'parent_ids'=>$ids,]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Catagory  $catagory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catagory $catagory)
    {
        // dd($request);
        $catagory->title = $request->title;
        $catagory->slug = $request->slug;
        $catagory->description = $request->description;

        $catagory->parents()->detach();
        $catagory->parents()->attach($request->parent_id);

        $save = $catagory->save();

        if($save){
            return back()->with('message','Data has been updated');
        }
        else
        {
        return back()->with('message','Sorry data cannot be Added');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Catagory  $catagory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catagory = catagory::withTrashed()->findOrfail($id);
        if($catagory->parents()->detach() && $catagory->forceDelete())
        {
            return back()->with('message','Category Successfully Deleted!');
        }
        else
        {
            return back()->with('message','Error Deleting Record');
        }  
    }



    public function recovery($id)
    {
        $recover = catagory::withTrashed()->findOrfail($id);
        if($recover->restore()){
            return back()->with('message', 'Catagory has been Recovered');
        }
        else{
            return back()->with('message', 'Catagory cannot be recovered');
        }
        // dd($catagory);
    }

    public function Trash(Catagory $catagory)
    {
        if($catagory->delete()){
            return back()->with('message','Catagory has been Trashed Successfully');
        }
        else{
            return back()->with('message', 'Catagory cannot be Trashed');
        }
    }

    public function GetTrash()
    {
        $Trashed = catagory::onlyTrashed()->get();
        return view('admin.catagory.index', compact('Trashed'));
        // dd($Trashed);
    }
}


/*
    ****************************************************************************
    |                   Documentation
    ****************************************************************************
    |1) Variable:
    ****************************************************************************
    |      $Except:
    |      *********************
    |             The except method will return all the key/value pairs in the collection
    |       where the keys in the collection are not in the supplied $keys array.
    |       Internally, this method makes a call to the
    |       "Illuminate\Support\Arr:except($array, $keys)" helper function.
    |
    |
    |
    |
    |
    |
    |
    |
    |
    |
    |
    ****************************************************************************
*/