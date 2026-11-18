
<?php

use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


// Default welcome page
Route::get('/', function () {
    return view('welcome');
});


// ==================== STUDENT ROUTES ====================

// Display the student creation form
Route::get('/student/create', function () {
    return view('student.create');
});

// Display all students
Route::get('/students', function () {

    $students = Student::all();

    return view('student.index', [
        'students' => $students
    ]);

});

// Display a single student's details
Route::get('/students/{id}', function ($id) {

    $student = Student::findOrFail($id);

    return view('student.show', [
        'student' => $student
    ]);

});

// Display the student edit form
Route::get('/students/{id}/edit', function ($id) {

    $student = Student::findOrFail($id);

    return view('student.edit', [
        'student' => $student
    ]);

});

// Update student information
Route::put('/students/{id}', function (Request $request, $id) {

    $student = Student::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',

        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('students')->ignore($student->id)
        ],

        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'date_of_birth' => 'nullable|date',
    ]);

    $student->update($validated);

    return redirect('/students/' . $student->id)
        ->with('success', 'Student updated successfully!');

});

// Delete a student
Route::delete('/students/{id}', function ($id) {

    $student = Student::findOrFail($id);

    $student->delete();

    return redirect('/students')
        ->with('success', 'Student deleted successfully!');

});

// Save student information
Route::post('/student', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    $student = Student::create($validated);

    return redirect('/students')
        ->with('success', "Student {$student->name} created successfully!");

});


// ==================== COURSE ROUTES ====================

// Display the course creation form
Route::get('/courses/create', function () {
    return view('course.create');
});

// Display all courses
Route::get('/courses', function () {

    $courses = Course::all();

    return view('course.index', [
        'courses' => $courses
    ]);

});

// Display a single course's details
Route::get('/courses/{id}', function ($id) {

    $course = Course::findOrFail($id);

    return view('course.show', [
        'course' => $course
    ]);

});

// Display the course edit form
Route::get('/courses/{id}/edit', function ($id) {

    $course = Course::findOrFail($id);

    return view('course.edit', [
        'course' => $course
    ]);

});

// Store course information
Route::post('/courses', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'duration' => 'required|integer|min:1',
        'fee' => 'required|numeric|min:0',
        'difficulty' => 'required|in:Easy,Medium,Hard',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $course = Course::create($validated);

    return redirect('/courses')
        ->with('success', "Course {$course->name} created successfully!");

});

// ==================== UPDATE COURSE ====================

// Update existing course information
Route::put('/courses/{id}', function (Request $request, $id) {

    $course = Course::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'duration' => 'required|integer|min:1',
        'fee' => 'required|numeric|min:0',
        'difficulty' => 'required|in:Easy,Medium,Hard',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $course->update($validated);

    return redirect('/courses/' . $course->id)
        ->with('success', 'Course updated successfully!');

});


// ==================== DELETE COURSE ====================

// Delete an existing course
Route::delete('/courses/{id}', function ($id) {

    $course = Course::findOrFail($id);

    $course->delete();

    return redirect('/courses')
        ->with('success', 'Course deleted successfully!');

});
