@extends('layouts.app')

@section('content')
<style>
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%      { transform: translateX(-8px); }
        40%      { transform: translateX(8px); }
        60%      { transform: translateX(-5px); }
        80%      { transform: translateX(5px); }
    }
    @keyframes floatUp {
        0%   { opacity:1; transform: translateY(0); }
        100% { opacity:0; transform: translateY(-40px); }
    }
    .shake   { animation: shake 0.45s ease; }
    .float-dmg {
        position:absolute; font-weight:900; font-size:1.25rem;
        pointer-events:none; animation: floatUp 0.9s ease forwards;
    }
    .hp-bar { transition: width 0.5s ease; }
    .log-box { scrollbar-width: thin; scrollbar-color: #374151 transparent; }
</style>

<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="text-center mb-8">
        <p class="text-xs font-bold tracking-widest text-yellow-500 uppercase mb-1"><i class="fa-solid fa-bolt mr-1"></i> Modo Batalla</p>
        <h1 class="text-4xl font-black tracking-widest text-white uppercase">
            Pokémon <span class="text-yellow-500">Battle</span>
        </h1>
    </div>

    {{-- FORMULARIO (solo cuando no hay batalla activa) --}}
    @if(!isset($pokemonA))
    <form method="GET" action="{{ route('battle') }}" class="mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">Tu Pokémon</label>
                <input type="text" name="A" value="{{ request('A') }}" autofocus
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    placeholder="Ej: pikachu">
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">Pokémon Rival</label>
                <input type="text" name="B" value="{{ request('B') }}"
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    placeholder="Ej: bulbasaur">
            </div>
        </div>
        <div class="text-center mt-5">
            <button type="submit"
                class="px-10 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all duration-200 shadow-lg hover:-translate-y-1">
                <i class="fa-solid fa-bolt mr-2"></i> ¡Iniciar Batalla!
            </button>
        </div>
    </form>
    @endif

    {{-- ERROR --}}
    @if(isset($error) && $error)
        <div class="p-5 bg-red-500/10 border border-red-500/30 rounded-xl text-center mb-6">
            <p class="text-red-400 font-bold">{{ $error }}</p>
            <a href="{{ route('battle') }}" class="mt-3 inline-block text-xs text-gray-400 hover:text-white underline">← Volver</a>
        </div>
    @endif

    {{-- ARENA DE BATALLA --}}
    @if(isset($pokemonA) && isset($pokemonB))
    <div id="arena" class="bg-gray-800/60 border border-gray-700 rounded-2xl overflow-hidden shadow-2xl">

        {{-- CAMPO --}}
        <div class="relative bg-gradient-to-b from-gray-900 to-gray-800 p-6 min-h-[280px]">

            {{-- ENEMIGO (arriba a la derecha) --}}
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gray-900/70 rounded-xl px-4 py-3 min-w-[200px]">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-black uppercase tracking-widest text-white">{{ $pokemonB['name'] }}</span>
                        <div class="flex gap-1">
                            @foreach($pokemonB['types'] as $t)
                                <span class="type-{{ $t }} px-2 py-0.5 text-[10px] font-bold text-white rounded-full uppercase">{{ $t }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 font-bold w-4">HP</span>
                        <div class="flex-1 h-3 bg-gray-700 rounded-full overflow-hidden">
                            <div id="enemy-hp-bar" class="hp-bar h-full bg-green-500 rounded-full" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="text-right text-xs text-gray-400 mt-1">
                        <span id="enemy-hp-text"></span>
                    </div>
                </div>
                <div class="relative">
                    <img id="enemy-sprite" src="{{ $pokemonB['sprite'] }}" alt="{{ $pokemonB['name'] }}"
                         class="w-32 h-32 object-contain drop-shadow-2xl">
                </div>
            </div>

            {{-- JUGADOR (abajo a la izquierda) --}}
            <div class="flex items-end justify-between mt-4">
                <div class="relative">
                    <img id="player-sprite" src="{{ $pokemonA['sprite'] }}" alt="{{ $pokemonA['name'] }}"
                         class="w-36 h-36 object-contain drop-shadow-2xl" style="transform:scaleX(-1)">
                </div>
                <div class="bg-gray-900/70 rounded-xl px-4 py-3 min-w-[200px]">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-black uppercase tracking-widest text-white">{{ $pokemonA['name'] }}</span>
                        <div class="flex gap-1">
                            @foreach($pokemonA['types'] as $t)
                                <span class="type-{{ $t }} px-2 py-0.5 text-[10px] font-bold text-white rounded-full uppercase">{{ $t }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 font-bold w-4">HP</span>
                        <div class="flex-1 h-3 bg-gray-700 rounded-full overflow-hidden">
                            <div id="player-hp-bar" class="hp-bar h-full bg-green-500 rounded-full" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="text-right text-xs text-gray-400 mt-1">
                        <span id="player-hp-text"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOG DE BATALLA --}}
        <div id="battle-log"
             class="log-box h-28 overflow-y-auto bg-gray-900 border-t border-gray-700 px-5 py-3 font-mono text-sm space-y-1">
        </div>

        {{-- MENÚS DE ACCIÓN --}}
        <div class="border-t border-gray-700 bg-gray-900">

            {{-- Menú principal --}}
            <div id="menu-action" class="grid grid-cols-3 divide-x divide-gray-700">
                <button onclick="showMoves()"
                    class="py-5 font-black text-sm tracking-widest text-white uppercase hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-hand-fist mr-2"></i> Luchar
                </button>
                <button onclick="tryCatch()"
                    class="py-5 font-black text-sm tracking-widest text-white uppercase hover:bg-gray-800 transition-colors">
                    <svg viewBox="0 0 100 100" class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="10"/><line x1="6" y1="50" x2="94" y2="50" stroke="currentColor" stroke-width="10"/><circle cx="50" cy="50" r="15" stroke="currentColor" stroke-width="8"/><circle cx="50" cy="50" r="6" fill="currentColor"/></svg> Atrapar
                </button>
                <button onclick="tryFlee()"
                    class="py-5 font-black text-sm tracking-widest text-white uppercase hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-person-running mr-2"></i> Huir
                </button>
            </div>

            {{-- Menú de movimientos --}}
            <div id="menu-moves" class="hidden">
                <div class="grid grid-cols-2 divide-x divide-y divide-gray-700">
                    @foreach($pokemonA['moves'] as $move)
                    <button onclick="playerAttack('{{ $move }}')"
                        class="py-4 px-4 text-left font-bold text-sm text-white uppercase tracking-wide hover:bg-gray-800 transition-colors">
                        {{ str_replace('-', ' ', $move) }}
                    </button>
                    @endforeach
                </div>
                <button onclick="showPhase('action')"
                    class="w-full py-3 text-xs text-gray-500 uppercase tracking-widest hover:text-gray-300 transition-colors border-t border-gray-700">
                    ← Volver
                </button>
            </div>

            {{-- Resultado final --}}
            <div id="menu-result" class="hidden text-center py-6 px-4">
                <div id="result-icon" class="text-5xl mb-2"></div>
                <div id="result-title" class="text-2xl font-black uppercase tracking-widest"></div>
                <a href="{{ route('battle') }}"
                   class="mt-4 inline-block px-8 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all text-sm">
                    ¡Otra batalla!
                </a>
            </div>
        </div>
    </div>

    <script>
        const A = @json($pokemonA);
        const B = @json($pokemonB);

        const state = {
            playerHP:    A.hp * 3,
            playerMaxHP: A.hp * 3,
            enemyHP:     B.hp * 3,
            enemyMaxHP:  B.hp * 3,
            phase: 'action',
            busy:  false,
        };

        // ── Inicializar ─────────────────────────────
        (function init() {
            updateBars();
            addLog(`¡Un ${B.name} salvaje apareció!`);
            addLog(`¿Qué hará ${A.name}?`);
        })();

        // ── Helpers ──────────────────────────────────
        function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

        function addLog(msg) {
            const log = document.getElementById('battle-log');
            const p   = document.createElement('p');
            p.className   = 'text-gray-300';
            p.textContent = '> ' + msg;
            log.appendChild(p);
            log.scrollTop = log.scrollHeight;
        }

        function hpColor(pct) {
            if (pct > 50) return 'bg-green-500';
            if (pct > 25) return 'bg-yellow-400';
            return 'bg-red-500';
        }

        function updateBars() {
            const pPct = Math.max(0, Math.round(state.playerHP / state.playerMaxHP * 100));
            const ePct = Math.max(0, Math.round(state.enemyHP  / state.enemyMaxHP  * 100));

            const pb = document.getElementById('player-hp-bar');
            const eb = document.getElementById('enemy-hp-bar');

            pb.style.width  = pPct + '%';
            eb.style.width  = ePct + '%';
            pb.className    = `hp-bar h-full rounded-full ${hpColor(pPct)}`;
            eb.className    = `hp-bar h-full rounded-full ${hpColor(ePct)}`;

            document.getElementById('player-hp-text').textContent = `${state.playerHP}/${state.playerMaxHP}`;
            document.getElementById('enemy-hp-text').textContent  = `${state.enemyHP}/${state.enemyMaxHP}`;
        }

        function calcDamage(atk, def) {
            const base = Math.max(5, atk * 0.55 - def * 0.25);
            return Math.round(base * (0.85 + Math.random() * 0.3));
        }

        function shake(id) {
            const el = document.getElementById(id);
            el.classList.add('shake');
            el.addEventListener('animationend', () => el.classList.remove('shake'), { once: true });
        }

        function floatDamage(spriteId, dmg) {
            const sprite = document.getElementById(spriteId);
            const rect   = sprite.getBoundingClientRect();
            const arena  = document.getElementById('arena').getBoundingClientRect();
            const el     = document.createElement('div');
            el.className = 'float-dmg text-red-400';
            el.textContent = `-${dmg}`;
            el.style.left = (rect.left - arena.left + rect.width / 2 - 20) + 'px';
            el.style.top  = (rect.top  - arena.top  + 10) + 'px';
            document.getElementById('arena').style.position = 'relative';
            document.getElementById('arena').appendChild(el);
            el.addEventListener('animationend', () => el.remove());
        }

        function showPhase(phase) {
            state.phase = phase;
            ['menu-action','menu-moves','menu-result'].forEach(id =>
                document.getElementById(id).classList.add('hidden')
            );
            const map = { action:'menu-action', moves:'menu-moves', result:'menu-result' };
            if (map[phase]) document.getElementById(map[phase]).classList.remove('hidden');
        }

        function showMoves() {
            if (state.busy || state.phase !== 'action') return;
            showPhase('moves');
        }

        function showResult(type) {
            const pokeballSvg = `<svg viewBox="0 0 100 100" class="w-14 h-14 mx-auto text-green-400" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="10"/><line x1="6" y1="50" x2="94" y2="50" stroke="currentColor" stroke-width="10"/><circle cx="50" cy="50" r="15" stroke="currentColor" stroke-width="8"/><circle cx="50" cy="50" r="6" fill="currentColor"/></svg>`;
            const cfg = {
                win:    { icon:'<i class="fa-solid fa-trophy text-yellow-500 text-5xl"></i>',       title:`¡${A.name} ganó!`,        cls:'text-yellow-500' },
                lose:   { icon:'<i class="fa-solid fa-skull text-red-400 text-5xl"></i>',           title:'¡Perdiste...',             cls:'text-red-400'    },
                flee:   { icon:'<i class="fa-solid fa-person-running text-blue-400 text-5xl"></i>', title:'¡Escapaste!',              cls:'text-blue-400'   },
                caught: { icon: pokeballSvg,                                                        title:`¡${B.name} fue atrapado!`, cls:'text-green-400'  },
            };
            const c = cfg[type];
            document.getElementById('result-icon').innerHTML  = c.icon;
            const t = document.getElementById('result-title');
            t.textContent = c.title;
            t.className   = `text-2xl font-black uppercase tracking-widest ${c.cls}`;
            showPhase('result');
            state.busy = false;
        }

        // ── Turno del enemigo ─────────────────────────
        async function enemyTurn() {
            await sleep(700);
            const move = B.moves.length ? B.moves[Math.floor(Math.random() * B.moves.length)] : 'tackle';
            const dmg  = calcDamage(B.attack, A.defense);
            state.playerHP = Math.max(0, state.playerHP - dmg);
            updateBars();
            floatDamage('player-sprite', dmg);
            shake('player-sprite');
            addLog(`¡${B.name} usó ${move.replace(/-/g,' ')}! Causó ${dmg} de daño.`);
            await sleep(600);

            if (state.playerHP <= 0) {
                addLog(`¡${A.name} se debilitó!`);
                showResult('lose');
                return false;
            }
            return true;
        }

        // ── Acciones del jugador ──────────────────────
        async function playerAttack(moveName) {
            if (state.busy || state.phase !== 'moves') return;
            state.busy = true;
            showPhase('none');

            const dmg = calcDamage(A.attack, B.defense);
            state.enemyHP = Math.max(0, state.enemyHP - dmg);
            updateBars();
            floatDamage('enemy-sprite', dmg);
            shake('enemy-sprite');
            addLog(`${A.name} usó ${moveName.replace(/-/g,' ')}! Causó ${dmg} de daño.`);
            await sleep(600);

            if (state.enemyHP <= 0) {
                addLog(`¡${B.name} se debilitó! ¡Ganaste!`);
                showResult('win');
                return;
            }

            const survived = await enemyTurn();
            if (survived) {
                addLog(`¿Qué hará ${A.name}?`);
                showPhase('action');
                state.busy = false;
            }
        }

        async function tryCatch() {
            if (state.busy || state.phase !== 'action') return;
            state.busy = true;
            showPhase('none');

            const hpRatio = state.enemyHP / state.enemyMaxHP;
            const chance  = hpRatio < 0.25 ? 0.75 : hpRatio < 0.5 ? 0.45 : 0.25;

            addLog('¡Lanzaste una Pokéball!');
            await sleep(700);
            addLog('.');
            await sleep(500);
            addLog('. .');
            await sleep(500);
            addLog('. . .');
            await sleep(500);

            if (Math.random() < chance) {
                showResult('caught');
                return;
            }

            addLog(`¡${B.name} se escapó de la Pokéball!`);
            const survived = await enemyTurn();
            if (survived) {
                addLog(`¿Qué hará ${A.name}?`);
                showPhase('action');
                state.busy = false;
            }
        }

        async function tryFlee() {
            if (state.busy || state.phase !== 'action') return;
            state.busy = true;
            showPhase('none');
            addLog(`¡${A.name} huyó con éxito!`);
            await sleep(800);
            showResult('flee');
        }
    </script>
    @endif

</div>
@endsection
