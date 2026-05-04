<section class="popup-right general-popup home-review-popup" id="home-review-popup" data-step="1">
    <div class="popup-review-form form-horizontal">
        <div class="title">{{ __('popup.reviews_popup.leave_review') }}</div>
        <form method="post"
              action="{{ route('reviews.store') }}"
              id="home-review-form"
              novalidate
              enctype="multipart/form-data">
            @csrf
            <div class="form-group required">
                <label class="control-label" for="home-review-name">{{ __('popup.reviews_popup.you_name') }}:</label>
                <input id="home-review-name"
                       type="text"
                       name="name"
                       class="form-control"
                       required
                       maxlength="255"
                       value="{{ auth()->check() ? auth()->user()->name : '' }}"
                       placeholder="{{ __('popup.reviews_popup.enter_name') }}">
            </div>

            <div class="form-group required">
                <label class="control-label" for="home-review-text">{{ __('popup.reviews_popup.you_review') }}:</label>
                <textarea id="home-review-text"
                          name="text"
                          class="form-control"
                          rows="5"
                          required
                          maxlength="1000"
                          placeholder="{{ __('popup.reviews_popup.enter_review') }}"></textarea>
            </div>

            <div class="form-group required">
                <div class="control-label">{{ __('popup.reviews_popup.mark') }}</div>
                <div class="form-radio">
                    @for ($i = 1; $i <= 5; $i++)
                        <label for="home-rating-{{ $i }}">
                                <input id="home-rating-{{ $i }}" type="radio" name="rating" value="{{ $i }}">
                            <span>
                                @switch($i)
                                    @case(1) {{ __('popup.reviews_popup.mark_could_be_better') }} @break
                                    @case(2) {{ __('popup.reviews_popup.mark_so_so') }} @break
                                    @case(3) {{ __('popup.reviews_popup.mark_good') }} @break
                                    @case(4) {{ __('popup.reviews_popup.mark_great') }} @break
                                    @case(5) {{ __('popup.reviews_popup.mark_super') }} @break
                                @endswitch
                            </span>
                        </label>
                    @endfor
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="home-review-photo">{{ __('popup.reviews_popup.photo') }}:</label>
                <div class="home-review-dropzone" id="home-review-dropzone">
                    <input id="home-review-photo" type="file" name="photo" class="home-review-dropzone__input" accept="image/*">
                    <div class="home-review-dropzone__content">
                        <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                        <div class="home-review-dropzone__hint">{{ __('popup.reviews_popup.photo_drop_hint') }}</div>
                        <div id="home-review-photo-name" class="home-review-dropzone__name">{{ __('popup.reviews_popup.photo_empty') }}</div>
                    </div>
                </div>
            </div>

            <div id="home-review-alert" class="clear-text"></div>

            <div class="buttons">
                <button type="submit" class="button colord" id="home-review-submit">
                    <i class="fas fa-chevron-right"></i>{{ __('popup.reviews_popup.send_review') }}
                </button>
            </div>
        </form>
    </div>
    <div class="close popup-close button fal fa-times"></div>
</section>

@push('scripts')
    <script>
        window.wrapReviewLoadingText = @json(__('popup.reviews_popup.loading'));
        window.wrapReviewSuccessText = @json(__('popup.reviews_popup.success_message'));
        window.wrapReviewErrorText = @json(__('popup.reviews_popup.error_message'));
        window.wrapReviewRatingRequiredText = @json(__('popup.reviews_popup.validation.rating_required'));
        window.wrapReviewPhotoEmptyText = @json(__('popup.reviews_popup.photo_empty'));
    </script>
@endpush
