@props([
    'id',
    'title',
    'size' => 'md',
    'centered' => false,
    'backdrop' => true,
    'keyboard' => true,
    'focus' => true,
    'footerClass' => '',
    'modalClass' => '',
    'closeBtn' => true
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}-title" aria-hidden="true"
    data-backdrop="{{ $backdrop ? 'true' : 'false' }}"
    data-keyboard="{{ $keyboard ? 'true' : 'false' }}"
    data-focus="{{ $focus ? 'true' : 'false' }}">
    <div class="modal-dialog modal-{{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $modalClass }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}-title">{{ $title }}</h5>
                @if($closeBtn)
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @endif
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if (isset($footer))
            <div class="modal-footer {{ $footerClass }}">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>
