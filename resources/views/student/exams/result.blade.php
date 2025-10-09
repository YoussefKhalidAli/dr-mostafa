@extends('layouts.student')

@section('content')
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
            نتيجة الامتحان: {{ $exam->title }}
        </h1>

        <div class="flex items-center justify-between mb-6">
            <p class="text-lg text-gray-700">
                درجتك: 
                <span class="font-bold text-green-600">{{ $result->student_degree }}</span>
                / {{ $exam->total_degree }}
            </p>
            <p class="text-sm text-gray-500">
                تم التسليم في {{ $result->created_at->translatedFormat('l j F Y - H:i') }}
            </p>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4">مراجعة الأسئلة</h2>

        @foreach($questions as $item)
            @php
                $question = $item['question'];
                $answer = $item['answer'];
                $chosenOption = $item['chosenOption'];
                $correctOption = $item['correctOption'];
            @endphp

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                {{-- عنوان السؤال --}}
                <h3 class="font-bold text-gray-800 mb-2">
                    {{ $loop->iteration }}. {{ $question->title }}
                </h3>

                {{-- لو السؤال له اختيارات --}}
                @if($question->options->count())
                    <ul class="space-y-2">
                        @foreach($question->options as $option)
                            <li class="flex items-center">
                                {{-- الدائرة الملونة --}}
                                <i class="fas fa-circle ml-2 text-xs
                                    @if($option->id == $correctOption?->id) text-green-500
                                    @elseif($option->id == $chosenOption?->id) text-red-500
                                    @else text-gray-400 @endif">
                                </i>

                                {{-- نص الإجابة --}}
                                <span class="
                                    @if($option->id == $correctOption?->id) font-bold text-green-600
                                    @elseif($option->id == $chosenOption?->id) text-red-500
                                    @endif
                                ">
                                    {{ $option->title }}
                                </span>

                                {{-- توضيح إذا كانت هذه إجابة الطالب --}}
                                @if($option->id == $chosenOption?->id)
                                    <span class="ml-2 text-sm italic text-gray-500">(إجابتك)</span>
                                @endif

                                {{-- توضيح إذا كانت هذه الإجابة الصحيحة --}}
                                @if($option->id == $correctOption?->id)
                                    <span class="ml-2 text-sm italic text-green-600">(الإجابة الصحيحة)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{-- في حالة السؤال مش اختياري --}}
                    <p class="text-gray-600">
                        إجابتك: {{ $answer?->text_answer ?? 'لم تتم الإجابة' }}
                    </p>
                @endif

                {{-- تنبيه في حالة عدم اختيار إجابة --}}
                @if(!$chosenOption && !$answer?->text_answer)
                    <p class="text-red-500 text-sm mt-2">❌ لم يتم اختيار إجابة لهذا السؤال</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
