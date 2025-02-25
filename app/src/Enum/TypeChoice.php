<?php
namespace App\Enum;

enum TypeChoice: string
{
    case TEXT = 'text';
    case MULTIPLE_CHOICE = 'multiple_choice';
    case SLIDER = 'slider';
}
