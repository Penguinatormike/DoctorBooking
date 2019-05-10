<?php

declare(strict_types=1);

namespace App\Handler;

use App\Helper\BookingHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Helper\Database;
use Zend\Diactoros\Response\JsonResponse;

class ReadBookingHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $db = (new Database())->getConnection();
        $bookingHelper = new BookingHelper($db);
        return $bookingHelper->readBooking();

        $requestParams = $request->getQueryParams();
        $requestType = isset($requestParams['handle']) ? $requestParams['handle'] : '';
        // TODO: split into own Handler files
        switch ($requestType) {
            case 'read':
                return $bookingHelper->readBooking();
                break;
            case 'get':
                return $bookingHelper->getBooking(
                    isset($requestParams['booking_id']) ? $requestParams['booking_id'] : null
                );
                break;
            case 'create':
                return $bookingHelper->createBooking(isset($requestParams['data']) ? $requestParams['data'] : []);
                break;
            case 'update':
                return $bookingHelper->updateBooking(
                    isset($requestParams['booking_id']) ? $requestParams['booking_id'] : null,
                    isset($requestParams['data']) ? $requestParams['data'] : []
                );
                break;
            default:
                return new JsonResponse(['error' => 'type not handled']);
                break;
        }
    }

}
