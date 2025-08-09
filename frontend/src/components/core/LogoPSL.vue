<template>
    <svg
        width="200"
        height="60"
        viewBox="0 0 400 120"
        xmlns="http://www.w3.org/2000/svg"
    >
        <rect width="100%" height="100%" fill="white" />

        <!-- Icône : boîte/logistique -->
        <g transform="translate(10,10)">
            <rect x="0" y="30" width="50" height="50" rx="5" fill="#2B6CB0" />
            <rect x="10" y="20" width="30" height="30" fill="#3182CE" />
            <line
                x1="10"
                y1="20"
                x2="0"
                y2="30"
                stroke="#1A365D"
                stroke-width="2"
            />
            <line
                x1="40"
                y1="20"
                x2="50"
                y2="30"
                stroke="#1A365D"
                stroke-width="2"
            />
        </g>

        <!-- Texte : PSL -->
        <text
            x="80"
            y="65"
            font-family="Segoe UI, sans-serif"
            font-size="48"
            fill="#2D3748"
            font-weight="bold"
        >
            PSL
        </text>

        <!-- Slogan -->
        <text
            x="80"
            y="95"
            font-family="Segoe UI, sans-serif"
            font-size="16"
            fill="#4A5568"
            font-style="italic"
        >
            Plateforme logistique & gestion de flux
        </text>
    </svg>
</template>
