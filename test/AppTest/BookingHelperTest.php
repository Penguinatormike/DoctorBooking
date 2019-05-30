<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Helper\BookingHelper;
use App\Helper\Database;
use PHPUnit\Framework\TestCase;

/**
 * Probably not a good idea to test with actual data.
 * Will need to truncate data before running to ensure tables are in a clean state
 *
 * Class BookingHelperTest
 * @package AppTest\Handler
 */
class BookingHelperTest extends TestCase
{
    /**
     * Test createBooking: insert data (if possible)
     * @param array $data
     * @param string $expected
     * @param string $message
     * @dataProvider createBookingProvider
     */
    public function testCreateBooking($data, $expected, $message)
    {
        $db = (new Database())->getConnection();
        $bk = new BookingHelper($db);
        $response = $bk->createBooking($data);
        $this->assertEquals($expected, array_keys($response->getPayload())[0], $message);
    }

    public function createBookingProvider()
    {
        return [
            [
                [
                    'user' => 'uty',
                    'reason' => 'fghh',
                    'start_date' => 123456446,
                    'end_date' => 123154545
                ],
                'created',
                'Data is all included; insert record'
            ],
            [
                [
                    'reason' => '',
                    'start_date' => 123456446,
                    'end_date' => 123154545
                ],
                'error',
                'no user and blank reason; do not insert'
            ],
            [
                [
                    'user' => 'uty',
                    'start_date' => 123456446,
                    'end_date' => 123154545
                ],
                'error',
                'no reason; do not insert'
            ],
            [
                [],
                'error',
                'nothing included; do not insert'
            ],
        ];
    }

    /**
     * Test readBooking
     */
    public function testReadBooking()
    {
        $db = (new Database())->getConnection();
        $bk = new BookingHelper($db);
        $response = $bk->readBooking();
        $this->assertEquals('bookings', array_keys($response->getPayload())[0], 'test if pulling all data');
    }

    /**
     * Test updateBooking: like testInsertData, update if possible.
     * @dataProvider updateBookingProvider
     * @param int $bookingId
     * @param array $data
     * @param string $expected
     * @param string $message
     */
    public function testUpdateBooking($bookingId, $data, $expected, $message)
    {
        $db = (new Database())->getConnection();
        $bk = new BookingHelper($db);

        // insert dummy record
        $db->query("INSERT IGNORE INTO `user`
            (`user_id`, `u_name`)
            VALUES(1, 'bob')");
        $db->query("INSERT IGNORE INTO `booking`
            (`booking_id`, `bk_user_id`, `bk_reason`, `bk_start_date`, `bk_end_date`)
            VALUES(789789456, 1, 'DERP', '123', '1234')");

        $response = $bk->updateBooking($bookingId, $data);

        $this->assertEquals($expected, array_keys($response->getPayload())[0], $message);
    }

    public function updateBookingProvider()
    {
        return [
            [
                789789456, // unlikely to occur..
                [
                    'reason' => 'dummy',
                    'start_date' => 999,
                    'end_date' => 777
                ],
                'updated',
                'booking id and reason is provided; update'
            ],
            [
                545454545, // unlikely to occur..
                [
                    'reason' => 'dummy',
                    'start_date' => 999,
                    'end_date' => 777
                ],
                'error',
                'booking id provided, but doesnt exist; failed update'
            ],
            [
                789789456,
                [
                    'start_date' => 999,
                    'end_date' => 777
                ],
                'error',
                'no reason; failed update'
            ],
            [
                null,
                [
                    'reason' => 'dummy',
                    'start_date' => 999,
                    'end_date' => 777
                ],
                'error',
                'no booking id; failed update'
            ],
            [
                null,
                [],
                'error',
                'nothing; failed update'
            ],
        ];
    }

    /**
     * Test deleteBooking: like testInsertData, delete if possible.
     * @dataProvider deleteBookingProvider
     * @param int $bookingId
     * @param string $expected
     * @param string $message
     */
    public function testDeleteBooking($bookingId, $expected, $message)
    {
        $db = (new Database())->getConnection();
        $bk = new BookingHelper($db);

        // insert dummy record
        $db->query("INSERT IGNORE INTO `booking`
            (`booking_id`, `bk_user_id`, `bk_reason`, `bk_start_date`, `bk_end_date`)
            VALUES(55555555, 1, 'delete', '123', '1234')");

        $response = $bk->deleteBooking($bookingId);

        $this->assertEquals($expected, array_keys($response->getPayload())[0], $message);
    }

    public function deleteBookingProvider()
    {
        return [
            [
                null,
                'error',
                'booking id not provided; delete failed'
            ],
            [
                54545,
                'error',
                'booking id provided, but doesnt exist; delete failed'
            ],
            [
                55555555, // unlikely to occur..
                'deleted',
                'booking id provided; delete'
            ],
        ];
    }

    /**
     * Test getBooking: like testInsertData, get if possible.
     * @dataProvider getBookingProvider
     * @param int $bookingId
     * @param string $expected
     * @param string $message
     */
    public function testGetBooking($bookingId, $expected, $message)
    {
        $db = (new Database())->getConnection();
        $bk = new BookingHelper($db);

        // insert dummy record
        $db->query("INSERT IGNORE INTO `booking`
            (`booking_id`, `bk_user_id`, `bk_reason`, `bk_start_date`, `bk_end_date`)
            VALUES(55555555, 1, 'delete', '123', '1234')");

        $response = $bk->getBooking($bookingId);

        $this->assertEquals($expected, array_keys($response->getPayload())[0], $message);
    }

    public function getBookingProvider()
    {
        return [
            [
                null,
                'error',
                'booking id not provided; get failed'
            ],
            [
                54545,
                'error',
                'booking id provided, but doesnt exist; get failed'
            ],
            [
                55555555, // unlikely to occur..
                'bookings',
                'booking id provided and record exists; get success'
            ],
            [
                788997, // unlikely to occur..
                'error',
                'booking id provided and record does not exists; get failed'
            ],
        ];
    }
}
