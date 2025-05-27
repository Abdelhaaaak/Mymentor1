@props([
  'id',
  'name',
  'label',
  'type'     => 'text',    // default to "text" if none is passed
  'required' => false,     // default to false
])

<div class="mb-4">
  <label for="{{ $id }}" class="block text-gray-700">
    {{ $label }}
  </label>
  <input
    id="{{ $id }}"
    name="{{ $name }}"
    type="{{ $type }}"
    {{ $required ? 'required' : '' }}
    class="w-full border rounded px-3 py-2"
  />
</div>
