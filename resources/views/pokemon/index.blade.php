<h1>Lista de Pokemones</h1>
<ul>
    @foreach($pokemons as $pokemon)
        <li>
            <a href="/pokemon/{{ strtolower($pokemon) }}">{{ $pokemon }}</a>
        </li>
    @endforeach
</ul>
<a href="/">Volver al Inicio</a>