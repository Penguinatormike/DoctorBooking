<?php

namespace App\Helper;

use PDO;
use Zend\Diactoros\Response\JsonResponse;

class BookingHelper
{
    /**
     * Database object
     * @var PDO
     */
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create booking record with $data
     * @param array $data
     * @return JsonResponse
     */
    public function createBooking($data)
    {
        try {
            if (!empty($data['user']) && !empty($data['reason'])) {
                $user =  trim($data['user']);
                // insert user if not exist
                $this->db->prepare(
                    "INSERT IGNORE INTO `user` (`u_name`) VALUES (:user)"
                )->execute([':user' => $user]);
                $stmt = $this->db->prepare(
                    "SELECT `user_id` FROM `user` WHERE `u_name`=:user"
                );
                $stmt->execute([':user' => $user]);
                $userId = $stmt->fetchColumn();

                return new JsonResponse([
                    'created' => (bool)$this->db->prepare(
                        "INSERT INTO `booking` 
                        (`bk_user_id`, `bk_reason`, `bk_start_date`, `bk_end_date`) 
                        VALUES (:user_id, :reason, :start_date, :end_date)"
                    )->execute([
                        ':user_id'    => $userId,
                        ':reason'     => $data['reason'],
                        ':start_date' => isset($data['start_date']) ? $data['start_date'] : time(),
                        ':end_date'   => isset($data['end_date']) ? $data['end_date'] : null
                    ])
                ]);
            }
            return new JsonResponse(["error" => 'Must have user name and reason'], 400);
        } catch (\PDOException $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Get all booking records
     * @return JsonResponse
     */
    public function readBooking()
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
                        `booking_id`,
                        `u_name`, 
                        `bk_reason`, 
                        `bk_start_date`,
                        `bk_end_date`
                    FROM `booking` 
                    JOIN `user` ON `bk_user_id`=`user_id` 
                    ORDER BY `bk_start_date` DESC"
            );
            $stmt->execute();
            return new JsonResponse([
                'bookings' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Get $bookingId record
     * @param $bookingId
     * @return JsonResponse
     */
    public function getBooking($bookingId)
    {
        try {
            if (!$this->checkBookingExists($bookingId)) {
                return new JsonResponse(["error" => "booking does not exists"], 400);
            }
            $stmt = $this->db->prepare(
                "SELECT 
                        `booking_id`,
                        `u_name`, 
                        `bk_reason`, 
                        `bk_start_date`,
                        `bk_end_date`
                    FROM `booking` 
                    JOIN `user` ON `bk_user_id`=`user_id` 
                    WHERE `booking_id`=:booking_id
                    ORDER BY `bk_start_date` DESC"
            );
            $stmt->execute([':booking_id' => $bookingId]);
            return new JsonResponse([
                'bookings' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Update $bookingId record with $data
     * @param int $bookingId
     * @param array $data
     * @return JsonResponse
     */
    public function updateBooking($bookingId, $data)
    {
        try {
            if (!$this->checkBookingExists($bookingId)) {
                return new JsonResponse(["error" => "booking does not exists"], 400);
            }
            if (!empty($data['reason'])) {
                return new JsonResponse(
                    [
                        'updated' => (bool)$this->db->prepare(
                            "UPDATE `booking` 
                            SET `bk_reason`=:reason,
                            `bk_start_date`=:start_date,
                            `bk_end_date`=:end_date
                            WHERE `booking_id`=:booking_id"
                        )->execute([
                            ':booking_id' => $bookingId,
                            ':reason'     => $data['reason'],
                            ':start_date' => isset($data['start_date']) ? $data['start_date'] : 'NULL',
                            ':end_date'   => isset($data['end_date']) ? $data['end_date'] : 'NULL'
                        ])
                    ]
                );
            }
            return new JsonResponse(["error" => 'Must include a reason and booking id'], 400);
        } catch (\PDOException $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Delete $bookingId record
     * @param int $bookingId
     * @return JsonResponse
     */
    public function deleteBooking($bookingId)
    {
        try {
            if (!$this->checkBookingExists($bookingId)) {
                return new JsonResponse(["error" => "booking does not exists"], 400);
            }
            return new JsonResponse(
                [
                    'deleted' => $this->db->prepare(
                        "DELETE FROM `booking` WHERE `booking_id`=:booking_id"
                    )->execute([
                        ':booking_id' => $bookingId
                    ])
                ]
            );
        } catch (\PDOException $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    private function checkBookingExists($bookingId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                `booking_id`
            FROM `booking` 
            WHERE `booking_id`=:booking_id"
        );
        $stmt->execute([':booking_id' => $bookingId]);
        return !empty($stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}
