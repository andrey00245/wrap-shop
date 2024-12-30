@props(['category'])
@if($category->hasChildren())
  <li class="item parrent flex-all">
    <a href="{{ route('products.category', ['category' => $category->slugEn]) }}"
       title="{{ $category->name }}">
      <figure>
        <img loading="lazy" src="{{$category->getImage()}}" srcset="{{$category->getImage()}}"
             alt="{{$category->name}}" title="{{$category->name}}" width="180" height="140">
      </figure>
      {{ $category->name }}
    </a>
    <div class="child-popup-open openSubMenu" data-category-id="{{$category->id}}"></div>
  </li>
@else
  <li class="item">
    <figure><img loading="lazy" src="{{$category->getImage()}}" srcset="{{$category->getImage()}}"
                 alt="{{$category->name}}" title="{{$category->name}}" width="180" height="140"></figure>
    <a href="{{ route('products.category', ['category' => $category->slugEn]) }}"
       title="{{ $category->name }}">{{ $category->name }}</a>
  </li>
@endif
