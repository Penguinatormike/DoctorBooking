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
    }
}
