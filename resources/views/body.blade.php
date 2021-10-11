@if($enabled)
    @if($event or $identify)
        <script type="text/javascript">
            {!! $identify !!}
            {!! $event !!}
        </script>
    @endif
@endif
