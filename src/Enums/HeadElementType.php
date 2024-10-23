<?php
namespace halestar\LaravelDropInCms\Enums;
use halestar\LaravelDropInCms\Traits\BackedEnumHelper;

enum HeadElementType: string
{
    use BackedEnumHelper;
    case Link = "Link";
    case Text = "Text";
}
