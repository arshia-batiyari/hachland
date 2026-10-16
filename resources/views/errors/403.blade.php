@include('errors.page', [
    'code' => 403,
    'title' => 'دسترسی مجاز نیست',
    'text' => ($exception->getMessage() && ! in_array($exception->getMessage(), ['Forbidden', 'This action is unauthorized.'], true))
        ? $exception->getMessage()
        : 'شما دسترسی لازم برای مشاهده این صفحه را ندارید.',
])
