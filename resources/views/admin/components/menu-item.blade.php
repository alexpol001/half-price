<?php
/**
 * @var $item array
 */
$tree = count($item['items']) ? true : false;
?>
@if ($item['visible'])
    <li class="nav-item @if ($tree) has-treeview @if ($item['active']) menu-open @endif @endif">
        <a href="{{$item['path']}}" class="nav-link @if ($item['active']) active @endif">
            <i class="nav-icon {{$item['icon']}}"></i>
            <p>
                {{$item['title']}}
                @if ($tree)
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if ($tree)
            <ul class="nav nav-treeview">
                @foreach($item['items'] as $subItem)
                    @include('admin.components.menu-item', ['item' => $subItem])
                @endforeach
            </ul>
        @endif
    </li>
@endif
