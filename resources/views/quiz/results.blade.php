<x-app-layout>
    <style>
        .move-icon {
            transition: transform 0.3s ease;
        }

        .btn:hover .move-icon {
            transform: translateX(7px); /* Moves the icon to the right */
        }
    </style>
    <div class="container mt-5">
        <h1 class="text-center text-primary">Quiz Results</h1>
        <p class="text-center fs-5">You answered <span class="fw-bold">{{$score}}</span> out of <span class="fw-bold">{{$total}}</span> questions correctly.</p>
        <p class="text-center fs-5"><span class="fw-bold">Your score: </span>{{ number_format($percentage, 2)}}%</p>
    </div>
<div class="container mt-5 bg-white p-5 mb-5 rounded">
    <p class="fs-4 fw-bolder">Thanks for your attempt!</p>
    <a  href="{{ route('profile.History') }}" ><button class=" fs-5 fw-medium btn btn-link">
            You can view your performance here!
            <i class="fas fa-arrow-right move-icon"></i>
        </button></a>
</div>
    <div class="container mt-5 bg-white p-5 mb-5 rounded">
            @foreach($questionsWithOptions as $index => $question)
                <div class="mb-6 border-b pb-4">
                    <h2 class="fs-5 font-semibold mb-3">
                        Q{{ $index + 1 }}: {{ $question['question_text'] }}
                    </h2>
                    <div class="space-y-2 mx-3">
                        @foreach($question['options'] as $optionIndex => $option)
                            @php
                                $isSelected = isset($question['selected_option']) && $option['option_id'] === $question['selected_option']['option_id'];
                                $isCorrect = $option['is_correct'] === 1;

                                $textColor = $isSelected
                                    ? ($isCorrect ? 'text-green-600' : 'text-red-600')
                                    : 'text-gray-800';

                                $fontStyle = $isSelected ? 'font-bold' : '';
                            @endphp

                            <div class="p-2 rounded fs-5 {{ $textColor }} {{ $fontStyle }}">
                                {{ $optionIndex + 1 }}. {{ $option['option_text'] }}

                                @if($isSelected)
                                    <span class="ml-2">
                                    {!! $isCorrect ? '&#10004;' : '&#10006;' !!}
                                </span>
                                @endif
                            </div>
                        @endforeach

                        @if(!isset($question['selected_option']))
                            <p class="fw-medium mt-3 d-flex align-items-center">
                            <span class="me-2">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                            </span>
                                You did not answer this question.
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
    </div>
    <script>

    </script>
</x-app-layout>
