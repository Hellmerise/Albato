<?php

namespace Tests\Support\Config;


enum SuccessPaymentEnum: string
{
    case HEADER = "Thank you for your order!";
    case MESSAGE = "Your order has been dispatched, and will arrive just as fast as the pony can get there!";
}
