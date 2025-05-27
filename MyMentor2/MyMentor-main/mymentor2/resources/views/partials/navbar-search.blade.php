<form action="{{ route('search') }}" method="GET"
      class="relative flex-1 max-w-sm mx-4">
  <input
    type="text"
    name="q"
    value="{{ request('q') }}"
    placeholder="Recherche…"
    class="w-full border border-gray-300 rounded-full pl-4 pr-10 py-1
           focus:outline-none focus:ring-2 focus:ring-indigo-500"
  />
 <button type="submit"
        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">
  🔍
</button>

</form>
