<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-header {{ $item['class'] ?? '' }}" style="font-size: 15px; padding: 10px 10px">

    {{ is_string($item) ? $item : $item['header'] }}

</li>
