@props(['category'])
@if($category->hasChildren())
  <li class="item parent flex-all">
    <a href="{{ route('products.category', ['category' => $category->slugEn]) }}"
       title="{{ $category->name }}">{{ $category->name }}</a>
      <div class="child-popup-open openSubMenu" data-category-id="{{$category->id}}"></div>
  </li>
@else
  <li class="item">
    <a href="{{ route('products.category', ['category' => $category->slugEn]) }}"
       title="{{ $category->name }}">{{ $category->name }}</a>
  </li>
@endif
