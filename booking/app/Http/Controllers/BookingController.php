<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingCreateRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->getBookingsJson($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookingCreateRequest $request
     * @return JsonResponse
     */
    public function store(BookingCreateRequest $request): JsonResponse
    {
        $booking = Booking::create($request->all());
        return response()->json([
           "status" => true,
           "booking" => $booking
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->checkExists($id) ?? response()->json([
            "status" => true,
            "booking" => new BookingResource(Booking::where('id', $id)->first())
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->checkExists($id) ?? response()->json([
            "status" => true,
            "message" => "Booking was successfully updated",
            "booking" => Booking::where('id', $id)->update($request->all())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): ?JsonResponse
    {
        $existsCheck = $this->checkExists($id);
        if( !$existsCheck ){
            Booking::destroy($id);
            return response()->json([
                "status" => true,
                "message" => sprintf("Booking with ID = %d was successfully deleted", $id)
            ]);
        } else return $existsCheck;
    }

    public function userBookings(Request $request, int $id)
    {
        if( User::where('id', $id)->count() == 0 ){
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ]);
        } else return $this->getBookingsJson($request, $id);
    }

    private function checkExists(int $id): ?JsonResponse
    {
        return Booking::where('id', $id)->count() ? null : response()->json([
            "status" => false,
            "message" => "Booking with specified ID is not found"
        ]);
    }

    private function getBookingList(Request $request, ?int $userId)
    {
        $limit = intval($request->input('limit')) > 0 ? intval($request->input('limit')) : 10;
        $offset = intval($request->input('limit')) > 0 ? intval($request->input('offset')) : 0;
        $items = Booking::orderBy('id')->limit($limit)->offset($offset);

        if(in_array($request->input('status'), ["confirmed", "not confirmed"])){
            $items->where('status', $request->input('status'));
        }

        if( empty($userId) ){
            if( intval($request->input('user_id')) > 0 ){
                $items->where('user_id', intval($request->input('user_id')));
            }
        } else $items->where('user_id', $userId);

        return $items->get();
    }

    private function getBookingsJson(Request $request, int $id = null): JsonResponse
    {
        return response()->json([
            "status" => true,
            "items" => BookingResource::collection($this->getBookingList($request, $id))
        ]);
    }
}
