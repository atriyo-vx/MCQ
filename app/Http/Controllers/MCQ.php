<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Questions;
use App\Models\Options;
use Redirect;

class MCQ extends Controller
{
    public $Request; //Global - Request Variable

    public function __construct(Request $Request)
    {
        $this->Request = $Request;
    }

    function index(){
        $questions = Questions::all();
        
        return view('index',['questions' => $questions]);
    }
    
    function list(){
        $questions = Questions::all();
        
        return view('list',['questions' => $questions]);
    }

    function add(){
        if($this->Request->isMethod('get')):
            return view('add');
        else:
            
            $question = $this->Request->input('question');
            $point = $this->Request->input('point');
            $option = $this->Request->input('option');
            $correct = $this->Request->input('correct');
            $lastQuestion = Questions::orderBy('ques_num','DESC')->first();
            $ques_num = $lastQuestion->ques_num+1;
            Questions::create(['ques_num' => $ques_num, 'question' => $question, 'point' => $point ]);
            for($i=0; $i < count($option); $i++){
                Options::create(['ques_id' => $ques_num,'answer' => $option[$i], 'correct' => $correct[$i]]);
            }
            return redirect()->route('list');
        endif;
    }

    function edit(){
        if($this->Request->isMethod('get')):
            $question_id = $this->Request->id;
            $questions = Questions::where('id',$question_id)->first();
            $options = Options::where('ques_id',$question_id)->get();
            
            return view('edit',['questions' => $questions , 'options' => $options]);
        else:
            $question_id = $this->Request->input('id');
            $question = $this->Request->input('question');
            $point = $this->Request->input('point');
            $option = $this->Request->input('option');
            $correct = $this->Request->input('correct');
            $option_id = $this->Request->input('option_id');
            Questions::where('id',$question_id)->update(['question' => $question, 'point' => $point]);
            
            for($i=0; $i < count($option_id); $i++){
                Options::where('id',$option_id[$i])->where('ques_id',$question_id)->update(['answer' => $option[$i], 'correct' => $correct[$i]]);
            }
            return redirect()->route('list');

        endif;
    }

    function addoptions(){
        if($this->Request->isMethod('get')):
            $question_id = $this->Request->id;
            $questions = Questions::where('id',$question_id)->first();

            return view('add-options',['questions' => $questions ]);
        else:
            $question_id = $this->Request->input('question_id');
            $option = $this->Request->input('option');
            $correct = $this->Request->input('correct');

            for($i=0; $i < count($option); $i++){
                Options::create(['ques_id' => $question_id,'answer' => $option[$i], 'correct' => $correct[$i]]);
            }
            return redirect()->route('list');
        endif;
    }

    function delete(){
        $question_id = $this->Request->id;
        Questions::destroy($question_id);
        Options::where('ques_id',$question_id)->delete();
        
        return redirect()->route('list');
    }

    function quiz(){
        $totalQuestions = Questions::count();
        $selectedOptions = $this->Request->input('ques');
        $totalPoints = $pointsGained = 0; 
        for($i=1; $i <= $totalQuestions; $i++){
            $totalPoints = $totalPoints + Questions::where('ques_num',$i)->first()->point;
            $pointsGained = Options::where('id',$selectedOptions[$i])->first()->correct=='true'? $pointsGained + Questions::where('ques_num',$i)->first()->point: $pointsGained+0;
        }
        return view('result',['obtained' =>$pointsGained, 'total' =>$totalPoints]);
    }

    public static function getAnswer($question_number){
        return $options = Options::where('ques_id',$question_number)->orderBy('id','ASC')->get();
    }
    
}
