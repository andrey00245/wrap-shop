@props(['category'])
@if($category->hasChildren())
    <li class="item parent flex-all">
        <a href="{{ route('products.category', ['category' => $category->slugEn]) }}" title="{{ $category->name }}">{{ $category->name }}</a>
        @if($category->hasChildren())
            <div class="child-popup-open open"></div>
            <div class="child-popup" style="display: none">
                <ul class="child-menu">
                    @foreach($category->children as $childCategory)
                        <li class="item parrent flex-all">
                            <a href="{{ route('products.category', ['category' => $category->slugEn, 'subcategory' => $childCategory->slugEn]) }}" title="{{ $childCategory->name }}">{{ $childCategory->name }}</a>
                        @if($childCategory->haschildren())
                                <div class="child-popup-open"></div>
                                <div class="child-popup">
                                    <ul class="child-menu">
                                        @foreach($childCategory->children as $childChildCategory)
                                            <li>
                                                <a href="{{ route('products.category', ['category' => $category->slugEn, 'subcategory' => $childCategory->slugEn, 'subsubcategory' => $childChildCategory->slugEn]) }}" title="{{ $childCategory->name }}">{{ $childCategory->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </li>
@else
    <li class="item">
        <a href="{{ route('products.category', ['category' => $category->slugEn]) }}" title="{{ $category->name }}">{{ $category->name }}</a>
    </li>
@endif
