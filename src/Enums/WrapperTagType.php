<?php
namespace halestar\LaravelDropInCms\Enums;
use halestar\LaravelDropInCms\Traits\BackedEnumHelper;

enum WrapperTagType: string
{
    use BackedEnumHelper;
    case DIV = "div";
    case MAIN = "main";
    case SECTION = "section";
    case APP = "app";
}
