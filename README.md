booking application for a doctor using Zend Expressive framework

**Api Example usages:**

GET http://localhost:8080/api/booking/read

GET http://localhost:8080/api/booking/get?booking_id=1

POST http://localhost:8080/api/booking/create

POST http://localhost:8080/api/booking/update

POST http://localhost:8080/api/booking/delete

**Unit test:** vendor/bin/phpunit test/AppTest/BookingHelperTest.php

**Important files:**

src/App/templates/app/home-page.phtml

src/App/Handler/*BookingHandler

src/App/Helper

test/AppTest/Handler/BookingHelperTest.php

config/routes.php