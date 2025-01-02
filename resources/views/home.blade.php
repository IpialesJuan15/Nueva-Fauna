@extends('template.layout')

@section('title', 'Inicio - iNaturalist Ecuador')

@section('content')
    <section class="hero">
        <div class="hero-slider">
            <button class="slider-btn slider-prev">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button class="slider-btn slider-next">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
        <div class="hero-content">
            <div class="hero-card">
                <h1>Conéctate con la naturaleza</h1>
                <p>Explora y comparte tus observaciones de la naturaleza.</p>
                <div class="hero-buttons">
                    <button class="btn btn-white">REGÍSTRATE</button>
                    <button class="btn btn-outline-white">EXPLORA</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Resto de las secciones -->
    <section id="explora" class="how-it-works">
        <h2>Cómo funciona</h2>
        <div class="steps-container">
            <!-- Pasos -->
        </div>
    </section>

    <section id="comunidad" class="community">
        <h2>Comunidad iNaturalist Ecuador</h2>
        <div class="stats-container">
            <!-- Estadísticas -->
        </div>
    </section>

    <section id="mas" class="recent-observations">
        <h2>Observaciones Recientes</h2>
        <div class="observations-grid">
            <!-- Observaciones -->
        </div>
    </section>
@endsection
