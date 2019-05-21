<?php

declare(strict_types=1);

namespace App\Handler;

use App\Helper\BookingHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Helper\Database;

class DeleteBookingHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $bookingHelper = new BookingHelper((new Database())->getConnection());
        $requestBody = $request->getParsedBody();
        return $bookingHelper->deleteBooking(
            isset($requestBody['booking_id']) ? $requestBody['booking_id'] : null
        );
    }

}
