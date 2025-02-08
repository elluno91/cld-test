<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request) {
        $date_check_in = $request->get('date_check_in');
        $date_check_out = $request->get('date_check_out');
        $pax_count = $request->get('pax_count');

        if($date_check_in == null) {
            return response()->json(['error' => 'Date check in is required'], Response::HTTP_BAD_REQUEST);
        }
        if($pax_count == null) {
            return response()->json(['error' => 'Pax count is required'], Response::HTTP_BAD_REQUEST);
        }
        if($date_check_out == null) {
            return response()->json(['error' => 'Date check out is required'], Response::HTTP_BAD_REQUEST);
        }

        $where_room = array(
            'is_deleted' => 0,
        );
        $rooms = Room::with(['reservations' => function($query) use($date_check_in, $date_check_out) {
            return $query->where('date_check_in', '>=', $date_check_in)->where('date_check_out', '<=', $date_check_out)->where('is_deleted', 0)->where('status','issued');
        }])->where($where_room);
        if($pax_count != null) {
            $rooms = $rooms->where('pax_count','>=', $pax_count);
        }
        $rooms = $rooms->get();

        if(!$rooms->isEmpty()) {
            $list_room = array();
            foreach($rooms as $room) {
                $detail_room = array(
                    'id' => $room->id,
                    'number' => $room->number,
                    'floor' => $room->floor,
                    'pax_count' => $room->pax_count,
                    'status' => 'available',
                );
                if(!$room->reservations->isEmpty()) {
                    $detail_room['status'] = "booked";
                }
                $list_room[] = $detail_room;
            }
            $response = array(
                'rooms' => $list_room,
            );
            return response()->json($response, Response::HTTP_OK);
        } else {
            return response()->json(['rooms' => array()], Response::HTTP_OK);
        }
    }
}
