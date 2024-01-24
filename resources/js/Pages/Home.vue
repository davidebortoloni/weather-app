<script>
import axios from "axios";

export default {
    data() {
        return {
            city: "",
            isLoading: false,
            weather: null,
        };
    },
    methods: {
        getWeather() {
            this.isLoading = true;
            axios
                .get("/weather/" + this.city)
                .then((response) => {
                    this.weather = response.data;
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.error(
                        "Errore durante il recupero dei dati meteo:",
                        error
                    );
                    this.isLoading = false;
                });
        },
        getWeatherImage(is_day, weather_code) {
            let image_name = is_day ? "day_" : "night_";

            switch (weather_code) {
                case 0:
                    image_name = image_name + "clear_sky";
                    break;
                case 1:
                    image_name = image_name + "mostly_clear";
                    break;
                case 2:
                    image_name = image_name + "partly_cloudy";
                    break;
                case 3:
                    image_name = image_name + "cloudy";
                    break;
                case 45:
                    image_name = image_name + "fog";
                    break;
                case 51:
                    image_name = image_name + "light_drizzle";
                    break;
                case 61:
                    image_name = image_name + "light_rain";
                    break;
                case 63:
                    image_name = image_name + "rain";
                    break;
                case 71:
                    image_name = image_name + "light_snow";
                    break;
                case 73:
                    image_name = image_name + "snow";
                    break;
                case 80:
                    image_name = image_name + "light_shower";
                    break;
                case 95:
                    image_name = image_name + "storm";
                    break;
            }

            return "../../images/weather_images/" + image_name + ".png";
        },
    },
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gray-200"
    >
        <img
            width="250px"
            src="../../images/logo.png"
            alt="Logo"
            class="mb-5"
        />
        <div class="weather-card">
            <h1 class="weather-title">Meteo Attuale</h1>
            <div class="mb-4">
                <input
                    type="text"
                    v-model="city"
                    @keyup.enter="getWeather"
                    placeholder="Inserisci una città"
                    class="weather-input"
                />
            </div>
            <div v-if="isLoading" class="weather-spinner-container">
                <div class="weather-spinner"></div>
            </div>
            <div v-else-if="weather" class="weather-data">
                <img
                    v-if="weather.description != 'Unknown'"
                    :src="getWeatherImage(weather.is_day, weather.code)"
                    :alt="weather.description"
                    class="weather-icon"
                />
                <div class="weather-info">
                    <p>
                        <strong>{{ weather.description }}:</strong>
                        {{ weather.temperature }} °C
                    </p>
                    <p>
                        <strong>Velocità del vento:</strong>
                        {{ weather.windspeed }} km/h
                    </p>
                </div>
            </div>
            <div v-else class="weather-error">
                Non è stato possibile recuperare i dati meteo.
            </div>
        </div>
    </div>
</template>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Stili generali */
.min-h-screen {
    min-height: 100vh;
}

.flex {
    display: flex;
}

.flex-col {
    flex-direction: column;
}

.justify-center {
    justify-content: center;
}

.items-center {
    align-items: center;
}

.bg-gray-200 {
    background-color: #e2e8f0;
}

.mb-5 {
    margin-bottom: 1.25rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

/* Stili per il card del meteo */
.weather-card {
    background-color: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    margin: 0 auto;
}

.weather-title {
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.weather-input {
    border: 2px solid #3498db;
    padding: 10px;
    border-radius: 8px;
    width: 100%;
    box-sizing: border-box;
}

/* Stili per il caricamento */
.weather-spinner-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px;
}

.weather-spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Stili per i dati meteo */
.weather-data {
    transition: ease-in 0.3s;
    display: flex;
    justify-content: space-around;
    align-items: center;
    text-align: center;
    margin-top: 10px;
}

.weather-icon {
    width: 75px;
}

.weather-info {
    color: #555;
    font-size: 18px;
}

/* Stili per i messaggi di errore */
.weather-error {
    color: red;
    text-align: center;
}

/* Adattamenti per schermi più piccoli */
@media (max-width: 600px) {
    .weather-card {
        width: 90%;
    }
}
</style>
