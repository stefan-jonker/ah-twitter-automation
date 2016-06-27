<?php
  
namespace App\Http\Controllers;
  
use App\Tweet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class TweetController extends Controller{
  
  
    public function index(){
  
        $Tweets  = Tweet::all();
  
        return response()->json($Tweets);
  
    }
  
    public function getTweet($id){
  
        $Tweet  = Tweet::find($id);
  
        return response()->json($Tweet);
    }
  
    public function createTweet(Request $request){
  
        $Tweet = Tweet::create($request->all());
  
        return response()->json($Tweet);
  
    }
  
    public function deleteTweet($id){
        $Tweet  = Tweet::find($id);
        $Tweet->delete();
 
        return response()->json('deleted');
    }
  
    public function updateTweet(Request $request,$id){
        $Tweet  = Tweet::find($id);
        $Tweet->tweet = $request->input('tweet');
        $Tweet->time = $request->input('time');
        $Tweet->save();
  
        return response()->json($Tweet);
    } 
}

