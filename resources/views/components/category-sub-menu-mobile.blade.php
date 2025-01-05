@props(['category'])
@foreach($productCategories as $category)
  @if($category->hasChildren())
    <div class="head-catalog-container subMenu for-mob" data-modal-category="{{$category->id}}">
      <div class="head-catalog-close button"><i class="far fa-times"></i></div>
      <div class="main_category_wrapper">
        <div class="back_to_main_category button" data-category-id="{{$category->id}}"><i class="fas fa-chevron-left"></i></div>
        {{ $category->name }}
      </div>
      <ul class="menu">
        @foreach($category->children as $childCategory)
          @if($childCategory->hasChildren())
            <li class="item">
              <a href="{{ route('products.category', ['category' => $category->slugEn, 'subcategory'=>$childCategory->slugEn]) }}" title="{{$childCategory->name}}">{{$childCategory->name}}</a>
            </li>
            @foreach($childCategory->children as $childChildCategory)
              <li class="item children">
                <a href="{{ route('products.category', ['category' => $category->slugEn, 'subcategory'=>$childCategory->slugEn, 'subsubcategory'=>$childChildCategory->slugEn]) }}" title="{{$childChildCategory->name}}">{{$childChildCategory->name}}</a>
              </li>
            @endforeach
          @else
            <li class="item">
              <a href="{{ route('products.category', ['category' => $category->slugEn, 'subcategory'=>$childCategory->slugEn]) }}" title="{{$childCategory->name}}">{{$childCategory->name}}</a>
            </li>
          @endif
        @endforeach
      </ul>
    </div>
  @endif
@endforeach
