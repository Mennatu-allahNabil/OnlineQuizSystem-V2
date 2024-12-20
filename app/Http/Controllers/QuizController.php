<?php

namespace App\Http\Controllers;


use App\Mail\AnswerMail;
use App\Models\Option;
use App\Models\Question;
use App\Models\PerformanceHistory;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uploadimage;
use App\Traits\CheckFile;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\ServiceProvider as PDF;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;




class QuizController extends Controller
{
    use Uploadimage, CheckFile;
    public function showQuizzesByTopic($id)
    {
        if ($id) {
            $topic = Topic::findOrFail($id);
            $quizzes = $topic->quizzes()->has('questions')->paginate(6);
        } else {
            $topic = (object) ['name' => 'All'];
            $quizzes = Quiz::has('questions')->paginate(6);
        }
        return view('website.quizzes.index', compact('topic', 'quizzes'));
    }

    public function getMonths()
    {
        $months = array_combine(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            range(1, 12)
        );
return $months;
    }

    public function showQuiz(Quiz $quiz)
    {
        $questions = $quiz->questions ;
        $questions->each(function ($question) {
            $question->options = $question->options->shuffle();
        });
        $questions= $questions->shuffle();

//        // Get all questions for the quiz and shuffle them
//        $questions = Question::where('quiz_id', $quiz->id)
//            ->with('options') // Eager load options
//            ->get()
//            ->shuffle();  // Shuffle questions
//
//        // Shuffle options for each question
//        foreach ($questions as $question) {
//            $question->options = $question->options->shuffle();
//        }
        return view('website.quizzes.show', compact('quiz','questions'));

    }

    public function addQuestions(array $QuestionsData, Quiz $quiz,Request $request=null)
    {
        foreach ( $QuestionsData as $question_to_store) {
            $question_data = [
                "question_text" => $question_to_store['text'],
                "question_type" => $question_to_store['type'],
            ];
            if (array_key_exists('image', $question_to_store)) {
                $question_data['image'] = $this->uploadImage($question_to_store, 'image', 'Questions_images');
            }
            $question = $quiz->questions()->create($question_data);
            foreach ($question_to_store['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option,
                    'is_correct' => ($question_to_store['is_correct_number'] - 1) === $index ? 1 : 0,
                ]);
            }
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                "title" => "required|string|unique:quizzes",
                "description" => "required|string",
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                "quiz_type" => "required",
                "time_limit" => "required|integer",
                "topic_id" => "required|integer",
                "questions" => "required|array|min:1",
                "questions.*.text" => "required|string",
                "questions.*.type" => "required|string",
                "questions.*.image" => "nullable|image|mimes:jpeg,png,jpg,gif",
                "questions.*.options" => "required|array|min:2",
                "questions.*.options.*" => "required|string",
                "questions.*.is_correct_number" => "required|integer|min:1|max:4",
                "created_by" => "required|integer",
            ],[
                "title.required" => "The quiz title is required.",
                "title.string" => "The quiz title must be a valid string.",
                "title.unique" => "This quiz title has already been used.",

                "description.required" => "The quiz description is required.",
                "description.string" => "The quiz description must be a valid string.",

                "image.image" => "The image must be an image file.",
                "image.mimes" => "The image must be of type jpeg, png, jpg, or gif.",

                "quiz_type.required" => "Please select the quiz type.",

                "time_limit.required" => "Time limit is required.",
                "time_limit.integer" => "Time limit must be an integer.",

                "topic_id.required" => "Topic ID is required.",
                "topic_id.integer" => "Topic ID must be a valid integer.",

                "questions.required" => "You must add at least one question.",
                "questions.array" => "Questions must be an array.",
                "questions.min" => "You must add at least one question.",

                "questions.*.text.required" => "Each question must have text.",
                "questions.*.text.string" => "Question text must be a valid string.",

                "questions.*.type.required" => "Each question must have a type.",
                "questions.*.type.string" => "Question type must be a valid string.",

                "questions.*.image.image" => "Question image must be an image file.",
                "questions.*.image.mimes" => "Question image must be of type jpeg, png, jpg, or gif.",

                "questions.*.options.required" => "Each question must have at least two options.",
                "questions.*.options.array" => "Options must be an array.",
                "questions.*.options.min" => "Each question must have at least two options.",

                "questions.*.options.*.required" => "Each option is required.",
                "questions.*.options.*.string" => "Each option must be a valid string.",

                "questions.*.is_correct_number.required" => "You must select the correct option for each question.",
                "questions.*.is_correct_number.integer" => "The correct option must be a valid integer.",
                "questions.*.is_correct_number.min" => "The correct option must be between 1 and 4.",
                "questions.*.is_correct_number.max" => "The correct option must be between 1 and 4.",

            ]);
            DB::transaction(function () use ($request) {
                $quiz_data = [
                    "title" => $request->title,
                    "description" => $request->description,
                    "quiz_type" => $request->quiz_type,
                    "time_limit" => $request->time_limit,
                    "created_by" => $request->created_by,
                    "topic_id" => $request->topic_id,
                ];
                if ($this->checkFile($request, 'image')) {
                    $quiz_data['image'] = $this->uploadImage($request, 'image', 'Quizzes_images');;
                }

                $quiz = Quiz::create($quiz_data);
                $this->addQuestions($request["questions"],$quiz,$request);

            });
            alert::success("Success!","Quiz Added Successfully");
            return redirect()->route("quiz.index");
        } catch (\Exception $e) {
            // Handle any other exceptions
            toast($e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    public function submitQuiz(Request $request)
    {
        $submittedAnswers = $request->input('answers', []);
        $userId = auth()->id();
        $quizId = $request->input('quiz_id');
        $score = 0;

        $questions = Question::where('quiz_id', $quizId)->get();
        $totalQuestions = $questions->count();

        foreach ($submittedAnswers as $questionId => $selectedOptionId) {
            $correctOption = Option::where('question_id', $questionId)
                ->where('is_correct', 1)
                ->first();

            if (!$correctOption) {
                continue;
            }

            if ($correctOption->id == $selectedOptionId) {
                $score++;
            }

            Answer::create([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                'option_id' => $selectedOptionId,
                'is_correct' => $correctOption->id == $selectedOptionId ? 1 : 0,
                'attempt_number' => 1,
            ]);
        }

        $percentageScore = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;

        $latestAttempt = PerformanceHistory::where('user_id', auth()->id())
            ->where('quiz_id', $quizId)
            ->max('attempt_number');
        $newAttemptNumber = $latestAttempt ? $latestAttempt + 1 : 1;

        PerformanceHistory::create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' =>  $percentageScore,
            'attempt_number' => $newAttemptNumber,
        ]);

        $quiz = Quiz::select('title', 'quiz_type')->find($quizId);

        if ($quiz->quiz_type == "once_attempt") {
            $useranswer = Answer::where('user_id', auth()->id())
                ->where('quiz_id', $quizId)
                ->get();
            Mail::to(auth()->user()->email)->send(new AnswerMail($questions, $useranswer, $quiz->title));
        }

        return redirect()->route('score.view', [
            'score' => $score,
            'total' => $totalQuestions,
            'percentage' => $percentageScore
        ]);
    }
    public function showResults(Request $request)
    {
//    dd($request);
        $userId = auth()->id();


        $userResults = PerformanceHistory::select(
            'performance_histories.user_id',
            'users.name',
            'quizzes.title',
            'quizzes.quiz_type',
            'performance_histories.score',
            'performance_histories.attempt_number',

        )
            ->join('users', 'performance_histories.user_id', '=', 'users.id')
            ->join('quizzes', 'performance_histories.quiz_id', '=', 'quizzes.id',)
            ->where('performance_histories.user_id', $userId)
            ->get();

        $latestAttempt = PerformanceHistory::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->first();
        $quiz_id=$latestAttempt->quiz_id;
        $quiz_type=Quiz::select("quiz_type")->where("id",$quiz_id)->first();
        $user_id=$latestAttempt->user_id;
        $userAnswers = Answer::where('user_id', $user_id) // replace 8 with auth()->id() for dynamic user
            ->where('quiz_id', $quiz_id) // replace 41 with the quiz ID
            ->where('created_at', $latestAttempt->created_at)
            ->get();

//        dd($userAnswers);
//        dd($quiz_type);
        $questionsWithOptions = [];

//        foreach ($userAnswers as $userAnswer) {
            // Fetch the question for this answer
            $questions = Question::where('quiz_id', $quiz_id)->get();

            foreach ($questions as $question) {
                // Find the user's answer for this question
                $userAnswer = $userAnswers->where('question_id', $question->id)->first();

                // Fetch all options for this question
                $options = $question->options; // Assuming the options relationship is defined on the Question model

                // Find the selected option based on the user answer, if it exists
                $selectedOption = $userAnswer
                    ? $options->where('id', $userAnswer->option_id)->first()
                    : null;

                // Store the question with its options and user answer details
                $questionsWithOptions[] = [
                    'question_id' => $question->id,
                    'question_text' => $question->question_text,
                    'selected_option' => $selectedOption
                        ? [
                            'option_id' => $selectedOption->id,
                            'option_text' => $selectedOption->option_text,
                            'is_correct' => $selectedOption->is_correct // Check if the selected option is correct
                        ]
                        : null,
                    'options' => $options->map(function ($option) {
                        return [
                            'option_id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct' => $option->is_correct
                        ];
                    })
                ];
            }
//        }

// Example output to check the result
//        dd($questionsWithOptions);

        $score = $request->input('score');
        $total = $request->input('total');
        $percentage = $request->input('percentage');

        return view('quiz.results', compact('score', 'total', 'percentage', 'userResults',"quiz_type","questionsWithOptions"));
    }


    public function showdata(Request $request)
    {
        // الحصول على معرف المستخدم الحالي
        $userId = auth()->id();

        // جلب نتائج الأداء
        $results = PerformanceHistory::select(
            'performance_histories.user_id',
            'users.name',
            'performance_histories.score',
            'quizzes.title as quizTitle'
        )
        ->join('users', 'performance_histories.user_id', '=', 'users.id')
        ->join('quizzes', 'performance_histories.quiz_id', '=', 'quizzes.id')
        ->get();

        // جمع البيانات للرسم البياني
        $quizTitles = Quiz::pluck('title')->toArray(); // سحب عناوين الاختبارات من قاعدة البيانات
        $userCounts = [];

        // احصاء عدد المستخدمين الذين قاموا بالاختبار
        foreach ($quizTitles as $title) {
            $quizId = Quiz::where('title', $title)->value('id'); // الحصول على معرف الاختبار
            $userCounts[] = PerformanceHistory::where('quiz_id', $quizId)->count(); // استخدام quiz_id
        }

        // حساب المتوسط ونسبة النجاح
        $averageScore = PerformanceHistory::avg('score') ?? 0; // التأكد من أن المتوسط قابل للاستخدام
        $passPercentage = PerformanceHistory::where('score', '>=', 50)->count() / (PerformanceHistory::count() ?: 1) * 100; // تجنب القسمة على صفر

        // إرجاع النتائج إلى العرض
        return view('quiz.showresults', compact('results', 'quizTitles', 'userCounts', 'averageScore', 'passPercentage'));
    }



    // here i show quiz and delete
    public function index()
    {
        $quizzes = Quiz::with(['creator:id,email'])->get();
        return view('dashboard.quiz.index', compact('quizzes'));
    }
    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('dashboard.quiz.show', compact('quiz'));
    }
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quiz.index')->with('success', 'Quiz deleted successfully.');
    }
    public function update(Request $request)
    {
        try{
            $rules = [
                "description" => "required",
                "quiz_type" => "required",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg",
                "time_limit" => "required|integer|min:0",
                "topic_id" => "required",
            ];
            $quiz = Quiz::find($request->id);
            $rules["title"] = ($quiz->title !== $request->title) ? "required|unique:quizzes,title" : "required";
            if(isset($request->questions)){
                $rules["questions"] = "required|array";
                $rules["questions.*.text" ]= "required|string";
                $rules["questions.*.type" ]= "required|string";
                $rules["questions.*.image"] = "nullable|image|mimes:jpeg,png,jpg,gif";
                $rules["questions.*.options"] = "required|array|min:2";
                $rules["questions.*.options.*"] = "required|string";
                $rules["questions.*.is_correct_number" ]= "required|integer|min:1|max:4";
            }
            $validatedData = $request->validate($rules);
            DB::transaction(function () use ($quiz, $validatedData, $request) {
                $quiz->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'quiz_type' => $validatedData['quiz_type'],
                    'time_limit' => $validatedData['time_limit'],
                    'topic_id' => $validatedData['topic_id'],
                ]);
                if ($this->checkFile($request,"image")) {
                    $quiz->image = $this->uploadImage($request, 'image', 'Quizzes_images');
                    $quiz->save();
                }
                if(isset($validatedData['questions'])){
                    $this->addQuestions($validatedData["questions"],$quiz,$request);
                }
            });
            alert::success("Success!","Quiz Updated Successfully");
            return redirect()->back();
        }catch(\Exception $e){
            alert::error("Failed!",$e->getMessage());
            return redirect()->back();
        }
    }
    public function showQuizzesByTopicForAdmin(Topic $topic)
    {
        $quizzes=$topic->quizzes;
        return view('Dashboard.quiz.index', compact( 'quizzes'));

    }
    public function showUsersForQuizForAdmin(Quiz $quiz)
    {
        $months = $this->getMonths();
        $participants = Quiz::with(['performances' => function ($query) {
            $query->select('id', 'user_id', 'quiz_id','created_at' ,"score");
        }, 'performances.user' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
            ->find($quiz->id)
            ->performances
            ->map(function ($performance) {
                $performance->created_at_edited = Carbon::parse($performance->created_at)->format('M j, Y \a\t H:i');
                $performance->user_name = $performance->user->name;
                return $performance;
            })
            ->sortByDesc('created_at');
        return view("Dashboard.quiz.participants",compact("participants","quiz","months"));
    }
    public function showUsersForQuizForAdminByMonth(Quiz $quiz,$month)
    {
        $months = $this->getMonths();
        $participants = Quiz::with([
            'performances' => function ($query) use ($month) {
            $query->select('id', 'user_id', 'quiz_id','created_at')
                ->whereMonth('created_at', $month);
            },
            'performances.user' => function ($query) {
            $query->select('id', 'name', 'email');
            }
            ])->find($quiz->id)
            ->performances
            ->map(function ($performance) {
                $performance->created_at_edited = Carbon::parse($performance->created_at)->format('M j, Y \a\t H:i');
                $performance->user_name = $performance->user->name;
                return $performance;
            })
            ->sortByDesc('created_at');
        return view("Dashboard.quiz.participants",compact("participants","quiz","months"));
    }


    //reports

    public function generatePdf($quizId)
{
    // Check if the user is authenticated
    if (!auth()->check()) {
        return response()->json(['error' => 'User not authenticated.'], 401);
    }

    // Fetch the quiz title
    $quiz = Quiz::findOrFail($quizId);

    // Fetch the results: User name and score for the quiz
    $results = PerformanceHistory::select('performance_histories.user_id', 'users.email', 'performance_histories.score',"performance_histories.created_at")
        ->join('users', 'performance_histories.user_id', '=', 'users.id')
        ->where('performance_histories.quiz_id', $quizId) // Filter by the quiz ID
        ->get();
    $results = $results->map(function ($result) {
        $result->created_at_formatted = Carbon::parse($result->created_at)->format('M j, Y \a\t H:i');
        return $result;
    });

    $passPercentage = PerformanceHistory::where('quiz_id', $quizId)->where('score', '>=', 50)->count() / (PerformanceHistory::count() ?: 1) * 100; // تجنب القسمة على صفر

    // Prepare data for the PDF
    $data = [
        "passPercent"=>$passPercentage,
        'quizTitle' => $quiz->title,
        'results' => $results, // Passing the results to the view
    ];

    // Create a new PDF instance
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('pdf.quiz_report', $data);

    // Return the generated PDF for download
    return $pdf->download('quiz_report.pdf');
}





}
