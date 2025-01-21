@props([
    'minutes'
])

<span
    x-data="timer(@js($minutes))"
    x-text="formatTime()"
></span>
