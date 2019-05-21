booking application for a doctor using Zend Expressive framework

**Api Example usages:**

GET http://localhost:8080/api/booking/read

GET http://localhost:8080/api/booking/get?booking_id=1

POST http://localhost:8080/api/booking/create?data={"user":"sandy","reason":"ihave sick","start_date":123444455,"end_date":124124444}

POST http://localhost:8080/api/booking/update?booking_id=1&data={"reason":"ihave sick2","start_date":123444455,"end_date":124124444}

POST http://localhost:8080/api/booking/delete?booking_id=1

**Unit test:** vendor/bin/phpunit test/AppTest/BookingHelperTest.php

**Important files:**

src/App/Handler/*BookingHandler

src/App/Helper

test/AppTest/Handler/BookingHelperTest.php

config/routes.php