{{-- resources/views/components/input-label.blade.php --}}
@props([
    /** L’attribut “for” (correspond à l’ID du champ associé) */
    'for',

    /** Le texte du label (si `value` n’est pas fourni, on affichera le slot à la place) */
    'value' => null,
])

<label
    for="{{ $for }}"
    class="block font-medium text-sm text-gray-700"
>
    {{ $value ?? $slot }}
</label>
