<style>
    :root {
        --dot-diameter: 300px;
        --circle-border-width: 3px;
        --default-color: var(--couleur-gris-clair);
    }

    span {
        color: transparent;
    }

    .container {
        margin: auto;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-items: center;
        align-content: stretch;
        align-items: center;
    }

    .flex-col {
        display: flex;
        flex-direction: column;
        align-content: stretch;
        text-align: center;
        color: white;
        text-transform: uppercase;
        gap: 24px;
        width: 450px;
    }

    .circle-container {
        width: var(--dot-diameter);
        height: var(--dot-diameter);
        transform: rotate(-225deg);
        fill: none;
        stroke: white;
        stroke-dasharray: 75 100;
        stroke-linecap: round;
    }

    .circle-container__background {
        fill: none;
        stroke: var(--default-color);
        stroke-width: var(--circle-border-width);
        stroke-dasharray: 75 100;
        stroke-linecap: round;
    }

    .circle-container__progress {
        fill: none;
        stroke-linecap: round;
        stroke: url(#gradient);
        stroke-dasharray: 75 100;
        stroke-linecap: round;
        stroke-width: var(--circle-border-width);
        transition: stroke-dashoffset 1s ease-in-out;
    }

    .stop1 {
        stop-color: #25a60f;

    }

    .stop2 {
        stop-color: green;
    }

    .circle-button {
        position: absolute;
        top: 10rem;   
        height: 10rem;
        line-height: 80px;
        width: 10rem;
        font-size: 2em;
        font-weight: bold;
        border-radius: 50%;
        background-color: red;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    .prix {
        display: flex;
    }

    .prix * {
        padding: 0 1rem 0 1rem;
    }
</style>


<div class="container">
    <svg class="circle-container" viewBox="2 -2 28 36">
        <linearGradient id="gradient">
            <stop class="stop1" offset="0%" />
            <stop class="stop2" offset="100%" />
        </linearGradient>
        <circle class="circle-container__background" r="16" cx="16" cy="16" shape-rendering="geometricPrecision">
        </circle>
        <circle class="circle-container__progress" r="16" cx="16" cy="16" style="stroke-dashoffset: 74"
            shape-rendering="geometricPrecision">
        </circle>
        <!-- stroke-dashoffset = la progression de la barre (0 <= x < 75)-->
    </svg>

    <button class="circle-button">Enchérir</button>

    <div class="prix">
        <div>
    <p>Prix minimum</p>
    <p id="min">X</p>

</div>
    <div>

    <p>Prix actuel</p>
    <p id="act">X</p>

</div>
    <div>

    <p>Prix maximum</p>
    <p id="max">X</p>
</div>
</div>
</div>