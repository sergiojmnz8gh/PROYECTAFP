document.addEventListener("DOMContentLoaded", function () {
    const API_FAMILIAS_URL = '/index.php?api=familias';
    const API_CICLOS_URL = '/index.php?api=ciclos';
    async function fetchFamilias(selectElementId, selectedValue = null) {
        const select = document.getElementById(selectElementId);
        if (!select) return;

        select.innerHTML = '<option value="">Cargando familias...</option>';
        try {
            const response = await fetch(API_FAMILIAS_URL);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="">Selecciona una familia</option>';
                result.data.forEach(familia => {
                    const option = document.createElement('option');
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    if (selectedValue !== null && familia.id == selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Error al cargar familias</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    async function fetchCiclos(selectElementId, familiaId = null, selectedValue = null) {
        const select = document.getElementById(selectElementId);
        if (!select) return;

        select.innerHTML = '<option value="">Cargando ciclos...</option>';
        let url = API_CICLOS_URL;
        if (familiaId) {
            url += `&familia_id=${familiaId}`;
        }

        try {
            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="">Selecciona un ciclo</option>';
                if (result.data.length === 0) {
                    select.innerHTML = '<option value="" hidden>Selecciona un ciclo</option>';
                }
                result.data.forEach(ciclo => {
                    const option = document.createElement('option');
                    option.value = ciclo.id;
                    option.textContent = ciclo.nombre;
                    if (selectedValue !== null && ciclo.id == selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Error al cargar ciclos</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    fetchFamilias('familia');
    fetchCiclos('ciclo');
    document.getElementById('familia').addEventListener('change', (event) => {
        const selectedFamiliaId = event.target.value;
        if (selectedFamiliaId) {
            fetchCiclos('ciclo', selectedFamiliaId);
        } else {
            document.getElementById('ciclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
        }
    });
});