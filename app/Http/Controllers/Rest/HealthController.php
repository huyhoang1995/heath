<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diary;
use App\Models\ExerciseRecord;
use App\Models\MealHistory;
use Illuminate\Support\Facades\Validator; // Add this line to import the Validator class

class HealthController extends Controller
{
    private $diary;
    private $exerciseRecord;
    private $mealHistory;

    public function __construct(Diary $diary, MealHistory $mealHistory, ExerciseRecord $exerciseRecord)
    {
        $this->diary = $diary;
        $this->mealHistory = $mealHistory;
        $this->exerciseRecord = $exerciseRecord;
    }
    //Diary
    public function getListDiary(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'perPage' => 'required',
        ], [
            'perPage.required' => 'Số phần tử trên trang không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        $perPage = $request->input('perPage', 10); // Default to 10 posts per page, you can adjust this value
        $listDiary = $this->diary->paginate($perPage);
        return response()->json(['diary' => $listDiary], 200);
    }

    public function createDiary(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required',
        ], [
            'user_id.required' => 'Mã người dùng không được bỏ trống',
            'title.required' => 'Tiêu đề trang không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $newId = $this->diary->insertGetId([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'content' => $request->content,
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);


        return response()->json(['status' => true, 'id' => $newId]);
    }

    public function updateDiary(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required',
        ], [
            'user_id.required' => 'Mã người dùng không được bỏ trống',
            'title.required' => 'Tiêu đề trang không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $diaryInfo = $this->diary->find($id);
        if (!$diaryInfo) {
            return response()->json(['status' => false, 'message' => 'Diary not found'], 404);
        }

        $diaryInfo->title = $request->password;
        $diaryInfo->content = $request->phone;
        $diaryInfo->updated_at = now(); // You can use the now() function to get the current timestamp

        // Save the changes to the database
        $diaryInfo->save();

        return response()->json(['status' => true, 'message' => 'Diary updated successfully']);
    }

    public function deleteDiary( $id)
    {
        // Validate the request, checking if the ID is present
        $validate = Validator::make(['id' => $id], [
            'id' => 'required|exists:diaries,id',
        ], [
            'id.required' => 'ID không được bỏ trống',
            'id.exists' => 'ID không tồn tại',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        // Find the diary entry by ID
        $diaryInfo = $this->diary->find($id);

        if (!$diaryInfo) {
            return response()->json(['status' => false, 'message' => 'Diary not found'], 404);
        }

        // Delete the diary entry
        $diaryInfo->delete();

        return response()->json(['status' => true, 'message' => 'Diary deleted successfully']);
    }


    // meal history

    public function getListHistoryMeal(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'perPage' => 'required',
        ], [
            'perPage.required' => 'Số phần tử trên trang không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $perPage = $request->input('perPage', 10); // Default to 10 posts per page, you can adjust this value
        $listHistoryMeal = $this->mealHistory->paginate($perPage);
        return response()->json(['historyMeal' => $listHistoryMeal], 200);
    }

    public function createHistoryMeal(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'description' => 'required',
            'images' => 'required',
            'category_type' => 'required',
        ], [
            'user_id.required' => 'Mã người dùng không được bỏ trống',
            'description.required' => 'Mô t ả không được bỏ trống',
            'images.required' => 'Ảnh không được bỏ trống',
            'category_type.required' => 'Chuyên mục không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $newId = $this->mealHistory->insertGetId([
            'user_id' => $request->user_id,
            'description' => $request->description,
            'images' => $request->images,
            'category_type' => $request->category_type,
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);


        return response()->json(['status' => true, 'id' => $newId]);
    }
    public function updateHistoryMeal(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'description' => 'required',
            'images' => 'required',
            'category_type' => 'required',
        ], [
            'description.required' => 'Mô t ả không được bỏ trống',
            'images.required' => 'Ảnh không được bỏ trống',
            'category_type.required' => 'Chuyên mục không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $mealHistoryInfo = $this->mealHistory->find($id);
        if (!$mealHistoryInfo) {
            return response()->json(['status' => false, 'message' => 'Meal history not found'], 404);
        }
        $mealHistoryInfo->description = $request->phone;
        $mealHistoryInfo->category_type = $request->category_type;
        $mealHistoryInfo->updated_at = now(); // You can use the now() function to get the current timestamp

        // Save the changes to the database
        $mealHistoryInfo->save();

        return response()->json(['status' => true, 'message' => 'Meal history updated successfully']);
    }

    public function deleteHistoryMeal(Request $request, $id)
    {
        // Validate the request, checking if the ID is present
        $validate = Validator::make(['id' => $id], [
            'id' => 'required|exists:diaries,id',
        ], [
            'id.required' => 'ID không được bỏ trống',
            'id.exists' => 'ID không tồn tại',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        // Find the diary entry by ID
        $mealHistoryInfo = $this->mealHistory->find($id);

        if (!$mealHistoryInfo) {
            return response()->json(['status' => false, 'message' => 'Meal history not found'], 404);
        }

        // Delete the diary entry
        $mealHistoryInfo->delete();

        return response()->json(['status' => true, 'message' => 'Meal history deleted successfully']);
    }

    // exercise record

    public function getListExerciseRecord(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'perPage' => 'required',
        ], [
            'perPage.required' => 'Số phần tử trên trang không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        $perPage = $request->input('perPage', 10); // Default to 10 posts per page, you can adjust this value
        $listDiary = $this->exerciseRecord->paginate($perPage);
        return response()->json(['exerciseRecord' => $listDiary], 200);
    }

    public function createExerciseRecord(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'duration' => 'required',
            'content' => 'required',
            'calories' => 'required',
        ], [
            'user_id.required' => 'Mã người dùng không được bỏ trống',
            'title.required' => 'Tiêu đề trang không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
            'calories.required' => 'calories không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $newId = $this->exerciseRecord->insertGetId([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'content' => $request->content,
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);


        return response()->json(['status' => true, 'id' => $newId]);
    }

    public function updateExerciseRecord(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'duration' => 'required',
            'content' => 'required',
            'calories' => 'required',
        ], [
            'title.required' => 'Tiêu đề trang không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
            'calories.required' => 'calories không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $exerciseRecordInfo = $this->exerciseRecord->find($id);
        if (!$exerciseRecordInfo) {
            return response()->json(['status' => false, 'message' => 'Record not found'], 404);
        }

        $exerciseRecordInfo->title = $request->password;
        $exerciseRecordInfo->content = $request->phone;
        $exerciseRecordInfo->updated_at = now(); // You can use the now() function to get the current timestamp

        // Save the changes to the database
        $exerciseRecordInfo->save();

        return response()->json(['status' => true, 'message' => 'Record updated successfully']);
    }
    public function deleteExerciseRecord( $id)
    {
        // Validate the request, checking if the ID is present
        $validate = Validator::make(['id' => $id], [
            'id' => 'required|exists:diaries,id',
        ], [
            'id.required' => 'ID không được bỏ trống',
            'id.exists' => 'ID không tồn tại',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        // Find the diary entry by ID
        $exerciseRecordInfo = $this->exerciseRecord->find($id);

        if (!$exerciseRecordInfo) {
            return response()->json(['status' => false, 'message' => 'Record not found'], 404);
        }

        // Delete the diary entry
        $exerciseRecordInfo->delete();

        return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
    }
}
