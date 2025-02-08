<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request) {

        $customer_name = $request->input('customer_name');
        $date_check_in = $request->input('date_check_in');
        $date_check_out = $request->input('date_check_out');
        $booking_code = $request->input('booking_code');

        $customer_id = null;

        if($booking_code == null) {
            $where = array(
                'name' => $customer_name,
            );
            $customer = Customer::where($where)->first();
            if ($customer != null) {
                $customer_id = $customer->id;
            }
        }

        $where_reservation = null;
        if($booking_code != null) {
            $where_reservation = array(
                'code' => $booking_code,
            );
        }
        if($booking_code == null && $customer_id != null) {
            $where_reservation = array(
                'customer_id' => $customer_id,
            );
        }
        $reservations = Reservation::with('customer')->where($where_reservation)
            ->where('status','issued')
            ->where('is_deleted','0');

        if($date_check_in != null) {
            $reservations = $reservations->whereDate('date_check_in', '>=', $date_check_in);
        };
        if($date_check_out != null) {
            $reservations = $reservations->whereDate('date_check_out', '<=', $date_check_out);
        };
        $reservations = $reservations->get();

        if(!$reservations->isEmpty()) {
            $list_reservation = array();
            foreach($reservations as $reservation) {
                $list_reservation[] = array(
                    'code' => $reservation->code,
                    'date_check_in' => date("Y-m-d",strtotime($reservation->date_check_in)),
                    'date_check_out' => date("Y-m-d",strtotime($reservation->date_check_out)),
                    'pax_count' => $reservation->pax_count,
                    'customer' => array(
                        'name' => $reservation->customer->name,
                        'email' => $reservation->customer->phone,
                        'phone' => $reservation->customer->email,
                    ),
                    'room' => array(
                        'floor' => $reservation->room->floor,
                        'room_number' => $reservation->room->number,
                    ),
                );
            }
            return response()->json(['reservation' => $list_reservation], Response::HTTP_OK);
        } else {
            return response()->json(['reservation' => array()], Response::HTTP_OK);
        }
    }
    public function store(Request $request) {
        $customer_name = $request->input('customer_name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $pax_count = $request->input('pax_count');
        $date_check_in = $request->input('date_check_in');
        $date_check_out = $request->input('date_check_out');
        $room_id = $request->input('room_id');

        $rules = [
            'customer_name' => 'required',
            'pax_count' => 'required',
            'date_check_in' => 'required',
            'date_check_out' => 'required',
            'room_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->passes()) {
            //TODO Handle your data
            return response()->json(["error" => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
        }

        $customer_id = null;
        DB::beginTransaction();
        try {
           $where = array(
                'name' => $customer_name,
            );
            if($phone != null) {
                $where['phone'] = $phone;
            }
            if($email != null) {
                $where['email'] = $email;
            }
            $customer = Customer::where($where)->first();
            if($customer == null) {
                $data_insert = array(
                    'name' => $customer_name,
                    'phone' => $phone,
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 'api',
                    'is_deleted' => 0,
                );
                $customer = Customer::create($data_insert);
                $customer_id = $customer->id;
            } else {
                $customer_id = $customer->id;
            }


            if($customer_id != null) {

                $code = $this->generateRandomString(6);

                $where_reservation = array(
                    'room_id' => $room_id,
                );
                $reservation = Reservation::where($where_reservation)->where('date_check_in','>=', $date_check_in)->where('date_check_out','<=', $date_check_out)->where('status','issued')->where('is_deleted','0')->first();
                if($reservation != null) {
                    return response()->json(['error' => 'Room already booked on selected date'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $data_insert = array(
                    'customer_id' => $customer_id,
                    'pax_count' => $pax_count,
                    'room_id' => $room_id,
                    'date_check_in' => $date_check_in,
                    'date_check_out' => $date_check_out,
                    'status' => 'issued',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 'api',
                    'is_deleted' => 0,
                    'code' => $this->generateRandomString(6),
                );
                $reservation = Reservation::create($data_insert);
                if($reservation != null) {
                    $response = array(
                        'booking_code' => $code,
                        'status' => 'issued',
                    );

                    DB::commit();
                    return response()->json($response, Response::HTTP_OK);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to create reservation'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Customer not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(["error" => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }


    }
    public function cancel(Request $request) {
        $customer_name = $request->input('customer_name');
        $booking_code = $request->input('booking_code');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $date_check_in = $request->input('date_check_in');

        $customer_id = null;

        if($booking_code == null) {
            if($customer_name == null && $email == null && $phone == null) {
                return response()->json(['error' => 'Customer name or email or phone cannot be empty'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if($booking_code == null) {
            $where = array(
                'name' => $customer_name,
            );
            if ($phone != null) {
                $where = array(
                    'phone' => $phone,
                );
            }
            if ($email != null) {
                $where = array(
                    'email' => $email,
                );
            }
            $customer = Customer::where($where)->first();
            if ($customer == null) {
                return response()->json(['error' => 'Customer not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $customer_id = $customer->id;
            }
        }

        if($customer_id != null || $booking_code != null) {
            $where_reservation = array(
                'code' => $booking_code,
                'is_deleted' => 0,
                'status' => 'issued'
            );
            if($booking_code == null) {
                $where_reservation = array(
                    'customer_id' => $customer_id,
                    'date_check_in' => $date_check_in,
                    'status' => 'issued'
                );
            }
            $reservation = Reservation::where($where_reservation)->first();
            if($reservation != null) {
                $date_update = array(
                    'status' => 'cancelled',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => 'api',
                );
                $where_update = array(
                    'id' => $reservation->id,
                );
                $reservation->update($date_update,$where_update);
                return response()->json(['status' => 'cancelled'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Reservation not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['error' => 'Customer not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
