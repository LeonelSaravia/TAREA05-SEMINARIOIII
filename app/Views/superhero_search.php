<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Superhéroes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .search-box {
            margin-bottom: 30px;
        }
        .superhero-card {
            margin-top: 20px;
            display: none;
        }
        .power-badge {
            margin: 2px;
        }
        #loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Buscador de Superhéroes</h1>
        
        <div class="search-box">
            <div class="input-group mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Escribe el nombre de un superhéroe (ej. Batman)" aria-label="Superhéroe">
                <button class="btn btn-primary" type="button" id="searchButton">Buscar</button>
            </div>
        </div>

        <div id="loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
            <p class="mt-2">Buscando superhéroes...</p>
        </div>

        <div id="resultsContainer"></div>

        <div id="superheroDetails" class="superhero-card card">
            <div class="card-header">
                <h3 id="heroName"></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre real:</strong> <span id="heroRealName"></span></p>
                        <p><strong>Editorial:</strong> <span id="heroPublisher"></span></p>
                        <p><strong>Alineación:</strong> <span id="heroAlignment"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Género:</strong> <span id="heroGender"></span></p>
                        <p><strong>Raza:</strong> <span id="heroRace"></span></p>
                    </div>
                </div>
                
                <h5 class="mt-4">Atributos</h5>
                <div class="row" id="heroAttributes"></div>
                
                <h5 class="mt-4">Poderes</h5>
                <div id="heroPowers"></div>
                
                <div class="mt-4">
                    <button id="generatePdfBtn" class="btn btn-danger">Generar PDF de Poderes</button>
                </div>
            </div>
        </div>

        <div id="noResults" class="alert alert-warning mt-3" style="display: none;">
            No se encontraron superhéroes con ese nombre.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const resultsContainer = document.getElementById('resultsContainer');
            const superheroDetails = document.getElementById('superheroDetails');
            const noResults = document.getElementById('noResults');
            const loading = document.getElementById('loading');
            
            let currentHeroId = null;

            // Función para buscar superhéroes
            async function searchSuperheroes() {
                const searchTerm = searchInput.value.trim();
                
                if (searchTerm.length < 2) {
                    alert('Por favor, ingresa al menos 2 caracteres para buscar');
                    return;
                }
                
                loading.style.display = 'block';
                resultsContainer.innerHTML = '';
                superheroDetails.style.display = 'none';
                noResults.style.display = 'none';
                
                try {
                    const response = await fetch('<?= base_url('superhero/search') ?>?term=' + encodeURIComponent(searchTerm));
                    const data = await response.json();
                    
                    loading.style.display = 'none';
                    
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    if (data.length === 0) {
                        noResults.style.display = 'block';
                        return;
                    }
                    
                    // Mostrar resultados
                    resultsContainer.innerHTML = '<h4>Resultados de la búsqueda:</h4>';
                    const resultsList = document.createElement('div');
                    resultsList.className = 'list-group';
                    
                    data.forEach(hero => {
                        const listItem = document.createElement('button');
                        listItem.type = 'button';
                        listItem.className = 'list-group-item list-group-item-action';
                        listItem.innerHTML = `
                            <strong>${hero.superhero_name}</strong> 
                            ${hero.full_name ? `(${hero.full_name})` : ''}
                            <br><small>Editorial: ${hero.publisher_name} | Alineación: ${hero.alignment}</small>
                        `;
                        
                        listItem.addEventListener('click', () => {
                            showSuperheroDetails(hero.id);
                        });
                        
                        resultsList.appendChild(listItem);
                    });
                    
                    resultsContainer.appendChild(resultsList);
                    
                } catch (error) {
                    loading.style.display = 'none';
                    console.error('Error:', error);
                    alert('Error al buscar superhéroes');
                }
            }
            
            // Función para mostrar detalles del superhéroe
            async function showSuperheroDetails(heroId) {
                loading.style.display = 'block';
                resultsContainer.innerHTML = '';
                noResults.style.display = 'none';
                
                try {
                    const response = await fetch('<?= base_url('superhero/powers') ?>/' + heroId);
                    const data = await response.json();
                    
                    loading.style.display = 'none';
                    
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    const hero = data.superhero;
                    const powers = data.powers;
                    const attributes = data.attributes;
                    
                    // Actualizar información básica
                    document.getElementById('heroName').textContent = hero.superhero_name;
                    document.getElementById('heroRealName').textContent = hero.full_name || 'Desconocido';
                    document.getElementById('heroPublisher').textContent = hero.publisher_name || 'Desconocido';
                    document.getElementById('heroAlignment').textContent = hero.alignment || 'Desconocido';
                    document.getElementById('heroGender').textContent = hero.gender || 'Desconocido';
                    document.getElementById('heroRace').textContent = hero.race || 'Desconocido';
                    
                    // Mostrar atributos
                    const attributesContainer = document.getElementById('heroAttributes');
                    attributesContainer.innerHTML = '';
                    
                    if (attributes && attributes.length > 0) {
                        attributes.forEach(attr => {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-2';
                            col.innerHTML = `
                                <div class="card">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-0">${attr.attribute_name}</h6>
                                        <div class="progress mt-1" style="height: 10px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: ${attr.attribute_value}%;" 
                                                 aria-valuenow="${attr.attribute_value}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">${attr.attribute_value}/100</small>
                                    </div>
                                </div>
                            `;
                            attributesContainer.appendChild(col);
                        });
                    } else {
                        attributesContainer.innerHTML = '<p>No hay información de atributos disponible.</p>';
                    }
                    
                    // Mostrar poderes
                    const powersContainer = document.getElementById('heroPowers');
                    powersContainer.innerHTML = '';
                    
                    if (powers && powers.length > 0) {
                        powers.forEach(power => {
                            const badge = document.createElement('span');
                            badge.className = 'badge bg-primary power-badge';
                            badge.textContent = power.power_name;
                            powersContainer.appendChild(badge);
                        });
                    } else {
                        powersContainer.innerHTML = '<p>No se encontraron poderes para este superhéroe.</p>';
                    }
                    
                    // Configurar botón de PDF
                    currentHeroId = heroId;
                    const pdfButton = document.getElementById('generatePdfBtn');
                    pdfButton.onclick = () => {
                        window.open('<?= base_url('superhero/generate-pdf') ?>/' + currentHeroId, '_blank');
                    };
                    
                    // Mostrar detalles
                    superheroDetails.style.display = 'block';
                    
                } catch (error) {
                    loading.style.display = 'none';
                    console.error('Error:', error);
                    alert('Error al cargar detalles del superhéroe');
                }
            }
            
            // Event listeners
            searchButton.addEventListener('click', searchSuperheroes);
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchSuperheroes();
                }
            });
        });
    </script>
</body>
</html>