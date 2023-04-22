<?php

    namespace App\Enums;

    enum OrderStatusEnum:int{
        case pending  = 0;
        case allowed  = 1;
        case rejected = 2;
    }